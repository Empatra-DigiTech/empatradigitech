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