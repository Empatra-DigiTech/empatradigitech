<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{URL::to('/')}}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendor/php-email-form/validate.js"></script>
<script src="{{URL::to('/')}}/assets/vendor/aos/aos.js"></script>
<script src="{{URL::to('/')}}/assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="{{URL::to('/')}}/assets/vendor/swiper/swiper-bundle.min.js"></script>

{{-- jQuery: dibutuhkan oleh halaman Informasi & Galeri. Load sekali saja di sini,
     JANGAN tambahkan bootstrap.bundle.min.js sudah include Popper, jangan load Popper/Bootstrap lagi. --}}
<script src="{{URL::to('/')}}/assets/js/home/core/jquery-3.7.1.min.js"></script>

<!-- Main JS File -->
<script src="{{URL::to('/')}}/assets/js/main.js"></script>

{{-- datatable / script tambahan per halaman --}}
@yield("script")
