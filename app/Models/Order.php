<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'promo_id',
        'wedding_date',
        'start_time',
        'end_time',
        'venue_name',
        'venue_address',
        'notes',
        'excluded_components',
        'vendor_notes',
        'component_discount',
        'discount_amount',
        'total_price',
        'paid_amount',
        'refund_amount',
        'refunded_at',
        'cancellation_notes',
        'payment_type',
        'status',
        'snap_token',
    ];

    protected $casts = [
        'wedding_date'         => 'date',
        'excluded_components'  => 'array',
        'vendor_notes'         => 'array',
        'refunded_at'          => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_price == 0) return 0;
        return min(100, round(($this->paid_amount / $this->total_price) * 100));
    }

    public function getComputedStatusAttribute()
    {
        // Jika pesanan lunas dan tanggal acara sudah lewat, dianggap selesai
        if ($this->status === 'paid' && $this->wedding_date && $this->wedding_date->endOfDay()->isPast()) {
            return 'completed';
        }
        // Jika pesanan masih pending dan tanggal acara sudah lewat, dianggap dibatalkan
        if ($this->status === 'pending' && $this->wedding_date && $this->wedding_date->endOfDay()->isPast()) {
            return 'cancelled';
        }
        return $this->status;
    }

    /**
     * Sinkronisasi & ubah status pesanan secara otomatis di database
     * untuk pesanan yang tanggal acaranya sudah lewat dari hari ini.
     */
    public static function autoUpdateExpiredStatuses()
    {
        $today = \Carbon\Carbon::today();

        // 1. Ubah pesanan 'paid' yang sudah terlewat tanggal acaranya menjadi 'completed'
        $paidExpired = self::where('status', 'paid')
            ->whereDate('wedding_date', '<', $today)
            ->get();

        foreach ($paidExpired as $order) {
            $order->update(['status' => 'completed']);
        }

        // 2. Ubah pesanan 'pending' yang sudah terlewat tanggal acaranya menjadi 'cancelled'
        $pendingExpired = self::where('status', 'pending')
            ->whereDate('wedding_date', '<', $today)
            ->get();

        foreach ($pendingExpired as $order) {
            $updateData = [
                'status' => 'cancelled',
                'cancellation_notes' => $order->paid_amount > 0 
                    ? 'Otomatis dibatalkan oleh sistem karena melewati tanggal acara tanpa penyelesaian pelunasan.' 
                    : 'Otomatis dibatalkan oleh sistem karena melewati tanggal acara tanpa pembayaran.'
            ];

            // Jika sudah ada pembayaran (misal DP), hitung refund otomatis 10%
            if ($order->paid_amount > 0 && $order->refund_amount == 0) {
                $updateData['refund_amount'] = (int) round($order->paid_amount * 0.10);
                $updateData['refunded_at'] = now();
            }

            $order->update($updateData);

            // Batalkan tagihan yang masih gantung
            $order->payments()->whereIn('status', ['pending', 'unpaid'])->update(['status' => 'failed']);
        }
    }
}