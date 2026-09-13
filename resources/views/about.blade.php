@extends('layouts.app')
@section('body-class', 'inner-page')
@section('content')

    <!-- Page Header -->
    <section class="page-header text-center" style="padding: 6rem 0 4rem;">
        <div class="container">
            <h1 class="page-title mb-3" style="font-size: 3.5rem; letter-spacing: 4px; color: var(--text-main);">Tentang Kami
            </h1>
            <p class="text-muted text-uppercase" style="letter-spacing: 2px; font-size: 0.9rem;">Mengenal Lebih Dekat Cahaya
                Minang</p>
        </div>
    </section>

    <!-- History & Story Section (Photo 1) -->
    <section class="story-section py-5">
        <div class="container px-4 px-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="image-wrapper position-relative fade-in-up">
                        <img src="{{ asset('images/about2.png') }}" alt="Cahaya Minang Story"
                            class="img-fluid rounded-0 shadow-lg" style="height: 600px; width: 100%; object-fit: cover;">
                        <!-- Decorative Element -->
                        <div class="position-absolute"
                            style="border: 1px solid var(--accent-color); top: -20px; left: -20px; right: 20px; bottom: 20px; z-index: -1;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 fade-in-up" style="transition-delay: 0.2s;">
                    <h2 class="mb-4" style="font-size: 2.5rem; color: var(--text-main);">Kisah Perjalanan Kami</h2>
                    <div style="width: 50px; height: 2px; background-color: var(--accent-color); margin-bottom: 2rem;">
                    </div>
                    <p class="text-muted mb-4" style="line-height: 1.8; font-size: 1.05rem;">
                        Berawal dari kecintaan yang mendalam terhadap kekayaan budaya Nusantara, khususnya pesona tradisi
                        Minangkabau, <strong>Sanggar Cahaya Minang</strong> didirikan sebagai wadah untuk melestarikan dan
                        menyajikan keindahan pernikahan adat dalam balutan yang elegan dan modern.
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.8; font-size: 1.05rem;">
                        Bagi kami, pernikahan bukan sekadar acara perayaan, melainkan sebuah mahakarya cinta yang
                        menggabungkan nilai-nilai luhur keluarga, adat istiadat, dan impian kedua mempelai. Kami hadir untuk
                        memastikan setiap langkah menuju hari bahagia Anda tertata dengan sempurna, megah, dan tak
                        terlupakan.
                    </p>
                    <p class="text-muted mb-0" style="line-height: 1.8; font-size: 1.05rem;">
                        Dengan tim profesional yang berdedikasi tinggi, kami telah dipercaya oleh ratusan pasangan untuk
                        mewujudkan pernikahan impian mereka, dari konsep tradisional yang kental hingga resepsi modern yang
                        gemerlap.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section (Photo 2) -->
    <section class="vision-mission-section py-5 my-5"
        style="background-color: #fcfbf9; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
        <div class="container px-4 px-lg-5 py-5">
            <div class="row align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="image-wrapper position-relative fade-in-up">
                        <img src="{{ asset('images/about3.png') }}" alt="Cahaya Minang Vision"
                            class="img-fluid rounded-0 shadow-lg" style="height: 600px; width: 100%; object-fit: cover;">
                        <!-- Decorative Element -->
                        <div class="position-absolute"
                            style="border: 1px solid var(--accent-color); top: 20px; left: 20px; right: -20px; bottom: -20px; z-index: -1;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 me-auto fade-in-up" style="transition-delay: 0.2s;">
                    <h2 class="mb-4" style="font-size: 2.5rem; color: var(--text-main);">Visi & Misi Kami</h2>
                    <div style="width: 50px; height: 2px; background-color: var(--accent-color); margin-bottom: 2rem;">
                    </div>

                    <h4 class="mb-3" style="color: var(--accent-color); font-size: 1.5rem;"><i
                            class="bi bi-eye text-muted me-2"></i> Visi</h4>
                    <ul class="list-unstyled mb-5">
                        <li class="d-flex mb-3">
                            <i class="bi bi-check2 text-success fs-5 me-3 mt-1"></i>
                            <span class="text-muted" style="line-height: 1.6;">Menjadi sanggar dan wedding organizer
                                terdepan yang menginspirasi melalui perpaduan harmonis antara tradisi luhur dan keanggunan
                                modern.</span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="bi bi-check2 text-success fs-5 me-3 mt-1"></i>
                            <span class="text-muted" style="line-height: 1.6;">Mewujudkan pernikahan berskala nasional yang
                                dikenal akan kualitas pelayanan prima dan detail estetika yang tak tertandingi.</span>
                        </li>
                    </ul>

                    <h4 class="mb-3" style="color: var(--accent-color); font-size: 1.5rem;"><i
                            class="bi bi-bullseye text-muted me-2"></i> Misi</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-3">
                            <i class="bi bi-arrow-right-short text-primary fs-5 me-2 mt-1"></i>
                            <span class="text-muted" style="line-height: 1.6;">Memberikan pelayanan holistik dan personal
                                (tailor-made) untuk memastikan setiap pernikahan mencerminkan karakter dan impian unik
                                setiap pasangan.</span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="bi bi-arrow-right-short text-primary fs-5 me-2 mt-1"></i>
                            <span class="text-muted" style="line-height: 1.6;">Melestarikan keindahan budaya Nusantara,
                                dengan cara menyajikannya secara profesional, inovatif, dan relevan dengan perkembangan
                                zaman.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section text-center py-5 mb-5 fade-in-up">
        <div class="container">
            <h3 class="mb-4" style="font-family: var(--font-heading); font-size: 2rem;">Mari Wujudkan Pernikahan Impian
                Anda</h3>
            <p class="text-muted mb-4 mx-auto" style="max-width: 600px;">Tim ahli kami siap mendengarkan cerita Anda dan
                merancang perayaan yang tak terlupakan.</p>
            <a href="{{ route('packages.index') }}" class="btn btn-elegant">Lihat Layanan Kami</a>
        </div>
    </section>

@endsection
