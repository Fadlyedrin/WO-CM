@extends('layouts.app')
@section('body-class', 'inner-page')
@section('content')

<div class="builder-page">
    {{-- Sticky Price Summary (Right Panel on Desktop) --}}
    <div class="builder-container">

        {{-- LEFT: Package Info + Component Checklist --}}
        <div class="builder-left">
            {{-- Hero Package Card --}}
            <div class="package-hero mb-5">
                <div class="row g-0 align-items-center">
                    <div class="col-md-5">
                        <img src="{{ $package->image_url ? Storage::url($package->image_url) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $package->name }}" class="hero-img">
                    </div>
                    <div class="col-md-7 p-4 p-lg-5">
                        <span class="package-badge">{{ $package->category->name ?? 'Paket Wedding' }}</span>
                        <h1 class="package-name mt-2">{{ $package->name }}</h1>
                        <p class="text-muted" style="font-size:0.95rem; line-height:1.7;">{{ Str::limit(strip_tags($package->description), 200) }}</p>
                        <div class="base-price-display">
                            <span class="label">Harga Dasar</span>
                            <span class="amount">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 1: Customize Components --}}
            <div class="builder-section">
                <div class="section-header">
                    <div class="step-badge">1</div>
                    <div>
                        <h4 class="mb-0">Pilih Layanan yang Anda Butuhkan</h4>
                        <p class="text-muted mb-0 small mt-1">Centang semua layanan yang ingin disediakan oleh kami. Hapus centang jika Anda sudah memiliki vendor sendiri untuk layanan tersebut — harga akan otomatis dikurangi.</p>
                    </div>
                </div>

                @if($package->components->count() > 0)
                <div class="components-grid" id="components-grid">
                    @foreach($package->components as $component)
                    <div class="component-card {{ !$component->is_optional ? 'component-required' : '' }}" 
                         data-price="{{ $component->price }}" data-id="{{ $component->id }}"
                         id="card-{{ $component->id }}">
                        <div class="component-check-area">
                            <input type="checkbox" 
                                   class="component-checkbox" 
                                   id="comp-{{ $component->id }}"
                                   data-price="{{ $component->price }}"
                                   data-id="{{ $component->id }}"
                                   {{ !$component->is_optional ? 'checked disabled' : 'checked' }}>
                            <label for="comp-{{ $component->id }}" class="{{ !$component->is_optional ? 'pe-none' : '' }}"></label>
                        </div>
                        <div class="component-icon-wrap">
                            <i class="bi {{ $component->icon }}"></i>
                        </div>
                        <div class="component-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="component-name mb-1">{{ $component->name }}</h6>
                                @if(!$component->is_optional)
                                    <span class="wajib-badge">Wajib</span>
                                @endif
                            </div>
                            @if($component->description)
                                <p class="component-desc">{{ strip_tags($component->description) }}</p>
                            @endif
                            <div class="component-price">Rp {{ number_format($component->price, 0, ',', '.') }}</div>
                        </div>
                        {{-- Vendor Note (shown when unchecked) --}}
                        <div class="vendor-note-wrap" id="note-{{ $component->id }}" style="display:none;">
                            <label class="vendor-note-label"><i class="bi bi-building me-1"></i> Nama vendor Anda (opsional):</label>
                            <input type="text" class="vendor-note-input" name="vendor_notes[{{ $component->id }}]" 
                                   placeholder="cth: Studio Cahaya Foto">
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-components text-center py-5 text-muted">
                    <i class="bi bi-grid-3x3-gap" style="font-size:3rem; opacity:0.3;"></i>
                    <p class="mt-3">Admin belum menambahkan komponen layanan untuk paket ini.<br>Anda bisa langsung melanjutkan pemesanan.</p>
                </div>
                @endif
            </div>

            {{-- Step 2: Event Details Form --}}
            <div class="builder-section mt-4">
                <div class="section-header">
                    <div class="step-badge">2</div>
                    <div>
                        <h4 class="mb-0">Detail Acara</h4>
                        <p class="text-muted mb-0 small mt-1">Lengkapi informasi acara pernikahan Anda.</p>
                    </div>
                </div>

                @auth
                <form id="builderForm" action="{{ route('checkout.summary', $package) }}" method="POST">
                    @csrf
                    {{-- Hidden fields for excluded components --}}
                    <div id="excluded-inputs"></div>
                    {{-- Hidden vendor notes --}}
                    <div id="vendor-note-inputs"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pernikahan <span class="text-danger">*</span></label>
                            <input type="date" name="wedding_date" class="form-control" required
                                   min="{{ \Carbon\Carbon::now()->addDays(10)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue_name" class="form-control" placeholder="cth: Gedung Serbaguna Baiturrahman" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Lengkap Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue_address" class="form-control" placeholder="cth: Jl. Veteran No. 12, Padang" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Ada tema khusus, permintaan spesial, atau hal lain yang ingin kami ketahui?"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <div class="payment-type-selector">
                                <label class="payment-option" id="opt-full">
                                    <input type="radio" name="payment_type" value="full" checked>
                                    <div class="payment-option-body">
                                        <i class="bi bi-shield-check text-success fs-4 mb-2"></i>
                                        <strong>Full Payment</strong>
                                        <span>Bayar lunas sekarang</span>
                                    </div>
                                </label>
                                <label class="payment-option" id="opt-dp">
                                    <input type="radio" name="payment_type" value="dp">
                                    <div class="payment-option-body">
                                        <i class="bi bi-calendar2-check text-primary fs-4 mb-2"></i>
                                        <strong>DP (Termin)</strong>
                                        <span>Bayar 30% sekarang</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
                @else
                <div class="alert-login text-center py-4">
                    <i class="bi bi-person-lock fs-2 mb-3 d-block text-muted"></i>
                    <p class="mb-3">Anda perlu masuk terlebih dahulu untuk melanjutkan pemesanan.</p>
                    <a href="{{ route('login') }}" class="btn btn-elegant me-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-custom">Daftar</a>
                </div>
                @endauth
            </div>
        </div>

        {{-- RIGHT: Sticky Price Summary --}}
        <div class="builder-right">
            <div class="price-summary-card" id="price-summary">
                <div class="summary-header">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Ringkasan Paket</h5>
                </div>
                <div class="summary-body">
                    <div class="summary-package-name">{{ $package->name }}</div>

                    {{-- Component list --}}
                    <div class="summary-components" id="summary-components">
                        @foreach($package->components as $component)
                        <div class="summary-item" id="summary-{{ $component->id }}">
                            <span class="summary-item-name">
                                <i class="bi bi-check2-circle text-success me-1"></i>
                                {{ $component->name }}
                            </span>
                            <span class="summary-item-price">Rp {{ number_format($component->price, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <hr class="my-3">

                    <div class="summary-row">
                        <span class="text-muted">Harga Dasar</span>
                        <span class="fw-medium">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row savings-row" id="savings-row" style="display:none;">
                        <span class="text-success"><i class="bi bi-scissors me-1"></i>Penghematan Vendor</span>
                        <span class="text-success fw-bold" id="savings-amount">- Rp 0</span>
                    </div>
                    <hr class="my-3">
                    <div class="summary-total">
                        <span>Total Anda</span>
                        <span id="total-price">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="savings-badge" id="savings-badge" style="display:none;">
                        🎉 Anda hemat <strong id="savings-text">Rp 0</strong>!
                    </div>

                    @auth
                    <button type="submit" form="builderForm" class="btn-book w-100 mt-4">
                        <i class="bi bi-calendar-check me-2"></i>Lanjut ke Konfirmasi
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn-book w-100 mt-4 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk untuk Memesan
                    </a>
                    @endauth

                    <p class="text-muted text-center mt-3" style="font-size:0.78rem;">
                        <i class="bi bi-shield-lock me-1"></i> Transaksi aman & terlindungi
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
/* ===== PAGE LAYOUT ===== */
.builder-page {
    background: #f7f5f1;
    min-height: 100vh;
    padding: 2rem 0 4rem;
}
.builder-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}
.builder-left { flex: 1; min-width: 0; }
.builder-right { width: 340px; flex-shrink: 0; position: sticky; top: 100px; }

/* ===== PACKAGE HERO ===== */
.package-hero {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.hero-img {
    width: 100%;
    height: 280px;
    object-fit: cover;
}
.package-badge {
    background: rgba(163, 140, 109, 0.12);
    color: var(--accent-color);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
}
.package-name {
    font-family: var(--font-heading);
    font-size: 2rem;
    color: var(--text-main);
    margin-top: 0.5rem;
}
.base-price-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1.5rem;
    background: #f7f5f1;
    border-radius: 10px;
    padding: 0.75rem 1rem;
}
.base-price-display .label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #999;
    font-weight: 500;
}
.base-price-display .amount {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    color: var(--text-main);
    font-weight: 700;
}

/* ===== BUILDER SECTIONS ===== */
.builder-section {
    background: #fff;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
.section-header {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f0ede8;
}
.step-badge {
    width: 36px;
    height: 36px;
    background: var(--accent-color);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

/* ===== COMPONENT CARDS ===== */
.components-grid {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
}
.component-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    border: 2px solid #eee;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #fff;
    position: relative;
    flex-wrap: wrap;
}
.component-card:hover { border-color: var(--accent-color); background: #fdfbf8; }
.component-card.is-checked { border-color: #4CAF50; background: #f0faf2; }
.component-card.is-excluded { border-color: #e8e8e8; background: #fafafa; opacity: 0.85; }
.component-card.component-required { background: #fffdf8; }

.component-check-area { flex-shrink: 0; padding-top: 2px; }
.component-checkbox { display: none; }
.component-checkbox + label {
    display: block;
    width: 22px;
    height: 22px;
    border: 2px solid #ccc;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    background: #fff;
}
.component-checkbox:checked + label {
    background: #4CAF50;
    border-color: #4CAF50;
}
.component-checkbox:checked + label::after {
    content: '';
    position: absolute;
    left: 5px; top: 2px;
    width: 8px; height: 13px;
    border: 2px solid #fff;
    border-top: none; border-left: none;
    transform: rotate(45deg);
}
.component-checkbox:disabled + label { 
    background: #4CAF50; border-color: #4CAF50; cursor: default;
}
.component-checkbox:disabled + label::after {
    content: ''; position: absolute;
    left: 5px; top: 2px;
    width: 8px; height: 13px;
    border: 2px solid #fff;
    border-top: none; border-left: none;
    transform: rotate(45deg);
}

.component-icon-wrap {
    width: 44px; height: 44px;
    background: rgba(163,140,109,0.1);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    color: var(--accent-color);
    flex-shrink: 0;
}
.component-body { flex: 1; }
.component-name { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
.component-desc { font-size: 0.83rem; color: #888; margin-bottom: 0.25rem; line-height: 1.5; }
.component-price { font-size: 0.92rem; font-weight: 700; color: var(--accent-color); }
.wajib-badge {
    background: rgba(255,152,0,0.12); color: #e65100;
    font-size: 0.7rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; letter-spacing: 0.5px;
    flex-shrink: 0;
}

/* Vendor Note */
.vendor-note-wrap {
    width: 100%;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px dashed #ddd;
    flex-basis: 100%;
}
.vendor-note-label { font-size: 0.8rem; color: #888; font-weight: 500; display: block; margin-bottom: 0.4rem; }
.vendor-note-input {
    width: 100%;
    border: 1px solid #e0ddd8;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    background: #fff;
    color: var(--text-main);
    outline: none;
    transition: border-color 0.2s;
}
.vendor-note-input:focus { border-color: var(--accent-color); }

/* ===== PAYMENT SELECTOR ===== */
.payment-type-selector {
    display: flex; gap: 1rem;
}
.payment-option {
    flex: 1;
    border: 2px solid #eee;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
}
.payment-option input[type=radio] { display: none; }
.payment-option-body {
    display: flex; flex-direction: column;
    align-items: center; text-align: center;
    padding: 1.25rem;
}
.payment-option-body strong { display: block; font-size: 0.95rem; margin-bottom: 0.25rem; color: var(--text-main); }
.payment-option-body span { font-size: 0.8rem; color: #888; }
.payment-option:has(input:checked) {
    border-color: var(--accent-color);
    background: rgba(163,140,109,0.06);
}

/* ===== PRICE SUMMARY CARD ===== */
.price-summary-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.1);
}
.summary-header {
    background: linear-gradient(135deg, #2a2a2a, #444);
    color: #fff;
    padding: 1.25rem 1.5rem;
}
.summary-body { padding: 1.5rem; }
.summary-package-name {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    color: var(--text-main);
    font-weight: 700;
    margin-bottom: 1rem;
}
.summary-components { display: flex; flex-direction: column; gap: 0.5rem; }
.summary-item {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 0.83rem;
    transition: all 0.25s;
}
.summary-item.is-excluded-summary {
    opacity: 0.35; text-decoration: line-through;
}
.summary-item-name { color: #555; }
.summary-item-price { font-weight: 600; color: #777; white-space: nowrap; margin-left: 0.5rem; }
.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 0.88rem; margin-bottom: 0.5rem;
}
.savings-row { font-size: 0.88rem; }
.summary-total {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 1.2rem; font-weight: 800; color: var(--text-main);
    font-family: var(--font-heading);
}
.savings-badge {
    background: linear-gradient(135deg, #e8f5e9, #f1f8f1);
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    text-align: center;
    padding: 0.6rem 1rem;
    margin-top: 0.75rem;
    font-size: 0.85rem;
    color: #2e7d32;
    animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes popIn {
    from { transform: scale(0.85); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.btn-book {
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #2a2a2a, #444);
    color: #fff !important;
    border: none;
    border-radius: 12px;
    padding: 1rem;
    font-size: 0.95rem;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}
.btn-book:hover {
    background: linear-gradient(135deg, var(--accent-color), #8b7355);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 992px) {
    .builder-container { flex-direction: column; }
    .builder-right { width: 100%; position: static; }
    .hero-img { height: 220px; }
}
@media (max-width: 576px) {
    .payment-type-selector { flex-direction: column; }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const basePrice = {{ $package->price }};
    const checkboxes = document.querySelectorAll('.component-checkbox:not(:disabled)');
    const totalPriceEl = document.getElementById('total-price');
    const savingsAmountEl = document.getElementById('savings-amount');
    const savingsBadgeEl = document.getElementById('savings-badge');
    const savingsTextEl = document.getElementById('savings-text');
    const savingsRowEl = document.getElementById('savings-row');
    const excludedInputsEl = document.getElementById('excluded-inputs');
    const form = document.getElementById('builderForm');

    function formatRp(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function updateSummary() {
        let totalDiscount = 0;
        const excludedIds = [];

        checkboxes.forEach(cb => {
            const id = cb.dataset.id;
            const price = parseInt(cb.dataset.price);
            const summaryItem = document.getElementById('summary-' + id);
            const card = document.getElementById('card-' + id);
            const vendorNote = document.getElementById('note-' + id);

            if (cb.checked) {
                card.classList.remove('is-excluded');
                card.classList.add('is-checked');
                if (summaryItem) summaryItem.classList.remove('is-excluded-summary');
                if (vendorNote) vendorNote.style.display = 'none';
            } else {
                totalDiscount += price;
                excludedIds.push(id);
                card.classList.add('is-excluded');
                card.classList.remove('is-checked');
                if (summaryItem) summaryItem.classList.add('is-excluded-summary');
                if (vendorNote) vendorNote.style.display = 'block';
            }
        });

        // Update hidden excluded_components inputs
        excludedInputsEl.innerHTML = '';
        excludedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'excluded_components[]';
            input.value = id;
            excludedInputsEl.appendChild(input);
        });

        const newTotal = basePrice - totalDiscount;
        totalPriceEl.textContent = formatRp(newTotal);

        if (totalDiscount > 0) {
            savingsAmountEl.textContent = '- ' + formatRp(totalDiscount);
            savingsTextEl.textContent = formatRp(totalDiscount);
            savingsRowEl.style.display = 'flex';
            savingsBadgeEl.style.display = 'block';
        } else {
            savingsRowEl.style.display = 'none';
            savingsBadgeEl.style.display = 'none';
        }
    }

    // Attach events
    checkboxes.forEach(cb => {
        const card = document.getElementById('card-' + cb.dataset.id);
        
        // Toggle on card click
        card.addEventListener('click', function (e) {
            if (e.target.closest('.vendor-note-wrap')) return; // Don't toggle when clicking vendor input
            if (e.target.closest('.component-check-area label')) return; // Let label handle it
            if (e.target.closest('.component-check-area input')) return;
            if (!cb.disabled) {
                cb.checked = !cb.checked;
                updateSummary();
            }
        });

        cb.addEventListener('change', updateSummary);
        // Set initial state
        if (cb.checked) {
            card.classList.add('is-checked');
        }
    });

    // Payment option visual feedback
    document.querySelectorAll('.payment-option input[type=radio]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
            radio.closest('.payment-option').classList.add('selected');
        });
    });

    // Initial state
    updateSummary();
    // Fix: mark all default checked as is-checked
    checkboxes.forEach(cb => {
        const card = document.getElementById('card-' + cb.dataset.id);
        if (cb.checked) card.classList.add('is-checked');
    });
});
</script>
@endpush
