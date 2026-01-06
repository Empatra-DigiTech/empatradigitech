@extends('home.layouts.master')
@section("title","Home | EMPATRA DIGITECH")

@section('css')
    <link href="assets/css/home/home.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endsection

@section('content')

<!-- ========================================
     1. HERO SECTION
     ======================================== -->
<section id="hero" class="hero-section">
    <div class="hero-carousel swiper-container" data-loop="true" data-autoplay="5000">
        <div class="swiper-wrapper">
            @forelse ($table_banner as $index => $row)
                <div class="swiper-slide">
                    <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $row->image) }}');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="hero-text-wrapper">
                                            <h1 class="hero-headline">{{ $row->title }}</h1>
                                            @if(!empty($row->description))
                                                <p class="hero-subheadline">{{ $row->description }}</p>
                                            @endif
                                            <div class="hero-cta">
                                                <a href="#kontak" class="btn-cta-primary">Get a Quote</a>
                                                <a href="#portfolio" class="btn-cta-secondary">View Portfolio</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1920');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="hero-text-wrapper">
                                            <h1 class="hero-headline">Transform Your Digital Vision Into Reality</h1>
                                            <p class="hero-subheadline">Expert web and mobile development solutions tailored for your business growth</p>
                                            <div class="hero-cta">
                                                <a href="#kontak" class="btn-cta-primary">Get a Quote</a>
                                                <a href="#portfolio" class="btn-cta-secondary">View Portfolio</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"><i class='bx bx-chevron-left'></i></div>
        <div class="swiper-button-next"><i class='bx bx-chevron-right'></i></div>
    </div>
</section>


<!-- ========================================
     2. SERVICES OVERVIEW SECTION
     ======================================== -->
