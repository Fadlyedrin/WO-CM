@extends('layouts.app')
@section('body-class', 'home-page')

@section('navbar-class', 'navbar-transparent')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="hero-section mt-0 fade-in-up">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Cahaya Minang</h1>
            <p class="hero-subtitle">Where beauty meets flawlessly executed weddings</p>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section fade-in-up">
        <div class="container px-4 px-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                        alt="Bride with flowers" class="services-img">
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h2 class="section-title text-start mb-4">Our Services</h2>
                    <p class="text-muted mb-5">We believe every love story is unique, and your celebration should be too.
                        Our meticulous planning and design approach ensures a seamless, beautiful, and unforgettable
                        experience.</p>

                    <ul class="service-list">
                        <li>
                            <h5>Full Wedding Planning</h5>
                            <p>From venue selection to the final send-off, we handle every detail so you can enjoy your
                                engagement.</p>
                        </li>
                        <li>
                            <h5>Event Design & Styling</h5>
                            <p>Creating cohesive, breathtaking visual narratives tailored to your unique aesthetic
                                preferences.</p>
                        </li>
                        <li>
                            <h5>Day-of Coordination</h5>
                            <p>Perfect for the couple who has planned it all but needs a professional to ensure smooth
                                execution on the big day.</p>
                        </li>
                    </ul>
                    <a href="#contact" class="btn btn-outline-custom mt-4">Contact Here</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Highlight -->
    <section class="packages-section fade-in-up">
        <div class="container px-4 px-lg-5">
            <h2 class="section-title mb-5">Curated Packages</h2>
            <div class="row g-4 justify-content-center">
                @foreach ($packages as $package)
                    <div class="col-md-6 col-lg-4">
                        <div class="package-card shadow-sm elegant-card p-0 d-flex flex-column">
                            <img src="{{ $package->image_url ? Storage::url($package->image_url) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80' }}"
                                class="card-img-top package-card-img" alt="{{ $package->name }}">
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <h4 class="mb-3">{{ $package->name }}</h4>
                                <p class="text-muted small mb-4 flex-grow-1">
                                    {{ Str::limit(strip_tags($package->description), 100) }}
                                </p>
                                <div class="package-price">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                <a href="{{ route('packages.show', $package->slug) }}"
                                    class="btn btn-outline-custom mt-auto w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('packages.index') }}" class="btn btn-outline-custom">View All Packages</a>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio-section fade-in-up">
        <div class="container px-4 px-lg-5">
            <h2 class="section-title">Portfolio</h2>

            <div class="masonry-grid mb-5">
                @forelse($galleries as $gallery)
                    <div class="masonry-item">
                        <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}" loading="lazy">
                    </div>
                @empty
                    <!-- Placeholder Images if no gallery -->
                    <div class="masonry-item"><img
                            src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            alt="Wedding 1"></div>
                    <div class="masonry-item"><img
                            src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            alt="Wedding 2"></div>
                    <div class="masonry-item"><img
                            src="https://images.unsplash.com/photo-1606800052052-a08af7148866?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            alt="Wedding 3"></div>
                    <div class="masonry-item"><img
                            src="https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            alt="Wedding 4"></div>
                @endforelse
            </div>

            <div class="text-center">
                <a href="{{ route('gallery.index') }}" class="btn btn-outline-custom">View Full Portfolio</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section fade-in-up">
        <div class="container px-4 px-lg-5">
            <div class="row align-items-center flex-lg-row-reverse">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <img src="{{ asset('images/about.png') }}"
                        alt="Founders" class="about-img">
                </div>
                <div class="col-lg-6 offset-lg-1 text-center text-lg-start">
                    <h2 class="section-title text-lg-start mb-4">About Us</h2>
                    <p class="text-muted mb-4">Founded on the belief that love deserves to be celebrated with exquisite
                        attention to detail, Cahaya Minang is a boutique wedding planning agency dedicated to crafting
                        moments that take your breath away.</p>
                    <p class="text-muted mb-5">Our team of passionate designers and meticulous planners work closely with
                        you to translate your vision into a sensory experience. We don't just plan weddings; we design
                        legacies of love.</p>
                    <a href="#contact" class="btn btn-outline-custom">Meet The Founders</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    {{-- <section class="testimonials-section fade-in-up">
        <div class="container px-4 px-lg-5">
            <h2 class="section-title">Testimonials</h2>
            <div class="row g-5 text-center mt-2">
                <div class="col-md-4">
                    <p class="testimonial-text">"Every detail was handled with such grace and precision. Our wedding day was truly a dream come true, beyond anything we could have imagined."</p>
                    <span class="testimonial-author">- Sarah & Matthew</span>
                </div>
                <div class="col-md-4">
                    <p class="testimonial-text">"The team at Cahaya Minang brought our vision to life. Their design aesthetic is unmatched, and their coordination let us simply enjoy our day."</p>
                    <span class="testimonial-author">- Chloe & Michael</span>
                </div>
                <div class="col-md-4">
                    <p class="testimonial-text">"Professional, intuitive, and incredibly talented. Investing in their services was the best decision we made for our celebration."</p>
                    <span class="testimonial-author">- Emma & David</span>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Contact Form Section (Separated from Footer) -->
    <section id="contact" class="contact-form-section py-5 fade-in-up"
        style="background-color: #fcfbf9; border-top: 1px solid #eee;">
        <div class="container px-4 px-lg-5 py-5">
            <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h2 class="section-title text-start mb-4" style="color: var(--text-main);">Get In Touch</h2>
                    <p class="text-muted mb-4">We would love to hear from you. Whether you have a question about our
                        services, pricing, or anything else, our team is ready to answer all your questions.</p>

                    <div class="mt-4">
                        <p class="text-muted mb-2"><i class="bi bi-geo-alt me-2" style="color: var(--accent-color);"></i>
                            1234 Elegant Avenue, Suite 100, Jakarta</p>
                        <p class="text-muted mb-2"><i class="bi bi-envelope me-2" style="color: var(--accent-color);"></i>
                            hello@cahayaminang.com</p>
                        <p class="text-muted"><i class="bi bi-telephone me-2" style="color: var(--accent-color);"></i> +62
                            812 3456 7890</p>
                    </div>
                </div>

                <div class="col-lg-6 offset-lg-1">
                    <div class="card elegant-card p-5">
                        <form action="{{ route('inquiries.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <input type="text" name="name"
                                        class="form-control form-control-minimal text-dark" placeholder="First Name"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-minimal text-dark"
                                        placeholder="Last Name">
                                </div>
                                <div class="col-12">
                                    <input type="email" name="email"
                                        class="form-control form-control-minimal text-dark" placeholder="Email Address"
                                        required>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="phone"
                                        class="form-control form-control-minimal text-dark" placeholder="Phone Number"
                                        required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control form-control-minimal text-dark" rows="3"
                                        placeholder="Tell us about your dream wedding..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-elegant w-100 py-3 text-uppercase"
                                        style="letter-spacing: 2px;">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('styles')
    <style>
        /* Hero Section specific to welcome page */
        .hero-section {
            position: relative;
            height: 100vh;
            margin: 0;
            background-image: url('{{ asset('images/hero.png') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #fff;
        }

        .hero-title {
            font-size: 5rem;
            letter-spacing: 8px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 0.9rem;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        /* Section Titles */
        .section-title {
            font-size: 3.5rem;
            margin-bottom: 3rem;
            color: var(--text-main);
            text-align: center;
        }

        /* Services Section */
        .services-section {
            padding: 8rem 0;
        }

        .services-img {
            width: 100%;
            height: 600px;
            object-fit: cover;
            box-shadow: -20px -20px 0px rgba(0, 0, 0, 0.03);
        }

        .service-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }

        .service-list li {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .service-list h5 {
            font-family: var(--font-body);
            font-weight: 500;
            font-size: 1.1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .service-list p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* Packages Section (Bonus) */
        .packages-section {
            padding: 5rem 0 8rem;
        }

        .package-card {
            text-align: center;
            transition: transform 0.3s ease;
            height: 100%;
            overflow: hidden;
        }

        .package-card:hover {
            transform: translateY(-10px);
        }

        .package-card-img {
            height: 250px;
            object-fit: cover;
            border-radius: 0;
        }

        .package-price {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: var(--accent-color);
            margin: 1rem 0;
        }

        /* Portfolio Section */
        .portfolio-section {
            background-color: #ebe7e0;
            padding: 8rem 0;
        }

        .masonry-grid {
            column-count: 3;
            column-gap: 1.5rem;
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }

        .masonry-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .masonry-item:hover img {
            transform: scale(1.02);
        }

        /* About Section */
        .about-section {
            padding: 8rem 0;
        }

        .about-img {
            width: 100%;
            height: 700px;
            object-fit: cover;
            box-shadow: 20px -20px 0px rgba(0, 0, 0, 0.03);
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 4rem 0 8rem;
        }

        .testimonial-text {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            font-style: italic;
            color: #555;
            margin-bottom: 1.5rem;
        }

        .testimonial-author {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-main);
        }

        /* Minimalist Form for Contact */
        .form-control-minimal {
            background: transparent !important;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0;
            padding: 1rem 0;
            box-shadow: none !important;
            font-size: 0.9rem;
        }

        .form-control-minimal:focus {
            border-bottom-color: #fff;
        }

        .form-control-minimal::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Add overrides for text-dark form controls */
        .form-control-minimal.text-dark {
            color: var(--text-main) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.2) !important;
        }

        .form-control-minimal.text-dark:focus {
            border-bottom-color: var(--accent-color) !important;
        }

        .form-control-minimal.text-dark::placeholder {
            color: rgba(0, 0, 0, 0.4);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 3.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .services-section,
            .packages-section,
            .portfolio-section,
            .about-section,
            .testimonials-section {
                padding: 4rem 0;
            }

            .masonry-grid {
                column-count: 2;
            }

            .about-img {
                height: 450px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 85vh;
            }

            .hero-title {
                font-size: 2.8rem;
                letter-spacing: 3px;
            }

            .hero-subtitle {
                font-size: 0.9rem;
                letter-spacing: 3px;
            }

            .hero-content {
                padding: 0 1.5rem;
            }

            .masonry-grid {
                column-count: 2;
                column-gap: 0.75rem;
            }

            .masonry-item {
                margin-bottom: 0.75rem;
            }

            .services-img,
            .about-img {
                height: 320px;
                margin-bottom: 2rem;
            }

            .package-card-img {
                height: 200px;
            }

            .package-price {
                font-size: 1.5rem;
            }

            .services-section,
            .packages-section,
            .portfolio-section,
            .about-section,
            .testimonials-section {
                padding: 3rem 0;
            }

            .section-title {
                font-size: 2rem !important;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                min-height: 92vh;
            }

            .hero-title {
                font-size: 2.2rem;
                letter-spacing: 2px;
            }

            .hero-subtitle {
                font-size: 0.8rem;
                letter-spacing: 2px;
            }

            .masonry-grid {
                column-count: 1;
            }

            .services-img,
            .about-img {
                height: 250px;
            }

            .services-section,
            .packages-section,
            .portfolio-section,
            .about-section,
            .testimonials-section {
                padding: 2.5rem 0;
            }

            /* vendor cta on show page */
            .vendor-cta {
                gap: 0.75rem;
            }
        }
    </style>
@endsection
