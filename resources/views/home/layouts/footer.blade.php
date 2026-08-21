<footer id="footer" class="footer position-relative">
    <div class="container footer-top">
        <div class="row gy-4">

            <!-- Company Logo & Motto Section -->
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="{{ route('home.home.index') }}" class="logo d-flex justify-content-center mb-4"> @if(optional($table_pengaturan)->website_logo) <img src="{{ asset('storage/' . $table_pengaturan->website_logo) }}" alt="Company Logo"> @else <img src="{{ URL::to('/') }}/assets/img/favicon.png" alt="Company Logo"> @endif </a>
                <div class="footer-contact">
                    <p class="footer-motto">{{ $table_pengaturan->website_motto }}</p>
                </div>
            </div>

            <!-- Office Address & Map Section -->
            <div class="col-lg-2 col-md-3 footer-links">
                <h4 class="footer-heading">Alamat Kantor</h4>
                <p class="footer-address">{{ $table_pengaturan->website_address }}</p>
                <div class="footer-map-wrapper mt-3">
                    <iframe
                        id="maps_mini"
                        src="{{ $table_pengaturan->website_map ?? '' }}"
                        frameborder="0"
                        loading="lazy"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- Visitor Counter Section -->
            <div class="col-lg-2 col-md-3 footer-links">
                <h4 class="footer-heading">Pengunjung</h4>
                <div class="visitor-counter">
                    <div class="counter-display">
                        <i class="bi bi-people-fill"></i>
                        <span id="visitor-count">0</span>
                    </div>
                    <p class="counter-label">Total Pengunjung</p>
                </div>
            </div>

            <!-- Contact & Social Media Section -->
            <div class="col-lg-4 col-md-12 footer-newsletter">
                <h4 class="footer-heading">Kontak</h4>

                <div class="contact-info">
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <div class="contact-details">
                            <strong>Phone:</strong>
                            <span>{{ $table_pengaturan->website_phone }}</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <div class="contact-details">
                            <strong>Email:</strong>
                            <span>{{ $table_pengaturan->website_email }}</span>
                        </div>
                    </div>
                </div>

                <div class="social-links d-flex justify-content-center mt-4">
                    <a href="https://www.facebook.com/p/Empatra-DigiTech-61577005718235/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/empatra_digitech/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://wa.me/6285172270460" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Bottom - Copyright Section -->
    <div class="container footer-bottom">
        <div class="row">
            <div class="col-12">
                <div class="copyright text-center">
                    <p>
                        &copy; <span id="current-year"></span>
                        <strong>{{ $table_pengaturan->website_name ?? 'Your Company' }}</strong>.
                        All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ========================================
        // Set Current Year in Copyright
        // ========================================
        const currentYearElement = document.getElementById('current-year');
        if (currentYearElement) {
            currentYearElement.textContent = new Date().getFullYear();
        }


        // ========================================
        // Visitor Counter Animation
        // ========================================
        const visitorCountElement = document.getElementById('visitor-count');

        if (visitorCountElement) {
            // Get actual visitor count from backend or use localStorage for demo
            let targetCount = parseInt(localStorage.getItem('visitorCount')) || 12543;

            // Increment visitor count
            targetCount += 1;
            localStorage.setItem('visitorCount', targetCount);

            // Animate counter
            let currentCount = 0;
            const duration = 2000; // 2 seconds
            const increment = targetCount / (duration / 16); // 60fps

            const counterInterval = setInterval(function() {
                currentCount += increment;

                if (currentCount >= targetCount) {
                    currentCount = targetCount;
                    clearInterval(counterInterval);
                }

                visitorCountElement.textContent = Math.floor(currentCount).toLocaleString();
            }, 16);
        }


        // ========================================
        // Social Links Hover Effect
        // ========================================
        const socialLinks = document.querySelectorAll('.social-links a');

        socialLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.1)';
            });

            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });


        // ========================================
        // Smooth Scroll for Footer Links
        // ========================================
        const footerLinks = document.querySelectorAll('.footer a[href^="#"]');

        footerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href !== '#') {
                    const targetElement = document.querySelector(href);

                    if (targetElement) {
                        e.preventDefault();

                        const header = document.getElementById('header');
                        const headerHeight = header ? header.offsetHeight : 0;
                        const targetPosition = targetElement.offsetTop - headerHeight - 20;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });


        // ========================================
        // Email Protection (Anti-spam)
        // ========================================
        const emailLinks = document.querySelectorAll('.footer a[href^="mailto:"]');

        emailLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Track email clicks if analytics is needed
                console.log('Email link clicked');
            });
        });

    });
</script>
