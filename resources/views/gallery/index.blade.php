@extends('layouts.app')
@section('content')
<div class="container text-center pt-5 pb-4">
    <h1 class="display-4 mb-3" style="font-family: var(--font-heading); color: var(--text-main);">Our Portfolio</h1>
    <p class="lead text-muted fw-light mx-auto" style="max-width: 600px;">A glimpse into the magical moments we've helped create.</p>
</div>

<div class="container pb-5 mb-5">
    @if($galleries->isEmpty())
        <p class="text-center text-muted py-5">The gallery is currently empty. Check back soon!</p>
    @else
        <div class="row g-4">
            @foreach($galleries as $img)
            <div class="col-12 col-md-6 col-lg-4 fade-in-up">
                <div class="portfolio-item position-relative overflow-hidden shadow-sm">
                    <a href="{{ Storage::url($img->image_path) }}" class="glightbox d-block w-100 h-100 position-relative" data-gallery="portfolio" data-title="{{ $img->title }}" data-description="{{ $img->description }}">
                        <img src="{{ Storage::url($img->image_path) }}" alt="{{ $img->title }}" class="portfolio-img">
                        <div class="portfolio-overlay d-flex align-items-center justify-content-center">
                            <div class="zoom-icon rounded-circle d-flex align-items-center justify-content-center shadow">
                                <i class="bi bi-zoom-in text-white fs-4"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination Links --}}
        @if($galleries->hasPages())
        <div class="d-flex justify-content-center mt-5 pt-3">
            {{ $galleries->links() }}
        </div>
        @endif
    @endif
</div>

<style>
    /* Regular clean 3-column portfolio styling */
    .portfolio-item {
        border-radius: 6px;
        aspect-ratio: 1 / 1; /* Square crop for neat grid alignment */
        background-color: #eee;
        cursor: pointer;
    }
    .portfolio-img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .portfolio-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(42, 42, 42, 0.35);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .zoom-icon {
        width: 54px;
        height: 54px;
        background-color: var(--accent-color, #a38c6d);
        transform: scale(0.8);
        transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .portfolio-item:hover .portfolio-img {
        transform: scale(1.08);
    }
    .portfolio-item:hover .portfolio-overlay {
        opacity: 1;
    }
    .portfolio-item:hover .zoom-icon {
        transform: scale(1);
    }

    /* Elegant Pagination Styling */
    .pagination .page-link {
        color: var(--text-main);
        background-color: #fff;
        border: 1px solid #e2dfd9;
        padding: 0.65rem 1.25rem;
        margin: 0 0.2rem;
        border-radius: 4px;
        font-size: 0.9rem;
        font-family: var(--font-body);
        transition: all 0.3s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--accent-color, #a38c6d);
        border-color: var(--accent-color, #a38c6d);
        color: #fff;
        font-weight: 600;
    }
    .pagination .page-link:hover {
        background-color: #f0ede6;
        color: var(--text-main);
        border-color: #d1cbbd;
    }
    .pagination .page-item.disabled .page-link {
        color: #b5b1a8;
        background-color: #faf9f6;
        border-color: #edeae3;
    }

    /* Responsive adjusts */
    @media (max-width: 576px) {
        .portfolio-item {
            aspect-ratio: 4 / 5; /* slightly taller on mobile screens */
        }
    }
</style>
@endsection