@extends($layout)
@section('content')
<div class="container py-4 mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
                @if($layout === 'admin.layout')
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-custom" style="padding: 0.5rem 1rem;"><i class="bi bi-arrow-left me-2"></i>Back to Orders</a>
                @else
                    <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-custom" style="padding: 0.5rem 1rem;"><i class="bi bi-arrow-left me-2"></i>Back to My Orders</a>
                @endif

                @if($order->status == 'paid')
                    <button onclick="window.print()" class="btn btn-sm btn-elegant" style="padding: 0.5rem 1rem;"><i class="bi bi-printer me-2"></i>Print Invoice</button>
                @endif
            </div>

            <div class="card elegant-card border-0 invoice-card" style="border-top: 5px solid var(--accent-color) !important;">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="row mb-5 pb-4 border-bottom">
                        <div class="col-sm-6">
                            <h2 class="fw-bold mb-1">
                                <img src="{{ asset('images/logo.png') }}" alt="Cahaya Minang" style="height: 60px; width: auto;">
                            </h2>
                            <p class="text-muted mb-0" style="font-family: var(--font-heading); font-size: 1.1rem; font-style: italic;">Wedding Services</p>
                            <p class="text-muted small mt-3 mb-0">Jalan otista, Jakarta</p>
                            <p class="text-muted small">hello@cahayaminang.com | +62 812 3456 7890</p>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                            @if($order->status == 'paid')
                                <h1 class="text-uppercase fw-bold text-success" style="letter-spacing: 3px; font-size: 2rem;">INVOICE</h1>
                            @else
                                <h1 class="text-uppercase fw-bold text-secondary" style="letter-spacing: 3px; font-size: 1.8rem;">ORDER DETAIL</h1>
                            @endif
                            <p class="mb-0 mt-3"><span class="text-muted">Order ID:</span> <span class="fw-bold">#WO-{{ $order->id }}</span></p>
                            <p class="mb-0"><span class="text-muted">Date Issued:</span> <span class="fw-bold">{{ $order->created_at->format('d M Y') }}</span></p>
                            <div class="mt-3">
                                @if($order->computed_status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-0 px-3 py-2 fw-normal">IN PROGRESS</span>
                                @elseif($order->computed_status == 'paid')
                                    <span class="badge bg-success rounded-0 px-3 py-2 fw-normal">PAID</span>
                                @elseif($order->computed_status == 'completed')
                                    <span class="badge bg-info rounded-0 px-3 py-2 fw-normal">COMPLETED</span>
                                @else
                                    <span class="badge bg-danger rounded-0 px-3 py-2 fw-normal">CANCELLED</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Client & Event Info -->
                    <div class="row mb-5">
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3" style="letter-spacing: 2px;">Billed To:</h6>
                            <h5 class="fw-bold mb-1" style="font-family: var(--font-heading); font-size: 1.5rem;">{{ $order->user->name }}</h5>
                            <p class="text-muted mb-1">{{ $order->user->email }}</p>
                            @if($order->user->phone)
                                <p class="text-muted mb-0"><i class="bi bi-telephone me-2"></i>{{ $order->user->phone }}</p>
                            @endif
                        </div>
                        <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3" style="letter-spacing: 2px;">Event Details:</h6>
                            <h5 class="fw-bold mb-1" style="font-family: var(--font-heading); font-size: 1.5rem;">{{ \Carbon\Carbon::parse($order->wedding_date)->format('d F Y') }}</h5>
                            <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }}</p>
                            <p class="text-muted mb-1">{{ $order->venue_name }}</p>

                            <a href="{{ route('orders.contract', $order) }}" class="btn btn-sm btn-outline-dark mt-2" target="_blank" title="Unduh Memorandum of Understanding">
                                <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Download MoU Contract
                            </a>
                            <p class="text-muted small mb-0">{{ $order->venue_address }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-5 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted text-uppercase small fw-bold mb-0" style="letter-spacing: 2px;">Payment Progress</h6>
                            <span class="fw-bold" style="color: var(--accent-color);">{{ $order->progress_percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 0;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $order->progress_percentage }}%;" aria-valuenow="{{ $order->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="table-responsive mb-5">
                        <table class="table table-borderless">
                            <thead style="background-color: #f9f9f9;">
                                <tr>
                                    <th class="py-3 text-muted text-uppercase small" style="letter-spacing: 2px;">Deskripsi</th>
                                    <th class="py-3 text-muted text-uppercase small text-end" style="letter-spacing: 2px;">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td class="py-4">
                                        <h6 class="fw-bold mb-1" style="font-family: var(--font-heading); font-size: 1.3rem;">{{ $order->package->name }} Package</h6>
                                        <p class="text-muted small mb-0">Layanan wedding organizer sesuai paket yang disepakati.</p>
                                    </td>
                                    <td class="py-4 text-end fw-bold" style="font-size: 1.1rem;">Rp {{ number_format($order->package->price, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->component_discount > 0)
                                @php
                                    $excComps = \App\Models\PackageComponent::whereIn('id', $order->excluded_components ?? [])->get();
                                @endphp
                                @foreach($excComps as $eComp)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td class="py-2 ps-3">
                                        <span class="text-warning small"><i class="bi bi-scissors me-2"></i>Bawa Vendor Sendiri: {{ $eComp->name }}</span>
                                        @if($order->vendor_notes && isset($order->vendor_notes[$eComp->id]) && $order->vendor_notes[$eComp->id])
                                        <div class="text-muted" style="font-size:0.78rem;">Vendor: {{ $order->vendor_notes[$eComp->id] }}</div>
                                        @endif
                                    </td>
                                    <td class="py-2 text-end text-warning fw-semibold">- Rp {{ number_format($eComp->price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                @endif
                                @if($order->discount_amount > 0)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td class="py-2 ps-3">
                                        <span class="text-success small"><i class="bi bi-tag me-2"></i>Diskon Kode Promo {{ $order->promo->code ?? '' }}</span>
                                    </td>
                                    <td class="py-2 text-end text-success fw-semibold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Installments Schedule -->
                    <h6 class="text-muted text-uppercase small fw-bold mb-3" style="letter-spacing: 2px;">Installment Schedule</h6>
                    <div class="table-responsive mb-5 pb-4 border-bottom">
                        <table class="table table-sm align-middle">
                            <thead style="background-color: #f9f9f9;">
                                <tr>
                                    <th class="py-2 px-3 text-muted small">Termin</th>
                                    <th class="py-2 text-muted small">Amount</th>
                                    <th class="py-2 text-muted small">Status</th>
                                    @if($layout === 'layouts.app')
                                    <th class="py-2 px-3 text-muted small text-end">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->payments as $payment)
                                @php
                                    $isOverdue = $payment->status === 'pending' && $payment->due_date && $payment->due_date->endOfDay()->isPast();
                                @endphp
                                <tr @if($isOverdue) style="background-color: rgba(220, 53, 69, 0.05);" @endif>
                                    <td class="py-3 px-3">
                                        <div class="fw-bold">{{ $payment->name }}</div>
                                        @if($payment->due_date && $payment->status !== 'paid')
                                            <div class="small text-muted">Due: {{ $payment->due_date->format('d M Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        @if($payment->status == 'paid')
                                            <span class="badge bg-success rounded-0">Paid on {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</span>
                                        @elseif($isOverdue)
                                            <span class="badge bg-danger rounded-0 shadow-sm" style="animation: pulse 2s infinite;">OVERDUE</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning text-dark rounded-0">Due Now</span>
                                        @else
                                            <span class="badge bg-secondary rounded-0 text-capitalize">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    @if($layout === 'layouts.app')
                                    <td class="py-3 px-3 text-end">
                                        @if($payment->status == 'pending')
                                            <button onclick="payInstallment({{ $payment->id }})" class="btn btn-sm btn-elegant px-3 py-1">Pay Now</button>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="row justify-content-end mb-5">
                        <div class="col-sm-6 col-md-5">
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    @if($order->component_discount > 0 || $order->discount_amount > 0)
                                    <tr>
                                        <td class="text-muted">Harga Paket</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($order->package->price, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($order->component_discount > 0)
                                    <tr>
                                        <td class="text-warning"><i class="bi bi-scissors me-1"></i>Vendor Sendiri</td>
                                        <td class="text-end fw-bold text-warning">- Rp {{ number_format($order->component_discount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td class="text-success"><i class="bi bi-tag me-1"></i>Diskon Promo</td>
                                        <td class="text-end fw-bold text-success">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @endif
                                    <tr>
                                        <td class="text-muted">Total Tagihan</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-bottom pb-3">Sudah Dibayar</td>
                                        <td class="text-end fw-bold text-success border-bottom pb-3">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pt-3 fw-bold" style="font-family: var(--font-heading); font-size: 1.2rem;">Sisa Tagihan</td>
                                        <td class="pt-3 text-end fw-bold {{ ($order->total_price - $order->paid_amount) > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 1.2rem;">
                                            Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center pt-4 border-top">
                        <p class="text-muted small mb-0" style="font-family: var(--font-heading); font-style: italic; font-size: 1.1rem;">Thank you for trusting Cahaya Minang.</p>
                        @if($order->total_price - $order->paid_amount > 0)
                            <p class="text-muted small mt-2">Please ensure the balance due is settled according to the installment schedule.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: #fff !important; }
        .d-print-none { display: none !important; }
        .invoice-card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
            border-top: 5px solid var(--accent-color) !important;
        }
        .container, .row, .col-lg-8 { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        nav, footer, .sidebar { display: none !important; }
    }
</style>

@if($layout === 'layouts.app')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
function payInstallment(paymentId) {
    Swal.fire({
        title: 'Preparing Payment...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
    });

    fetch(`/payment/${paymentId}/snap`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        Swal.close();
        if(data.token) {
            snap.pay(data.token, {
                onSuccess: function(result){
                    Swal.fire({
                        title: 'Verifying Payment...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });

                    fetch('/midtrans/sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ order_id: result.order_id })
                    }).then(() => {
                        Swal.fire('Success', 'Payment completed successfully!', 'success').then(() => window.location.reload());
                    });
                },
                onPending: function(result){
                    Swal.fire('Pending', 'Waiting for your payment!', 'info');
                },
                onError: function(result){
                    Swal.fire('Failed', 'Payment failed! Please try again.', 'error');
                },
                onClose: function(){
                    Swal.fire('Cancelled', 'You closed the payment window.', 'warning');
                }
            });
        } else {
            Swal.fire('Error', data.error || 'Failed to get payment token.', 'error');
        }
    })
    .catch(e => {
        Swal.close();
        Swal.fire('Error', 'Network error.', 'error');
    });
}

// Auto-trigger payment if redirected here right after checkout
@if(session('auto_pay_token') && session('auto_pay_type'))
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        snap.pay('{{ session('auto_pay_token') }}', {
            onSuccess: function(result){
                Swal.fire({
                    title: 'Verifying Payment...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

                fetch('{{ route('midtrans.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order_id: result.order_id })
                }).then(response => response.json())
                .then(data => {
                    let msg = '{{ session('auto_pay_type') }}' === 'dp'
                        ? 'Pembayaran DP Berhasil! Tim dari sanggar akan segera menghubungi Anda untuk info pelunasan.'
                        : 'Pembayaran Lunas Berhasil! Tim dari Sanggar Cahaya Minang akan segera menghubungi nomor yang tertera.';
                    Swal.fire('Berhasil!', msg, 'success').then(() => window.location.reload());
                }).catch(() => window.location.reload());
            },
            onPending: function(result){
                Swal.fire('Pending', 'Waiting for your payment!', 'info');
            },
            onError: function(result){
                Swal.fire('Failed', 'Payment failed! Please try again.', 'error');
            },
            onClose: function(){
                Swal.fire('Cancelled', 'You closed the payment window without finishing payment. You can pay later from this page.', 'warning');
            }
        });
    }, 500);
});
@endif
</script>
@endif
@endsection