<section id="services" class="services-section">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Comprehensive digital solutions for your business needs</p>
        </div>

        <!-- Services Grid -->
        <div class="services-grid">
            @forelse ($table_layanan as $index => $row)
                <div class="service-card">
                    <div class="service-icon">
                        <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
                    </div>
                    <h3 class="service-title">{{ $row->title }}</h3>
                    <p class="service-description">{{ Str::limit(strip_tags($row->description ?? ''), 120) }}</p>
                    <a href="{{ route('home.layanan.show', $row->id) }}" class="service-link">
                        Learn More <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No services available at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Package Deals Section -->
        <div class="packages-section">
            <div class="packages-header">
                <h3 class="packages-title">Package Deals</h3>
                <p class="packages-subtitle">Choose the perfect package for your project</p>
            </div>

            <!-- Package Tabs -->
            <div class="package-tabs">
                <button class="tab-button active" data-tab="web-packages">
                    <i class='bx bx-laptop'></i>
                    <span>Web Development</span>
                </button>
                <button class="tab-button" data-tab="app-packages">
                    <i class='bx bx-mobile-alt'></i>
                    <span>App Development</span>
                </button>
            </div>

            <!-- Web Development Packages -->
            <div id="web-packages" class="package-content active">
                <div class="packages-grid">
                    @forelse ($paket_website ?? [] as $paket)
                        <div class="package-card {{ $paket->is_recommended ? 'recommended' : '' }}">
                            @if($paket->is_recommended)
                                <div class="recommended-badge">Recommended</div>
                            @endif
                            <div class="package-header">
                                <h4 class="package-name">{{ $paket->nama_paket }}</h4>
                                <div class="package-price">
                                    <span class="currency">Rp</span>
                                    <span class="amount">{{ number_format($paket->harga, 0, ',', '.') }}</span>
                                </div>
                                @if($paket->tagline)
                                    <p class="package-tagline">{{ $paket->tagline }}</p>
                                @endif
                            </div>
                            <div class="package-body">
                                <ul class="package-features">
                                    @if(!empty($paket->fitur))
                                        @foreach(is_array($paket->fitur) ? $paket->fitur : json_decode($paket->fitur, true) as $fitur)
                                            <li><i class='bx bx-check'></i> {{ $fitur }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="package-footer">
                                <a href="{{ route('home.kontak.index') }}?paket=website_{{ $paket->id }}" class="btn-package">
                                    Choose Package
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-packages">
                            <p>Web development packages coming soon</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- App Development Packages -->
            <div id="app-packages" class="package-content">
                <div class="packages-grid">
                    @forelse ($paket_app ?? [] as $paket)
                        <div class="package-card {{ $paket->is_recommended ? 'recommended' : '' }}">
                            @if($paket->is_recommended)
                                <div class="recommended-badge">Recommended</div>
                            @endif
                            <div class="package-header">
                                <h4 class="package-name">{{ $paket->nama_paket }}</h4>
                                <div class="package-price">
                                    <span class="currency">Rp</span>
                                    <span class="amount">{{ number_format($paket->harga, 0, ',', '.') }}</span>
                                </div>
                                @if($paket->tagline)
                                    <p class="package-tagline">{{ $paket->tagline }}</p>
                                @endif
                            </div>
                            <div class="package-body">
                                <ul class="package-features">
                                    @if(!empty($paket->fitur))
                                        @foreach(is_array($paket->fitur) ? $paket->fitur : json_decode($paket->fitur, true) as $fitur)
                                            <li><i class='bx bx-check'></i> {{ $fitur }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="package-footer">
                                <a href="{{ route('home.kontak.index') }}?paket=app_{{ $paket->id }}" class="btn-package">
                                    Choose Package
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-packages">
                            <p>App development packages coming soon</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========================================
     3. PORTFOLIO / CASE STUDIES SECTION
     ======================================== -->
<section id="portfolio" class="portfolio-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Portfolio</h2>
            <p class="section-subtitle">Recent projects that showcase our expertise</p>
        </div>

        <div class="portfolio-grid">
            @forelse ($table_berita as $index => $row)
                <div class="portfolio-item">
                    <div class="portfolio-image">
                        <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
                        <div class="portfolio-overlay">
                            <div class="portfolio-info">
                                <h4 class="portfolio-title">{{ $row->title }}</h4>
                                @if($row->layanan)
                                    <p class="portfolio-category">{{ $row->layanan }}</p>
                                @endif
                                @if($row->brand)
                                    <p class="portfolio-brand">{{ $row->brand }}</p>
                                @endif
                                <a href="{{ route('home.berita.show', $row->id) }}" class="btn-portfolio">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="empty-portfolio">
                        <i class='bx bx-folder-open'></i>
                        <p>No portfolio items available at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($table_berita->count() >= 6)
            <div class="portfolio-cta">
                <a href="{{ route('home.berita.index') }}" class="btn-view-all">
                    View All Projects
                    <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        @endif
    </div>
</section>


<!-- ========================================
     4. WHY CHOOSE US SECTION
     ======================================== -->
<section id="why-choose-us" class="why-choose-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-subtitle">What makes EMPATRA DIGITECH stand out</p>
        </div>

        <div class="value-props-grid">
            <div class="value-prop-card">
                <div class="value-icon">
                    <i class='bx bx-time-five'></i>
                </div>
                <h3 class="value-title">Fast Delivery</h3>
                <p class="value-description">We deliver projects on time without compromising quality</p>
            </div>

            <div class="value-prop-card">
                <div class="value-icon">
                    <i class='bx bx-code-alt'></i>
                </div>
                <h3 class="value-title">Expert Developers</h3>
                <p class="value-description">Experienced team with proven track record</p>
            </div>

            <div class="value-prop-card">
                <div class="value-icon">
                    <i class='bx bx-trending-up'></i>
                </div>
                <h3 class="value-title">Scalable Solutions</h3>
                <p class="value-description">Built to grow with your business needs</p>
            </div>

            <div class="value-prop-card">
                <div class="value-icon">
                    <i class='bx bx-refresh'></i>
                </div>
                <h3 class="value-title">Agile Process</h3>
                <p class="value-description">Flexible and adaptive development methodology</p>
            </div>

            <div class="value-prop-card">
                <div class="value-icon">
                    <i class='bx bx-support'></i>
                </div>
                <h3 class="value-title">Ongoing Support</h3>
                <p class="value-description">Continuous maintenance and technical support</p>
            </div>

            <div class="value-prop-card">
                <div class="value-icon">
                    <i class='bx bx-dollar-circle'></i>
                </div>
                <h3 class="value-title">Competitive Pricing</h3>
                <p class="value-description">Quality services at affordable rates</p>
            </div>
        </div>
    </div>
</section>


<!-- ========================================
     5. TECH STACK SECTION
     ======================================== -->
<section id="tech-stack" class="tech-stack-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Tech Stack</h2>
            <p class="section-subtitle">Technologies we use to build exceptional products</p>
        </div>

        <div class="tech-categories">
            <!-- Frontend -->
            <div class="tech-category">
                <h3 class="tech-category-title">Frontend</h3>
                <div class="tech-logos">
                    <div class="tech-logo">
                        <i class='bx bxl-react'></i>
                        <span>React</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-vuejs'></i>
                        <span>Vue.js</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-angular'></i>
                        <span>Angular</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-javascript'></i>
                        <span>Next.js</span>
                    </div>
                </div>
            </div>

            <!-- Backend -->
            <div class="tech-category">
                <h3 class="tech-category-title">Backend</h3>
                <div class="tech-logos">
                    <div class="tech-logo">
                        <i class='bx bxl-nodejs'></i>
                        <span>Node.js</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-php'></i>
                        <span>Laravel</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-python'></i>
                        <span>Python</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-java'></i>
                        <span>Java</span>
                    </div>
                </div>
            </div>

            <!-- Mobile -->
            <div class="tech-category">
                <h3 class="tech-category-title">Mobile</h3>
                <div class="tech-logos">
                    <div class="tech-logo">
                        <i class='bx bxl-flutter'></i>
                        <span>Flutter</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-react'></i>
                        <span>React Native</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-android'></i>
                        <span>Kotlin</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-apple'></i>
                        <span>Swift</span>
                    </div>
                </div>
            </div>

            <!-- Database & Cloud -->
            <div class="tech-category">
                <h3 class="tech-category-title">Database & Cloud</h3>
                <div class="tech-logos">
                    <div class="tech-logo">
                        <i class='bx bxl-postgresql'></i>
                        <span>PostgreSQL</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-mongodb'></i>
                        <span>MongoDB</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-aws'></i>
                        <span>AWS</span>
                    </div>
                    <div class="tech-logo">
                        <i class='bx bxl-docker'></i>
                        <span>Docker</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========================================
     6. PROCESS / HOW WE WORK SECTION
     ======================================== -->
<section id="process" class="process-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Process</h2>
            <p class="section-subtitle">How we transform ideas into reality</p>
        </div>

        <div class="process-timeline">
            <div class="process-step">
                <div class="step-number">01</div>
                <div class="step-content">
                    <div class="step-icon">
                        <i class='bx bx-conversation'></i>
                    </div>
                    <h3 class="step-title">Consultation</h3>
                    <p class="step-description">Understanding your business goals and project requirements</p>
                </div>
            </div>

            <div class="process-step">
                <div class="step-number">02</div>
                <div class="step-content">
                    <div class="step-icon">
                        <i class='bx bx-edit'></i>
                    </div>
                    <h3 class="step-title">Planning</h3>
                    <p class="step-description">Creating detailed roadmap and technical specifications</p>
                </div>
            </div>

            <div class="process-step">
                <div class="step-number">03</div>
                <div class="step-content">
                    <div class="step-icon">
                        <i class='bx bx-code-block'></i>
                    </div>
                    <h3 class="step-title">Development</h3>
                    <p class="step-description">Building your solution with best practices and clean code</p>
                </div>
            </div>

            <div class="process-step">
                <div class="step-number">04</div>
                <div class="step-content">
                    <div class="step-icon">
                        <i class='bx bx-test-tube'></i>
                    </div>
                    <h3 class="step-title">Testing</h3>
                    <p class="step-description">Rigorous quality assurance and bug fixing</p>
                </div>
            </div>

            <div class="process-step">
                <div class="step-number">05</div>
                <div class="step-content">
                    <div class="step-icon">
                        <i class='bx bx-rocket'></i>
                    </div>
                    <h3 class="step-title">Deployment</h3>
                    <p class="step-description">Launching your product to production environment</p>
                </div>
            </div>

            <div class="process-step">
                <div class="step-number">06</div>
                <div class="step-content">
                    <div class="step-icon">
                        <i class='bx bx-support'></i>
                    </div>
                    <h3 class="step-title">Support</h3>
                    <p class="step-description">Ongoing maintenance and technical assistance</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========================================
     7. TESTIMONIALS SECTION
     ======================================== -->
<section id="testimonials" class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Client Testimonials</h2>
            <p class="section-subtitle">What our clients say about us</p>
        </div>

        <div class="testimonials-slider swiper-container">
            <div class="swiper-wrapper">
                @for($i = 1; $i <= 5; $i++)
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="testimonial-quote">
                                <i class='bx bxs-quote-alt-left'></i>
                            </div>
                            <p class="testimonial-text">
                                "Working with EMPATRA DIGITECH was an absolute pleasure. They delivered our project on time and exceeded our expectations. Highly recommended!"
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <img src="https://i.pravatar.cc/100?img={{ $i }}" alt="Client {{ $i }}">
                                </div>
                                <div class="author-info">
                                    <h4 class="author-name">John Doe {{ $i }}</h4>
                                    <p class="author-role">CEO, Tech Company</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>


<!-- ========================================
     8. ABOUT US / TEAM SECTION
     ======================================== -->
<section id="about" class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-image">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800" alt="Our Team">
                        <div class="about-image-overlay">
                            <div class="about-stats">
                                <div class="stat-item">
                                    <h3 class="stat-number">100+</h3>
                                    <p class="stat-label">Projects Completed</p>
                                </div>
                                <div class="stat-item">
                                    <h3 class="stat-number">50+</h3>
                                    <p class="stat-label">Happy Clients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-text">
                        <h2 class="about-title">About EMPATRA DIGITECH</h2>
                        <p class="about-description">
                            We are a digital technology company dedicated to transforming businesses through innovative web and mobile solutions. Founded on the principles of excellence, integrity, and customer satisfaction.
                        </p>
                        <div class="about-mission">
                            <h3 class="mission-title">Our Mission</h3>
                            <p class="mission-text">
                                To empower businesses with cutting-edge digital solutions that drive growth and success in the modern marketplace.
                            </p>
                        </div>
                        <div class="about-vision">
                            <h3 class="vision-title">Our Vision</h3>
                            <p class="vision-text">
                                To become the leading digital technology partner for businesses across Southeast Asia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========================================
     9. CONTACT SECTION
     ======================================== -->
<section id="kontak" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">Let's discuss your project and bring your ideas to life</p>
        </div>

        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="contact-info-wrapper">
                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class='bx bx-map'></i>
                        </div>
                        <div class="info-content">
                            <h4 class="info-title">Address</h4>
                            <p class="info-text">{{ $table_pengaturan->website_address ?? 'Address not available' }}</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class='bx bx-phone'></i>
                        </div>
                        <div class="info-content">
                            <h4 class="info-title">Phone</h4>
                            <p class="info-text">{{ $table_pengaturan->website_phone ?? 'Phone not available' }}</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="info-icon">
                            <i class='bx bx-envelope'></i>
                        </div>
                        <div class="info-content">
                            <h4 class="info-title">Email</h4>
                            <p class="info-text">{{ $table_pengaturan->website_email ?? 'Email not available' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-wrapper">
                    <form action="{{ route('home.kontak.store') }}" method="post" class="contact-form"
                          onsubmit="return confirm('Are you sure you want to send this message?')"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Attachment (Optional)</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn-submit">
                            <span>Send Message</span>
                            <i class='bx bx-send'></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="contact-map">
            <iframe src="{{ $table_pengaturan->website_map ?? '' }}"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
            </iframe>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>

    // ========================================
// Portfolio Section Animations
// ========================================

// Portfolio Items Animation on Scroll
const portfolioItems = document.querySelectorAll('.portfolio-item');

const portfolioObserverOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
};

const portfolioObserver = new IntersectionObserver(function(entries) {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }, index * 100); // Stagger animation

            portfolioObserver.unobserve(entry.target);
        }
    });
}, portfolioObserverOptions);

