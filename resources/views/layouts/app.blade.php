<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cahaya Minang</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

    <style>
        :root {
            --primary-bg: #f5f3ef;
            /* Warm light beige */
            --dark-bg: #2a2a2a;
            /* Charcoal dark */
            --accent-color: #a38c6d;
            --text-main: #333333;
            --font-heading: 'Cormorant Garamond', serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--primary-bg);
            color: var(--text-main);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .brand-logo {
            font-family: var(--font-heading);
            font-weight: 600;
        }

        /* Navbar */
        .navbar-custom {
            padding: 1rem 0;
            background-color: rgba(245, 243, 239, 0.95);
            /* solid background for inner pages */
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        /* Transparent Navbar for welcome page */
        .navbar-transparent {
            background-color: transparent;
            box-shadow: none;
            padding: 1.5rem 0;
        }

        /* Make text white when transparent (before scroll) */
        .navbar-transparent:not(.scrolled) .brand-logo,
        .navbar-transparent:not(.scrolled) .nav-link {
            color: #fff !important;
        }

        .navbar-transparent:not(.scrolled) .btn-nav-outline {
            color: #fff !important;
            border-color: #fff !important;
        }

        .navbar-transparent:not(.scrolled) .btn-nav-outline:hover {
            background-color: #fff !important;
            color: var(--text-main) !important;
        }

        /* Navbar toggler icon color (hamburger menu) */
        .navbar-transparent:not(.scrolled) .navbar-toggler-icon {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .navbar-transparent.scrolled {
            background-color: rgba(245, 243, 239, 0.95);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            font-size: 2rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-main);
            text-decoration: none;
        }

        .nav-link {
            font-size: 0.8rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-main) !important;
            padding: 0.5rem 1.5rem !important;
        }

        .btn-nav-outline {
            border: 1px solid var(--text-main);
            color: var(--text-main);
            border-radius: 0;
            padding: 0.5rem 1.5rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-nav-outline:hover {
            background-color: var(--text-main);
            color: #fff;
        }

        /* Forms & Inputs */
        .form-control,
        .form-select {
            border-radius: 0;
            border: 1px solid #ddd;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: none;
        }

        .form-label {
            font-family: var(--font-body);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
        }

        /* General Buttons */
        .btn-elegant {
            background-color: var(--text-main);
            color: #fff;
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            padding: 0.8rem 2rem;
            border: 1px solid var(--text-main);
            transition: all 0.3s ease;
        }

        .btn-elegant:hover {
            background-color: transparent;
            color: var(--text-main);
        }

        /* Outline variant for general use */
        .btn-outline-custom {
            border: 1px solid var(--text-main);
            color: var(--text-main);
            background: transparent;
            padding: 0.8rem 2.5rem;
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--text-main);
            color: #fff;
        }

        /* Cards */
        .elegant-card {
            background-color: #fff;
            border: none;
            border-radius: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        /* Footer */
        .footer-section {
            background-color: var(--dark-bg);
            color: #fff;
            padding: 3rem 0;
            margin-top: auto;
        }

        .footer-logo {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            letter-spacing: 1px;
            color: #fff;
            text-decoration: none;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #fff !important;
        }

        .footer-section .border-secondary {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.7);
            margin-right: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .social-links a:hover {
            color: var(--dark-bg);
            background-color: var(--accent-color);
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }


        /* Adjustments for Inner Pages */
        body.inner-page main {
            padding-top: 80px;
        }

        /* ===== GLOBAL RESPONSIVE FIXES ===== */
        @media (max-width: 991px) {
            .brand-logo {
                font-size: 1.5rem;
            }

            .nav-link {
                padding: 0.5rem 0.75rem !important;
                letter-spacing: 1px;
            }

            #navbarResponsive {
                background: rgba(245, 243, 239, 0.98);
                backdrop-filter: blur(10px);
                padding: 1rem;
                border-top: 1px solid rgba(0, 0, 0, 0.06);
                margin-top: 0.5rem;
            }

            #navbarResponsive .d-flex {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
                padding-top: 0.75rem;
            }

            .btn-nav-outline {
                width: 100%;
                text-align: center;
                padding: 0.6rem 1rem;
            }
        }

        @media (max-width: 768px) {

            /* Typography */
            h1,
            .display-1 {
                font-size: clamp(2rem, 8vw, 3.5rem) !important;
            }

            h2,
            .display-2 {
                font-size: clamp(1.6rem, 6vw, 2.5rem) !important;
            }

            h3 {
                font-size: clamp(1.3rem, 5vw, 2rem) !important;
            }

            .display-4 {
                font-size: clamp(1.8rem, 7vw, 3rem) !important;
            }

            /* Spacing */
            .py-5 {
                padding-top: 2.5rem !important;
                padding-bottom: 2.5rem !important;
            }

            .pb-5 {
                padding-bottom: 2.5rem !important;
            }

            .pt-5 {
                padding-top: 2.5rem !important;
            }

            .mb-5 {
                margin-bottom: 2rem !important;
            }

            /* Package Builder mobile */
            .builder-container {
                flex-direction: column !important;
            }

            .builder-right {
                width: 100% !important;
                position: static !important;
            }

            .builder-left {
                width: 100% !important;
            }

            .hero-img {
                height: 200px !important;
            }

            .package-name {
                font-size: 1.5rem !important;
            }

            .payment-type-selector {
                flex-direction: column;
            }

            /* Tables on mobile */
            .table-responsive-stack td,
            .table-responsive-stack th {
                display: block;
                width: 100%;
            }

            /* Admin sidebar - hide on mobile */
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        @media (max-width: 576px) {

            .container,
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .btn-elegant,
            .btn-outline-custom {
                padding: 0.7rem 1.25rem;
                font-size: 0.8rem;
            }

            .elegant-card {
                padding: 1.25rem !important;
            }

            .builder-section {
                padding: 1.25rem !important;
            }

            .package-hero .col-md-7 {
                padding: 1.25rem !important;
            }

            .section-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .base-price-display {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }

            .base-price-display .amount {
                font-size: 1.3rem;
            }

            .summary-total {
                font-size: 1rem;
            }

            /* Fix table overflow on small screens */
            table {
                font-size: 0.85rem;
            }
        }

        /* Floating WhatsApp button */
        .whatsapp-float {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background-color: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.8rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            z-index: 1050;
            transition: transform 0.2s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.08);
            color: #fff;
        }
    </style>
    @yield('styles')
