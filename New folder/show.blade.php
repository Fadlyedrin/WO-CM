@extends('layouts.app')
@section('content')

<style>
/* ===== PACKAGE SHOW — RESPONSIVE ===== */
.pkg-show-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.25rem 4rem;
}

/* Image area */
.pkg-img-wrap img {
    width: 100%;
    object-fit: cover;
    border-radius: 12px;
    max-height: 520px;
}
.pkg-thumb-row {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    overflow-x: auto;
    padding-bottom: 0.25rem;
}
.pkg-thumb-row img {
    height: 72px;
    width: 96px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.2s;
    flex-shrink: 0;
}
.pkg-thumb-row img:hover { border-color: var(--accent-color); }

/* Detail column */
.pkg-category-label {
    font-size: 0.72rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #999;
}
.pkg-title {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 6vw, 3.2rem);
    color: var(--text-main);
    line-height: 1.15;
    margin: 0.5rem 0 1rem;
}
.pkg-price {
    font-family: var(--font-heading);
    font-size: clamp(1.3rem, 4vw, 1.8rem);
    color: var(--accent-color);
    margin-bottom: 1.5rem;
}
.pkg-desc-content {
    font-family: var(--font-body);
    color: #666; font-size: 0.95rem; line-height: 1.8;
}
.pkg-desc-content p, .pkg-desc-content span, .pkg-desc-content div, .pkg-desc-content li {
    font-family: inherit !important; color: inherit !important;
    font-size: inherit !important; line-height: inherit !important;
    background: transparent !important;
}
.pkg-desc-content strong, .pkg-desc-content b { font-weight: 600 !important; color: var(--text-main) !important; }
.pkg-desc-content ul { list-style-type: disc; padding-left: 20px; }
.pkg-desc-content ol { list-style-type: decimal; padding-left: 20px; }
.pkg-desc-content li { margin-bottom: 8px; }

/* CTA Banner */
.vendor-cta {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #fdfbf8, #f7f3ed);
    border: 1px solid rgba(163,140,109,0.2);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.vendor-cta-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    background: rgba(163,140,109,0.12);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: var(--accent-color);
}
.vendor-cta-text { flex: 1; min-width: 160px; }
.vendor-cta-text strong { display: block; color: var(--text-main); font-size: 0.95rem; }
.vendor-cta-text span { font-size: 0.82rem; color: #888; }
.vendor-cta .btn-elegant {
    font-size: 0.82rem; padding: 0.6rem 1.1rem;
    white-space: nowrap; flex-shrink: 0;
}

/* Booking Form */
.booking-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.75rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
}
.form-control-minimal {
    border: none;
    border-bottom: 1px solid rgba(0,0,0,0.18);
    border-radius: 0;
    padding: 0.65rem 0;
    background: transparent;
    font-size: 0.9rem;
    color: var(--text-main);
}
.form-control-minimal:focus {
    box-shadow: none;
    border-bottom-color: var(--text-main);
    background: transparent;
}

/* Responsive Grid */
.pkg-show-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: start;
}
@media (max-width: 768px) {
    .pkg-show-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .pkg-img-wrap img { max-height: 280px; border-radius: 10px; }
    .booking-card { padding: 1.25rem; }
    .vendor-cta { flex-direction: row; align-items: flex-start; }
}
@media (max-width: 480px) {
    .pkg-show-wrap { padding: 1rem 1rem 3rem; }
    .vendor-cta { flex-direction: column; align-items: stretch; }
    .vendor-cta .btn-elegant { width: 100%; text-align: center; }
}
</style>

<div class="pkg-show-wrap fade-in-up">
    <div class="pkg-show-grid">

        {{-- LEFT: Images --}}
        <div class="pkg-img-wrap">
            @if($package->images->count() > 0)
                <div id="packageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="border-radius:12px; overflow:hidden;">
                        @foreach($package->images as $index => $img)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <a href="{{ Storage::url($img->image_path) }}" class="glightbox" data-gallery="pkg">
                                <img src="{{ Storage::url($img->image_path) }}" class="d-block w-100" style="object-fit:cover; height:clamp(240px,45vw,520px);">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @if($package->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
                @if($package->images->count() > 1)
                <div class="pkg-thumb-row mt-3">
                    @foreach($package->images as $index => $img)
                    <img src="{{ Storage::url($img->image_path) }}"
                         onclick="bootstrap.Carousel.getInstance(document.getElementById('packageCarousel')).to({{ $index }})"
                         alt="Thumbnail {{ $index+1 }}">
                    @endforeach
                </div>
                @endif
            @elseif($package->image_url)
                <a href="{{ \App\Helpers\ImageHelper::getUrl($package->image_url) }}" class="glightbox" data-gallery="pkg">
                    <img src="{{ \App\Helpers\ImageHelper::getUrl($package->image_url) }}" style="border-radius:12px; width:100%; object-fit:cover; max-height:520px;">
                </a>
            @endif
        </div>

        {{-- RIGHT: Info + Booking --}}
        <div>
            <span class="pkg-category-label">{{ $package->category->name }}</span>
            <h1 class="pkg-title">{{ $package->name }}</h1>
            <p class="pkg-price">Rp {{ number_format($package->price, 0, ',', '.') }}</p>

            <div class="pkg-desc-content mb-4">
                {!! $package->description !!}
            </div>

            {{-- Accordion Detail Komponen --}}
            @if($package->components->count() > 0)
            <h5 class="fw-bold mb-3 mt-4" style="font-family: var(--font-heading); font-size: 1.4rem;">Detail Spesifikasi Layanan</h5>
            <div class="accordion mb-4" id="packageComponentsAccordion">
                @foreach($package->components as $index => $comp)
                @if(!empty($comp->description))
                <div class="accordion-item border-0 border-bottom mb-2" style="background: transparent;">
                    <h2 class="accordion-header" id="headingComp{{ $comp->id }}">
                        <button class="accordion-button collapsed px-0 shadow-none fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComp{{ $comp->id }}" style="background: transparent; color: var(--text-main); font-size: 1.05rem;">
                            <i class="bi {{ $comp->icon }} me-3" style="color: var(--accent-color); font-size: 1.2rem;"></i> {{ $comp->name }}
                        </button>
                    </h2>
                    <div id="collapseComp{{ $comp->id }}" class="accordion-collapse collapse" data-bs-parent="#packageComponentsAccordion">
                        <div class="accordion-body px-0 pt-2 pb-3 pkg-desc-content" style="font-size: 0.95rem; border-top: 1px dashed rgba(0,0,0,0.1); margin-top: 5px;">
                            {!! $comp->description !!}
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif

            @auth
                {{-- Vendor CTA Banner --}}
                @if($package->components->count() > 0)
                <div class="vendor-cta">
                    <div class="vendor-cta-icon">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div class="vendor-cta-text">
                        <strong>Punya vendor sendiri?</strong>
                        <span>Kurangi layanan & hemat lebih banyak!</span>
                    </div>
                    <a href="{{ route('packages.builder', $package) }}" class="btn btn-elegant">
                        <i class="bi bi-tools me-1"></i> Sesuaikan Paket
                    </a>
                </div>
                @endif

                {{-- Booking Form --}}
                <div class="booking-card">
                    <form action="{{ route('checkout.summary', $package) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Informasi Kontak</label>
                            <input type="text" class="form-control form-control-minimal fw-bold"
                                   value="{{ Auth::user()->name }} ({{ Auth::user()->phone ?? 'No Phone' }})" readonly disabled style="opacity:0.8;">
                            <small class="text-muted" style="font-size:0.75rem;">Diambil otomatis dari profil Anda.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pernikahan <span class="text-danger">*</span></label>
                            <input type="text" id="wedding_date" name="wedding_date"
                                   class="form-control form-control-minimal"
                                   placeholder="Pilih Tanggal Acara..." required>
                            <small class="text-muted" style="font-size:0.75rem;">Min. 10 hari dari sekarang. Tanggal abu-abu = sudah penuh.</small>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control form-control-minimal" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control form-control-minimal" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue_name" class="form-control form-control-minimal"
                                   placeholder="cth: Gedung Serbaguna Senayan" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap Venue <span class="text-danger">*</span></label>
                            <textarea name="venue_address" class="form-control form-control-minimal"
                                      rows="2" placeholder="Masukkan alamat lengkap lokasi acara..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan <small class="text-muted fw-normal">(Opsional)</small></label>
                            <textarea name="notes" class="form-control form-control-minimal"
                                      rows="2" placeholder="Permintaan khusus atau catatan lain..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_type" class="form-select form-control-minimal" required>
                                <option value="full">Full Payment (Rp {{ number_format($package->price, 0, ',', '.') }})</option>
                                @if($package->price >= 5000000)
                                <option value="dp">Down Payment 30% (Rp {{ number_format($package->price * 0.3, 0, ',', '.') }})</option>
                                @endif
                            </select>
                            @if($package->price < 5000000)
                                <small class="text-danger mt-1 d-block">* Paket ini wajib menggunakan Full Payment.</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-elegant w-100 py-3">
                            Lanjut ke Checkout <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="booking-card text-center py-4">
                    <p class="mb-3" style="font-family:var(--font-heading); font-size:1.4rem; color:var(--text-main);">
                        Siap memulai perjalanan Anda?
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-elegant px-4 py-3">Masuk untuk Memesan</a>
                </div>
            @endauth
        </div>

    </div>{{-- .pkg-show-grid --}}
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/booked-dates')
        .then(r => r.json())
        .then(bookedDates => {
            flatpickr("#wedding_date", {
                minDate: new Date().fp_incr(10),
                disable: bookedDates,
                dateFormat: "Y-m-d",
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                    if (bookedDates.includes(dateStr)) {
                        dayElem.addEventListener('click', function(e) {
                            e.stopPropagation(); e.preventDefault();
                            Swal.fire({
                                title: 'Tanggal Penuh',
                                text: 'Kuota acara pada tanggal ini sudah penuh (maks. 2 acara). Silakan pilih tanggal lain.',
                                icon: 'warning',
                                confirmButtonColor: '#a38c6d'
                            });
                        });
                    }
                }
            });
        });
});
</script>
@endsection