@extends('layouts.app')
@section('content')
<div class="container py-5 fade-in-up" style="min-height: 80vh;">
    <h2 class="section-title mb-5 text-start" style="font-size: 2.5rem;">My Orders</h2>

    @php
        $hasOverdue = false;
        if(isset($orders)) {
            foreach($orders as $order) {
                foreach($order->payments as $payment) {
                    if ($payment->status === 'pending' && $payment->due_date && $payment->due_date->endOfDay()->isPast()) {
                        $hasOverdue = true;
                        break;
                    }
                }
                if ($hasOverdue) break;
            }
        }
    @endphp

    @if($hasOverdue)
        <div class="alert alert-danger d-flex align-items-center rounded-0 border-0 mb-4 shadow-sm" role="alert" style="border-left: 5px solid #dc3545 !important;">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Overdue Payment Detected</h6>
                <p class="mb-0 small">You have one or more installments that are past their due date. Please settle them immediately to avoid cancellation of your orders.</p>
            </div>
        </div>
    @endif

    <div class="card elegant-card border-0 p-4">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase" style="letter-spacing: 1px; border-bottom: 2px solid var(--text-main);">
                    <tr>
                        <th class="py-3 ps-4 border-0">Order ID</th>
                        <th class="py-3 border-0">Package</th>
                        <th class="py-3 border-0">Wedding Date</th>
                        <th class="py-3 border-0">Venue</th>
                        <th class="py-3 border-0">Amount Paid</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="py-3 pe-4 text-end border-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td class="ps-4 fw-bold text-muted">#WO-{{ $order->id }}</td>
                        <td class="fw-bold" style="font-family: var(--font-heading); font-size: 1.1rem;">{{ $order->package->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($order->wedding_date)->format('d M Y') }}<br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }}</small>
                        </td>
                        <td>{{ $order->venue_name ?? '-' }}</td>
                        <td>
                            <div class="d-flex align-items-center mb-1">
                                <div class="progress flex-grow-1" style="height: 6px; border-radius: 0;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $order->progress_percentage }}%;" aria-valuenow="{{ $order->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ms-2 small fw-bold">{{ $order->progress_percentage }}%</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">Rp {{ number_format($order->paid_amount, 0, ',', '.') }} / Rp {{ number_format($order->total_price, 0, ',', '.') }}</small>
                        </td>
                        <td>
                            @if($order->computed_status == 'pending')
                                <span class="badge bg-warning text-dark rounded-0 fw-normal">In Progress</span>
                            @elseif($order->computed_status == 'paid')
                                <span class="badge bg-success rounded-0 fw-normal">Paid</span>
                            @elseif($order->computed_status == 'completed')
                                <span class="badge bg-info rounded-0 fw-normal">Selesai</span>
                            @else
                                <span class="badge bg-danger rounded-0 fw-normal">Cancelled</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-elegant px-3 py-2">
                                View & Pay
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x text-muted display-1 mb-3"></i>
            <h4 class="text-muted" style="font-family: var(--font-heading);">You haven't placed any orders yet.</h4>
            <a href="{{ route('packages.index') }}" class="btn btn-elegant mt-4 px-4 py-2">Browse Packages</a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<!-- Midtrans Snap JS -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payWithMidtrans(snapToken, paymentType) {
        snap.pay(snapToken, {
            onSuccess: function(result){
                Swal.fire({
                    title: 'Verifying Payment...',
                    text: 'Please wait while we confirm your payment with the server.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                fetch('{{ route('midtrans.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: result.order_id
                    })
                }).then(response => response.json())
                .then(data => {
                    if(data.success) {
                        let msg = paymentType === 'dp'
                            ? 'Pembayaran DP Berhasil! Tim dari sanggar akan segera menghubungi Anda untuk info pelunasan.'
                            : 'Pembayaran Lunas Berhasil! Tim dari Sanggar Cahaya Minang akan segera menghubungi nomor yang tertera.';

                        Swal.fire('Berhasil!', msg, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Warning', 'Payment successful but failed to update status locally. Please contact admin.', 'warning').then(() => {
                            window.location.reload();
                        });
                    }
                }).catch(err => {
                    window.location.reload();
                });
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
    }

    // Auto-trigger payment if available from checkout redirect
    @if(session('auto_pay_token') && session('auto_pay_type'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                payWithMidtrans('{{ session("auto_pay_token") }}', '{{ session("auto_pay_type") }}');
            }, 500);
        });
    @endif
</script>
@endpush
@endsection