</head>

<body class="@yield('body-class', 'inner-page')">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top w-100 @yield('navbar-class', 'navbar-custom')" id="mainNav">
        <div class="container-fluid px-4 px-lg-5">
            <a class="brand-logo" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Cahaya Minang" style="height: 60px; width: auto;">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('packages.index') }}">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('gallery.index') }}">Portfolio</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        @if (auth()->user()->role == 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="btn-nav-outline text-decoration-none">Dashboard</a>
                        @else
                            <div class="dropdown">
                                <button class="btn-nav-outline dropdown-toggle" type="button" id="userMenu"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-0"
                                    aria-labelledby="userMenu">
                                    <li><a class="dropdown-item" href="{{ route('user.orders') }}">My Orders</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Log Out</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="nav-link me-3">Log In</a>
                        <a href="{{ route('register') }}" class="btn-nav-outline text-decoration-none">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages via SweetAlert2 -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ addslashes(session('success')) }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'shadow-lg'
                    }
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '{{ addslashes(session('error')) }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'shadow-lg'
                    }
                });
            });
        </script>
    @endif
    @if (session('payment_reminders') && session('payment_reminders')->isNotEmpty())
        @php
            $paymentReminderItems = session('payment_reminders')
                ->map(function ($payment) {
                    return [
                        'order_id' => $payment->order_id,
                        'name' => $payment->name,
                        'amount' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                        'due_date' => \Carbon\Carbon::parse($payment->due_date)->translatedFormat('d M Y'),
                        'overdue' => \Carbon\Carbon::parse($payment->due_date)->endOfDay()->isPast(),
                    ];
                })
                ->values();
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const items = @json($paymentReminderItems);

                const listHtml = items.map(i => `
                    <li style="margin-bottom:0.6rem;">
                        <strong>${i.name}</strong> (Order #WO-${i.order_id})<br>
                        ${i.amount} &mdash; ${i.overdue ? '<span style="color:#dc3545;">Sudah lewat jatuh tempo</span>' : 'Jatuh tempo ' + i.due_date}
                    </li>
                `).join('');

                Swal.fire({
                    icon: 'info',
                    title: 'Pengingat Pembayaran',
                    html: `<p class="mb-3 small">Anda memiliki tagihan DP/termin yang mendekati atau melewati jatuh tempo:</p><ul style="text-align:left; padding-left:1.2rem;">${listHtml}</ul>`,
                    confirmButtonText: 'Lihat Pesanan Saya',
                    confirmButtonColor: '#a38c6d',
                    showCancelButton: true,
                    cancelButtonText: 'Nanti Saja',
                    customClass: {
                        popup: 'shadow-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('user.orders') }}';
                    }
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errList = @json($errors->all());
                const errHtml = errList.map(e => `<li style="text-align:left;">${e}</li>`).join('');
                Swal.fire({
                    icon: 'warning',
                    title: 'Periksa Formulir Anda',
                    html: `<ul style="padding-left:1.2rem; margin:0;">${errHtml}</ul>`,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#a38c6d',
                    customClass: {
                        popup: 'shadow-lg'
                    }
                });
            });
        </script>
    @endif

    <main @yield('main-attributes')>
        @yield('content')
    </main>

    <footer class="footer-section">
        <div class="container px-4 px-lg-5">
            <div class="row mb-5">
                <!-- Column 1 -->
                <div class="col-lg-6 col-md-6 mb-4 mb-lg-0">
                    <div class="mb-4">
                        <a href="{{ route('home') }}" class="footer-logo d-block mb-1" style="line-height: 1.2;">
                            <img src="{{ asset('images/logo.png') }}" alt="Cahaya Minang"
                                style="height: 50px; width: auto;">
                        </a>
                        <span style="color: rgba(255,255,255,0.5); font-size: 0.75rem; letter-spacing: 1px;"
                            class="text-uppercase">Wedding Organizer</span>
                    </div>
                    <p class="footer-text small mb-4" style="line-height: 1.8; max-width: 300px;">
                        Empowering couples with cutting-edge event solutions that drive innovation and accelerate
                        unforgettable dream weddings.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                        <a href="#"><i class="bi bi-pinterest"></i></a>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-lg-6 col-md-6 mb-4 mb-lg-0">
                    <h6 class="mb-4"
                        style="color: var(--accent-color); font-weight: 600; letter-spacing: 0.5px; font-size: 0.9rem;">
                        <i class="bi bi-compass me-2"></i>Navigation
                    </h6>
                    <ul class="list-unstyled mb-0 footer-links">
                        <li class="mb-3"><a href="{{ route('home') }}" class="text-decoration-none small">Home</a>
                        </li>
                        <li class="mb-3"><a href="{{ route('about') }}" class="text-decoration-none small">About
                                Us</a></li>
                        <li class="mb-3"><a href="{{ route('packages.index') }}"
                                class="text-decoration-none small">Packages</a></li>
                        <li class="mb-0"><a href="{{ route('gallery.index') }}"
                                class="text-decoration-none small">Portfolio</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="row pt-4 border-top border-secondary align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="footer-text small mb-0">&copy; {{ date('Y') }} Cahaya Minang. All rights reserved.
                        <span class="ms-3" style="color: #4cd137;"><i class="bi bi-circle-fill me-1"
                                style="font-size: 8px; vertical-align: middle;"></i> All systems operational</span>
                    </p>
                </div>
                <div class="col-md-6 text-md-end footer-links">
                    <a href="#" class="text-decoration-none small me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none small me-3">Terms of Service</a>
                    <a href="#" class="text-decoration-none small">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    @if (config('services.whatsapp.number'))
        <a href="https://wa.me/{{ config('services.whatsapp.number') }}" target="_blank" rel="noopener"
            class="whatsapp-float" title="Chat via WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </a>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        // Navbar Scroll Effect for Transparent Navbar
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNav');
            if (nav && nav.classList.contains('navbar-transparent')) {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            }
        });

        // Intersection Observer for fade-in animations
        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.fade-in-up').forEach((el) => {
                observer.observe(el);
            });

            // Initialize GLightbox
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                closeOnOutsideClick: true
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
