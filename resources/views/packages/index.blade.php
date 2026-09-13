@extends('layouts.app')
@section('content')
<style>
    .pkg-card-img { height: 320px; overflow: hidden; }
    @media (max-width: 768px) { .pkg-card-img { height: 220px; } }
    @media (max-width: 480px) { .pkg-card-img { height: 200px; } }
    .pkg-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .pkg-card-img:hover img { transform: scale(1.05); }
    .filter-link { font-size: 0.78rem; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; padding-bottom: 4px; }
</style>

<div class="container text-center pt-5 pb-4">
    <h1 class="display-4 mb-3" style="font-family: var(--font-heading); color: var(--text-main);">Koleksi Paket Kami</h1>
    <p class="lead text-muted fw-light mx-auto mb-5" style="max-width: 600px;">Temukan paket pernikahan impian Anda yang dirancang dengan penuh cinta dan ketelitian.</p>

    <div class="d-flex justify-content-center flex-wrap gap-3 mb-5 pb-3">
        <a href="{{ route('packages.index') }}" class="filter-link {{ !request('category') ? 'border-bottom border-dark border-2 text-dark fw-bold' : 'text-muted' }}">Semua</a>
        @foreach($categories as $cat)
        <a href="{{ route('packages.index', ['category' => $cat->slug]) }}" class="filter-link {{ request('category')==$cat->slug ? 'border-bottom border-dark border-2 text-dark fw-bold' : 'text-muted' }}">{{ $cat->name }}</a>
        @endforeach
    </div>
</div>

<div class="container pb-5 mb-5">
    <div class="row g-4 justify-content-center">
        @foreach($packages as $pkg)
        <div class="col-6 col-md-6 col-lg-4">
            <a href="{{ route('packages.show', $pkg) }}" class="text-decoration-none text-dark">
                <div class="card elegant-card h-100 bg-white" style="transition: transform 0.3s ease; overflow:hidden;">
                    @if($pkg->image_url)
                    <div class="pkg-card-img">
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($pkg->image_url) }}" alt="{{ $pkg->name }}">
                    </div>
                    @endif
                    <div class="card-body p-3 p-md-4 text-center">
                        <span class="text-uppercase text-muted d-block mb-2" style="font-size:0.68rem; letter-spacing: 2px;">{{ $pkg->category->name }}</span>
                        <h4 class="card-title mb-2" style="font-family: var(--font-heading); font-size: clamp(1rem,3vw,1.4rem);">{{ $pkg->name }}</h4>
                        <p class="mb-0" style="color: var(--accent-color); font-size: clamp(0.9rem,2.5vw,1.1rem); font-family: var(--font-heading);">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                        <div class="mt-3">
                            <span class="text-uppercase text-muted" style="font-size:0.72rem; letter-spacing: 1px; border-bottom: 1px solid #ccc; padding-bottom: 3px;">Lihat Detail</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection