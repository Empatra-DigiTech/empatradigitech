<!DOCTYPE html>
<html lang="en">
    @include('home.layouts.head')
<body>
    <div class="wrapper">
        @include('sweetalert::alert')
        @include('home.layouts.navbar')
        
        <div class="content-wrapper">
            <section class="content">
                <div class="">
                    <main class="main">
                        @yield("content")
                    </main>
                </div>
            </section>
            
            <!-- Scroll Top -->
            <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
                <i class="bi bi-arrow-up-short"></i>
            </a>

            <!-- Floating WhatsApp Button -->
            <a href="https://wa.me/6285151811055?text={{ urlencode('Halo Empatra DigiTech, saya ingin konsultasi gratis untuk kebutuhan digital saya.') }}"
               target="_blank" rel="noopener" id="wa-float" class="wa-float d-flex align-items-center justify-content-center"
               aria-label="Chat via WhatsApp">
                <i class='bx bxl-whatsapp'></i>
            </a>

            @include('home.component.popout')
        
            @include('home.layouts.footer')
        </div>
    </div>
    
    @include('home.layouts.script')

    <script>
        // Smooth scroll to top functionality
        const scrollTop = document.getElementById('scroll-top');
        
        // Show/hide scroll button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTop.classList.add('active');
            } else {
                scrollTop.classList.remove('active');
            }
        });
        
        // Smooth scroll to top
        scrollTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>