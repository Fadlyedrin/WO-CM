@extends('layouts.app')
@section('styles')
<style>
@media (max-width: 576px) {
    .card-body.p-4.p-md-5 { padding: 1.25rem !important; }
    .table td, .table th { font-size: 0.82rem; padding: 0.5rem 0.25rem; }
    .input-group { flex-direction: column; gap: 0.5rem; }
    .input-group .btn { border-radius: 0 !important; width: 100%; }
    #pay-now-display { font-size: 1.6rem !important; }
}
</style>
@endsection
@section('content')
<div class="bg-light py-4 py-md-5">
    <div class="container py-4">
        <h2 class="section-title mb-5">Checkout Summary</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card elegant-card border-0 p-4 p-md-5">
                    <h4 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Order Details</h4>

                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3 w-50">Paket Dipilih</th>
                                    <td class="fw-bold text-end py-3">{{ $package->name }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Kategori</th>
                                    <td class="text-end py-3">{{ $package->category->name }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Tanggal Pernikahan</th>
                                    <td class="text-end py-3 fw-bold text-primary">{{ \Carbon\Carbon::parse($wedding_date)->translatedFormat('l, d F Y') }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Waktu Acara</th>
                                    <td class="text-end py-3">{{ \Carbon\Carbon::parse($start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($end_time)->format('H:i') }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Nama Venue</th>
                                    <td class="text-end py-3">{{ $venue_name }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Alamat Venue</th>
                                    <td class="text-end py-3">{{ $venue_address }}</td>
                                </tr>
                                @if($notes)
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Catatan</th>
                                    <td class="text-end py-3 fst-italic">{{ $notes }}</td>
                                </tr>
                                @endif
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Metode Pembayaran</th>
                                    <td class="text-end py-3 text-uppercase">
                                        @if($payment_type == 'dp')
                                            <span class="badge bg-warning text-dark px-3 py-2">Down Payment (30%)</span>
                                        @else
                                            <span class="badge bg-success px-3 py-2">Full Payment</span>
                                        @endif
                                    </td>
                                </tr>
                                @if(isset($baseFee) && $baseFee > 0)
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3">Biaya Dasar Paket</th>
                                    <td class="text-end py-3">Rp {{ number_format($baseFee, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if(!empty($excludedComponents) && $excludedComponents->count() > 0)
                                <tr class="border-bottom">
                                    <th class="text-muted fw-normal py-3" style="font-size:0.82rem;">Layanan Ditiadakan</th>
                                    <td class="text-end py-3">
                                        @foreach($excludedComponents as $ec)
                                        <span class="badge bg-light text-muted border d-inline-block mb-1" style="font-size:0.75rem;">{{ $ec->name }}</span><br>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                <tr class="border-bottom d-none" id="discount-row">
                                    <th class="text-success fw-normal py-3">Diskon <span id="promo-code-display" class="badge bg-success ms-2"></span></th>
                                    <td class="text-end py-3 text-success fw-bold" id="discount-amount-display">- Rp 0</td>
                                </tr>
                                <tr>
                                    <th class="py-4 fs-5" style="font-family: 'Playfair Display', serif;">Total Harga</th>
                                    <td class="text-end py-4 fs-5 fw-bold" style="font-family: 'Playfair Display', serif;" id="final-total-display">Rp {{ number_format($totalPrice, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase">Have a promo code?</label>
                        <div class="input-group">
                            <input type="text" id="promo_code_input" class="form-control" placeholder="Enter code here" style="text-transform: uppercase;">
                            <button class="btn btn-outline-primary" type="button" id="btn-apply-promo">Apply</button>
                        </div>
                        <div id="promo-message" class="mt-2 small"></div>
                    </div>

                    <div class="bg-light p-4 rounded mb-4 text-center border">
                        <span class="text-muted small text-uppercase d-block mb-2" style="letter-spacing: 1px;">Amount to Pay Now</span>
                        <h2 class="text-primary mb-0" style="font-family: 'Playfair Display', serif;" id="pay-now-display">Rp {{ number_format($paidAmount, 0, ',', '.') }}</h2>
                    </div>

                    <form action="{{ route('checkout.process', $package) }}" method="POST">
                        @csrf
                        <input type="hidden" name="wedding_date" value="{{ $wedding_date }}">
                        <input type="hidden" name="start_time" value="{{ $start_time }}">
                        <input type="hidden" name="end_time" value="{{ $end_time }}">
                        <input type="hidden" name="venue_name" value="{{ $venue_name }}">
                        <input type="hidden" name="venue_address" value="{{ $venue_address }}">
                        <input type="hidden" name="notes" value="{{ $notes }}">
                        <input type="hidden" name="payment_type" value="{{ $payment_type }}">
                        <input type="hidden" name="promo_code" id="hidden_promo_code" value="">
                        @if(!empty($excludedIds))
                            @foreach($excludedIds as $exId)
                                <input type="hidden" name="excluded_components[]" value="{{ $exId }}">
                            @endforeach
                        @endif
                        @if(!empty($vendor_notes))
                            @foreach($vendor_notes as $compId => $note)
                                <input type="hidden" name="vendor_notes[{{ $compId }}]" value="{{ $note }}">
                            @endforeach
                        @endif
                        <button type="submit" class="btn btn-primary btn-elegant w-100 py-3 fs-5">Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-2"></i></button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('packages.show', $package) }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Package</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.getElementById('btn-apply-promo');
    const promoInput = document.getElementById('promo_code_input');
    const messageBox = document.getElementById('promo-message');

    const basePrice = {{ $totalPrice }};
    const paymentType = '{{ $payment_type }}';

    applyBtn.addEventListener('click', function() {
        const code = promoInput.value.trim().toUpperCase();
        if(!code) return;

        applyBtn.disabled = true;
        applyBtn.innerHTML = 'Applying...';

        fetch('/api/promo/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code, package_price: basePrice })
        })
        .then(r => r.json())
        .then(data => {
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Apply';

            if(data.valid) {
                messageBox.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> ${data.message}</span>`;

                document.getElementById('discount-row').classList.remove('d-none');
                document.getElementById('promo-code-display').innerText = code;
                document.getElementById('discount-amount-display').innerText = '- ' + data.discount_formatted;

                const finalTotal = basePrice - data.discount_amount;
                document.getElementById('final-total-display').innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');

                let payNow = finalTotal;
                if(paymentType === 'dp') {
                    payNow = finalTotal * 0.3;
                }
                document.getElementById('pay-now-display').innerText = 'Rp ' + payNow.toLocaleString('id-ID');

                document.getElementById('hidden_promo_code').value = code;
            } else {
                messageBox.innerHTML = `<span class="text-danger"><i class="bi bi-x-circle"></i> ${data.message}</span>`;
                resetPromoUI();
            }
        })
        .catch(err => {
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Apply';
            console.error(err);
        });
    });

    function resetPromoUI() {
        document.getElementById('discount-row').classList.add('d-none');
        document.getElementById('final-total-display').innerText = 'Rp ' + basePrice.toLocaleString('id-ID');

        let payNow = basePrice;
        if(paymentType === 'dp') {
            payNow = basePrice * 0.3;
        }
        document.getElementById('pay-now-display').innerText = 'Rp ' + payNow.toLocaleString('id-ID');
        document.getElementById('hidden_promo_code').value = '';
    }
});
</script>
@endsection