portfolioItems.forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(30px)';
    item.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
    portfolioObserver.observe(item);
});

// Portfolio Image Loading
const portfolioImages = document.querySelectorAll('.portfolio-image img');

portfolioImages.forEach(img => {
    img.addEventListener('load', function() {
        this.classList.add('loaded');
        this.style.opacity = '1';
    });

    // If image already loaded
    if (img.complete) {
        img.classList.add('loaded');
        img.style.opacity = '1';
    }
});

// Enhanced Hover Effect
portfolioItems.forEach(item => {
    const overlay = item.querySelector('.portfolio-overlay');
    const info = item.querySelector('.portfolio-info');

    item.addEventListener('mouseenter', function() {
        overlay.style.opacity = '1';
        info.style.transform = 'translateY(0)';
    });

    item.addEventListener('mouseleave', function() {
        overlay.style.opacity = '0';
        info.style.transform = 'translateY(20px)';
    });
});

console.log('Portfolio animations initialized:', portfolioItems.length, 'items');
// ========================================
// WAIT FOR DOM TO LOAD
// ========================================
document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // Hero Carousel Initialization
    // ========================================
    const heroCarousel = new Swiper('.hero-carousel', {
        // Core parameters
        loop: true,
        speed: 800,
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },

        // Autoplay
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: '.hero-carousel .swiper-button-next',
            prevEl: '.hero-carousel .swiper-button-prev',
        },

        // Pagination bullets
        pagination: {
            el: '.hero-carousel .swiper-pagination',
            clickable: true,
            dynamicBullets: false,
        },

        // Keyboard control
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },

        // Accessibility
        a11y: {
            prevSlideMessage: 'Previous slide',
            nextSlideMessage: 'Next slide',
        },
    });

    console.log('Hero Carousel initialized:', heroCarousel);

    // ========================================
    // Testimonials Slider Initialization
    // ========================================
    const testimonialsSlider = new Swiper('.testimonials-slider', {
        loop: true,
        speed: 600,
        spaceBetween: 30,
        slidesPerView: 1,

        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },

        pagination: {
            el: '.testimonials-slider .swiper-pagination',
            clickable: true,
        },

        // Responsive breakpoints
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            }
        }
    });

    console.log('Testimonials Slider initialized:', testimonialsSlider);

    // ========================================
    // Package Tabs Functionality
    // ========================================
    const tabButtons = document.querySelectorAll('.tab-button');
    const packageContents = document.querySelectorAll('.package-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class
            tabButtons.forEach(btn => btn.classList.remove('active'));
            packageContents.forEach(content => content.classList.remove('active'));

            // Add active class
            this.classList.add('active');
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });

    // ========================================
    // Smooth Scroll for Anchor Links
    // ========================================
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            if (href === '#' || href.length <= 1) return;

            const targetElement = document.querySelector(href);

            if (targetElement) {
                e.preventDefault();

                const header = document.querySelector('.header, .navbar, header');
                const headerHeight = header ? header.offsetHeight : 80;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ========================================
    // Scroll Reveal Animation
    // ========================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                // Unobserve after animation
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all sections
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });

    // ========================================
    // Debug Info (HAPUS SETELAH TESTING)
    // ========================================
    console.log('Total slides in hero:', document.querySelectorAll('.hero-carousel .swiper-slide').length);
    console.log('Total slides in testimonials:', document.querySelectorAll('.testimonials-slider .swiper-slide').length);
});
</script>
@endsection
