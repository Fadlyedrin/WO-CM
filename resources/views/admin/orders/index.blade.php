@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Order Management</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">ID / Date</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Customer</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Package</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Wedding & Venue</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Payment Progress</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Status</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($orders as $order)
                    @php
                        $isOverdue = false;
                        foreach($order->payments as $payment) {
                            if ($payment->status === 'pending' && $payment->due_date && $payment->due_date->endOfDay()->isPast()) {
                                $isOverdue = true;
                                break;
                            }
                        }
                    @endphp
                    <tr @if($isOverdue) style="background-color: rgba(220, 53, 69, 0.05); border-left: 3px solid #dc3545;" @endif>
                        <td class="ps-4 py-3">
                            <strong class="text-dark">#WO-{{ $order->id }}</strong><br>
                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="py-3">
                            <strong class="text-dark">{{ $order->user->name }}</strong><br>
                            <small class="text-muted"><a href="mailto:{{ $order->user->email }}" class="text-decoration-none">{{ $order->user->email }}</a></small><br>
                            <small class="text-muted"><i class="bi bi-telephone"></i> {{ $order->user->phone ?? '-' }}</small>
                        </td>
                        <td class="py-3 text-dark fw-medium">{{ $order->package->name }}</td>
                        <td class="py-3">
                            <strong class="text-primary">{{ \Carbon\Carbon::parse($order->wedding_date)->format('d M Y') }}</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }}</small><br>
                            <small class="text-muted">{{ $order->venue_name ?? '-' }}</small>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center mb-1">
                                <div class="progress flex-grow-1 bg-light" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $order->progress_percentage }}%; border-radius: 10px;" aria-valuenow="{{ $order->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ms-2 small fw-bold">{{ $order->progress_percentage }}%</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">Rp {{ number_format($order->paid_amount, 0, ',', '.') }} / Rp {{ number_format($order->total_price, 0, ',', '.') }}</small>
                        </td>
                        <td class="py-3">
                            @if($order->computed_status == 'pending')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-medium">In Progress</span>
                            @elseif($order->computed_status == 'paid')
                                <span class="badge bg-success rounded-pill px-3 py-2 fw-medium">Lunas</span>
                            @elseif($order->computed_status == 'completed')
                                <span class="badge bg-info rounded-pill px-3 py-2 fw-medium">Selesai</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium">Dibatalkan</span>
                                @if($order->refund_amount > 0)
                                <br><span class="badge bg-warning text-dark rounded-pill mt-1" style="font-size:0.68rem;">
                                    💰 Refund: Rp {{ number_format($order->refund_amount, 0, ',', '.') }}
                                </span>
                                @endif
                            @endif
                            
                            @if($isOverdue)
                                <br><span class="badge bg-danger rounded-pill shadow-sm mt-1" style="animation: pulse 2s infinite;">OVERDUE</span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light text-primary fw-medium" title="View Detail/Invoice">
                                <i class="bi bi-file-earmark-text"></i> View
                            </a>
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline-flex gap-1 mt-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" style="width: auto; border-radius: 8px;"
                                    data-original="{{ $order->status }}"
                                    data-paid="{{ $order->paid_amount }}"
                                    onchange="confirmStatusChange(this)">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <input type="hidden" name="cancellation_notes" class="cancellation-notes-input" value="">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
async function confirmStatusChange(selectElement) {
    const originalValue = selectElement.getAttribute('data-original');
    const newValue = selectElement.value;
    const paidAmount = parseInt(selectElement.getAttribute('data-paid') || 0);
    if (newValue === originalValue) return;

    if (newValue === 'cancelled' && paidAmount > 0) {
        const refundAmount = Math.round(paidAmount * 0.10);
        const formatted = new Intl.NumberFormat('id-ID').format(refundAmount);
        const paidFormatted = new Intl.NumberFormat('id-ID').format(paidAmount);

        const { value: notes, isConfirmed } = await Swal.fire({
            title: '⚠️ Konfirmasi Pembatalan',
            html: `
                <div class="text-start">
                    <p>Klien sudah membayar <strong>Rp ${paidFormatted}</strong>.</p>
                    <div class="alert alert-warning py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Sesuai kebijakan kontrak, <strong>refund 10%</strong> dari dana yang sudah dibayar =
                        <strong class="text-success">Rp ${formatted}</strong> akan dikembalikan ke klien.
                    </div>
                    <label class="form-label fw-semibold">Catatan Pembatalan (opsional):</label>
                    <textarea id="cancel-notes" class="form-control" rows="2" placeholder="Alasan pembatalan..."></textarea>
                </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan & Hitung Refund',
            cancelButtonText: 'Tidak',
            preConfirm: () => document.getElementById('cancel-notes').value
        });

        if (isConfirmed) {
            selectElement.closest('form').querySelector('.cancellation-notes-input').value = notes || '';
            selectElement.form.submit();
        } else {
            selectElement.value = originalValue;
        }
    } else {
        const result = await Swal.fire({
            title: 'Ubah Status?',
            text: 'Anda akan mengubah status pesanan secara manual.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        });
        if (result.isConfirmed) {
            selectElement.form.submit();
        } else {
            selectElement.value = originalValue;
        }
    }
}
</script>
@endsection