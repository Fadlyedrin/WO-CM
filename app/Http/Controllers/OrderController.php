<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    // Admin functions
    public function adminOrders()
    {
        Order::autoUpdateExpiredStatuses();
        $orders = Order::with('user', 'package')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'             => 'required|in:pending,paid,cancelled,completed',
            'cancellation_notes' => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        // Hitung refund otomatis saat pembatalan dengan ada pembayaran
        if ($request->status === 'cancelled' && $order->paid_amount > 0) {
            // Kebijakan: refund 10% dari DP yang sudah dibayar
            $refundAmount = (int) round($order->paid_amount * 0.10);
            $data['refund_amount']        = $refundAmount;
            $data['refunded_at']          = now();
            $data['cancellation_notes']   = $request->cancellation_notes;
        } elseif ($request->status === 'cancelled') {
            $data['cancellation_notes']   = $request->cancellation_notes;
            $data['refund_amount']        = 0;
        }

        $order->update($data);

        if ($request->status === 'cancelled') {
            $order->payments()->whereIn('status', ['pending', 'unpaid'])->update(['status' => 'failed']);
        }

        $msg = $request->status === 'cancelled' && $order->paid_amount > 0
            ? 'Pesanan dibatalkan. Dana refund Rp ' . number_format($order->refund_amount, 0, ',', '.') . ' (10% dari DP) siap dikembalikan ke klien.'
            : 'Status pesanan berhasil diperbarui.';

        return redirect()->back()->with('success', $msg);
    }

    public function adminShow(Order $order)
    {
        Order::autoUpdateExpiredStatuses();
        $order->load('user', 'package', 'promo');
        return view('admin.orders.show', compact('order'));
    }

    // User functions
    public function userOrders()
    {
        Order::autoUpdateExpiredStatuses();
        $orders = Order::where('user_id', Auth::id())->with('package')->latest()->get();
        return view('user.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        Order::autoUpdateExpiredStatuses();
        // Ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $order->load('package', 'user');
        $layout = 'layouts.app';
        return view('shared.invoice', compact('order', 'layout'));
    }

    public function downloadContract(Order $order)
    {
        if (Auth::user()->is_admin || Auth::id() === $order->user_id) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.contract', compact('order'));
            return $pdf->download('MoU_Kontrak_CAHAYA_' . $order->id . '.pdf');
        }
        abort(403);
    }

    public function getBookedDates()
    {
        Order::autoUpdateExpiredStatuses();
        $bookedDates = Order::where('status', '!=', 'cancelled')
            ->selectRaw('DATE(wedding_date) as date, count(*) as count')
            ->groupBy('date')
            ->havingRaw('count >= 2')
            ->pluck('date');

        return response()->json($bookedDates)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    // Checkout Process
    public function checkoutSummary(Request $request, Package $package)
    {

        if (empty(Auth::user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi nomor telepon Anda terlebih dahulu sebelum melakukan pemesanan.');
        }
        $minDate = Carbon::now()->addDays(29)->format('Y-m-d');

        $request->validate([
            'wedding_date'        => 'required|date|after:' . $minDate,
            'start_time'          => 'required|date_format:H:i',
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::createFromFormat('H:i', $request->start_time);
                    $end = Carbon::createFromFormat('H:i', $value);
                    if ($start->diffInMinutes($end) > 480) { // 480 menit = 8 jam
                        $fail('Durasi acara maksimal 8 jam.');
                    }
                },
            ],
            'venue_name'          => 'required|string|max:255',
            'venue_address'       => 'required|string',
            'notes'               => 'nullable|string',
            'payment_type'        => 'required|in:full,dp',
            'excluded_components' => 'nullable|array',
            'excluded_components.*' => 'integer|exists:package_components,id',
            'vendor_notes'        => 'nullable|array',
        ], [
            'wedding_date.after' => 'Pemesanan harus dilakukan minimal 30 hari sebelum tanggal pernikahan.',
            'end_time.after'     => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $excludedIds = $request->input('excluded_components', []);

        // Hybrid Logic: Calculate Base Fee
        $totalAllComponents = \App\Models\PackageComponent::where('package_id', $package->id)->sum('price');
        $baseFee = $package->price - $totalAllComponents;
        if ($baseFee < 0) $baseFee = 0;

        // Calculate Checked Components Sum
        $checkedComponentsSum = \App\Models\PackageComponent::where('package_id', $package->id)
            ->whereNotIn('id', $excludedIds)
            ->sum('price');

        $totalPrice = $baseFee + $checkedComponentsSum;

        // Fallback if package has no components
        if ($totalAllComponents === 0) {
            $totalPrice = $package->price;
        }

        $componentDiscount = 0;

        if ($totalPrice < 5000000 && $request->payment_type === 'dp') {
            return back()->with('error', 'Paket di bawah Rp 5.000.000 tidak bisa menggunakan sistem DP. Silakan pilih Full Payment.')->withInput();
        }

        $wedding_date  = $request->wedding_date;
        $start_time    = $request->start_time;
        $end_time      = $request->end_time;
        $venue_name    = $request->venue_name;
        $venue_address = $request->venue_address;
        $notes         = $request->notes;
        $payment_type  = $request->payment_type;
        $vendor_notes  = $request->input('vendor_notes', []);
        $paidAmount    = $payment_type === 'dp' ? ($totalPrice * 0.3) : $totalPrice;

        $dateCount = Order::where('status', '!=', 'cancelled')
            ->whereDate('wedding_date', $wedding_date)
            ->count();

        if ($dateCount >= 2) {
            return back()->with('error', 'Maaf, tanggal yang dipilih sudah penuh (maksimal 2 acara per hari). Silakan pilih tanggal lain.')->withInput();
        }

        // Load excluded component details for summary display
        $excludedComponents = \App\Models\PackageComponent::whereIn('id', $excludedIds)->get();

        return view('checkout.summary', compact(
            'package',
            'wedding_date',
            'start_time',
            'end_time',
            'venue_name',
            'venue_address',
            'notes',
            'payment_type',
            'totalPrice',
            'paidAmount',
            'componentDiscount',
            'excludedIds',
            'excludedComponents',
            'vendor_notes',
            'baseFee'
        ));
    }

    public function checkout(Request $request, Package $package)
    {
        if (empty(Auth::user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi nomor telepon Anda terlebih dahulu sebelum melakukan pemesanan.');
        }
        $minDate = Carbon::now()->addDays(9)->format('Y-m-d');

        $request->validate([
            'wedding_date'          => 'required|date|after:' . $minDate,
            'start_time'            => 'required|date_format:H:i',
            'end_time'              => 'required|date_format:H:i|after:start_time',
            'venue_name'            => 'required|string|max:255',
            'venue_address'         => 'required|string',
            'notes'                 => 'nullable|string',
            'payment_type'          => 'required|in:full,dp',
            'promo_code'            => 'nullable|string',
            'excluded_components'   => 'nullable|array',
            'excluded_components.*' => 'integer|exists:package_components,id',
            'vendor_notes'          => 'nullable|array',
        ]);

        $excludedIds = $request->input('excluded_components', []);

        // Hybrid Logic: Calculate Base Fee
        $totalAllComponents = \App\Models\PackageComponent::where('package_id', $package->id)->sum('price');
        $baseFee = $package->price - $totalAllComponents;
        if ($baseFee < 0) $baseFee = 0;

        // Calculate Checked Components Sum
        $checkedComponentsSum = \App\Models\PackageComponent::where('package_id', $package->id)
            ->whereNotIn('id', $excludedIds)
            ->sum('price');

        $totalPrice = $baseFee + $checkedComponentsSum;

        // Fallback if package has no components
        if ($totalAllComponents === 0) {
            $totalPrice = $package->price;
        }

        $componentDiscount = 0;

        if ($totalPrice < 5000000 && $request->payment_type === 'dp') {
            return back()->with('error', 'Paket di bawah Rp 5.000.000 tidak bisa menggunakan sistem DP. Silakan pilih Full Payment.')->withInput();
        }

        $dateCount = Order::where('status', '!=', 'cancelled')
            ->whereDate('wedding_date', $request->wedding_date)
            ->count();

        if ($dateCount >= 2) {
            return back()->with('error', 'Maaf, tanggal yang dipilih sudah penuh (maksimal 2 acara per hari). Silakan pilih tanggal lain.')->withInput();
        }

        $discountAmount = 0;
        $promoId = null;

        if ($request->filled('promo_code')) {
            $promo = \App\Models\Promo::where('code', strtoupper($request->promo_code))->where('is_active', true)->first();
            if ($promo && (!$promo->valid_until || !$promo->valid_until->isPast()) && (!$promo->max_uses || $promo->uses < $promo->max_uses)) {
                $promoId = $promo->id;
                if ($promo->discount_type === 'fixed') {
                    $discountAmount = $promo->discount_value;
                } else {
                    $discountAmount = ($promo->discount_value / 100) * $totalPrice;
                }
                if ($discountAmount > $totalPrice) {
                    $discountAmount = $totalPrice;
                }
                $totalPrice -= $discountAmount;
                $promo->increment('uses');
            }
        }

        $weddingDate = \Carbon\Carbon::parse($request->wedding_date);
        $today = now();

        $order = Order::create([
            'user_id'             => Auth::id(),
            'package_id'          => $package->id,
            'promo_id'            => $promoId,
            'wedding_date'        => $request->wedding_date,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
            'venue_name'          => $request->venue_name,
            'venue_address'       => $request->venue_address,
            'notes'               => $request->notes,
            'payment_type'        => $request->payment_type,
            'total_price'         => $totalPrice,
            'discount_amount'     => $discountAmount,
            'component_discount'  => $componentDiscount,
            'excluded_components' => !empty($excludedIds) ? $excludedIds : null,
            'vendor_notes'        => !empty($request->vendor_notes) ? $request->vendor_notes : null,
            'paid_amount'         => 0,
            'status'              => 'pending',
        ]);

        if ($request->payment_type === 'dp') {
            $daysToWedding = $today->diffInDays($weddingDate);
            $dpDueDate = $today->copy()->addDay();
            $pelunasanDueDate = $weddingDate->copy()->subDays(min(14, max(1, (int)($daysToWedding * 0.1))));
            $termin1DueDate = $today->copy()->addDays((int)($today->diffInDays($pelunasanDueDate) / 2));

            $activePayment = $order->payments()->create(['name' => 'Down Payment (30%)', 'amount' => $totalPrice * 0.3, 'status' => 'pending', 'due_date' => $dpDueDate]);
            $order->payments()->create(['name' => 'Termin 1 (30%)', 'amount' => $totalPrice * 0.3, 'status' => 'unpaid', 'due_date' => $termin1DueDate]);
            $order->payments()->create(['name' => 'Pelunasan (40%)', 'amount' => $totalPrice * 0.4, 'status' => 'unpaid', 'due_date' => $pelunasanDueDate]);
        } else {
            $dpDueDate = $today->copy()->addDay();
            $activePayment = $order->payments()->create(['name' => 'Full Payment (100%)', 'amount' => $totalPrice, 'status' => 'pending', 'due_date' => $dpDueDate]);
        }

        // Midtrans config
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'PAY-' . $activePayment->id . '-' . time(),
                'gross_amount' => $activePayment->amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $activePayment->update(['snap_token' => $snapToken]);
            $order->update(['snap_token' => $snapToken]); // Keep for backward compatibility of UI

            $msg = 'Order placed successfully. Processing your payment...';
            return redirect()->route('user.orders.show', $order)
                ->with('success', $msg)
                ->with('auto_pay_token', $snapToken)
                ->with('auto_pay_type', $order->payment_type);
        } catch (\Exception $e) {
            $order->delete();
            return redirect()->route('packages.show', $package)->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function getPaymentSnap(\App\Models\Payment $payment)
    {
        if ($payment->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($payment->snap_token) {
            return response()->json(['token' => $payment->snap_token]);
        }

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'PAY-' . $payment->id . '-' . time(),
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $payment->update(['snap_token' => $snapToken]);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if (hash_equals($hashed, (string) $request->signature_key)) {
            $parts = explode('-', $request->order_id);
            if (count($parts) >= 2 && $parts[0] === 'PAY') {
                $payment = \App\Models\Payment::find($parts[1]);
                if ($payment) {
                    $this->processPaymentStatus($payment, $request->transaction_status);
                }
            } else if (count($parts) >= 2 && $parts[0] === 'WO') {
                // Backward compatibility for old orders
                $order = Order::find($parts[1]);
                if ($order && ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement')) {
                    $order->update(['status' => 'paid', 'paid_amount' => $order->total_price]);
                }
            }
        }
        return response()->json(['message' => 'Callback received']);
    }

    public function syncStatus(Request $request)
    {
        $orderId = $request->order_id;
        if (!$orderId) {
            return response()->json(['success' => false]);
        }

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($orderId);
            $parts = explode('-', $orderId);
            if (count($parts) >= 2 && $parts[0] === 'PAY') {
                $payment = \App\Models\Payment::find($parts[1]);
                if ($payment) {
                    if ($payment->order->user_id !== Auth::id()) {
                        abort(403, 'Unauthorized action.');
                    }
                    $this->processPaymentStatus($payment, $status->transaction_status);
                    return response()->json(['success' => true]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

        return response()->json(['success' => false]);
    }

    private function processPaymentStatus($payment, $transactionStatus)
    {
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            DB::transaction(function () use ($payment) {
                $lockedPayment = Payment::whereKey($payment->id)->lockForUpdate()->first();
                if (!$lockedPayment || $lockedPayment->status === 'paid') {
                    return;
                }

                $lockedPayment->update(['status' => 'paid', 'paid_at' => now()]);

                $order = Order::whereKey($lockedPayment->order_id)->lockForUpdate()->first();
                $newPaidAmount = $order->paid_amount + $lockedPayment->amount;

                $order->update([
                    'paid_amount' => $newPaidAmount,
                    'status' => $newPaidAmount >= $order->total_price ? 'paid' : 'pending'
                ]);

                // Activate next termin if any
                $nextPayment = $order->payments()->where('status', 'unpaid')->orderBy('id')->first();
                if ($nextPayment) {
                    $nextPayment->update(['status' => 'pending']);
                }
            });
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $payment->update(['status' => 'failed']);
        }
    }
}
