
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport" shrink-to-fit=no">
        <title>Login | EMPATRA DIGITECH</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="{{URL::to('/')}}/assets/img/favicon.png" rel="icon">
        <link href="{{URL::to('/')}}/assets/img/favicon.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

        <!-- Vendor CSS Files -->
        {{-- Bootstrap 4 (login/bootstrap.min.css di bawah) adalah satu-satunya Bootstrap halaman ini; markup memakai kelas BS4 (ml-auto, btn-block, form-group). --}}
        <link href="{{URL::to('/')}}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="{{URL::to('/')}}/assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="{{URL::to('/')}}/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="{{URL::to('/')}}/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

        {{-- login css --}}
        <link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/login/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/login/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/login/style.css') }}">

        {{-- CDN --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> --}}

        <!-- Main CSS File -->
        <link href="{{URL::to('/')}}/assets/css/main.css" rel="stylesheet">
        <link href="{{URL::to('/')}}/assets/css/profil/so.css" rel="stylesheet">
        <link href="{{URL::to('/')}}/assets/css/profil/vm.css" rel="stylesheet">

        {{-- KAIADMIN CSS --}}
        <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/home/plugins.min.css" />
        <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/home/kaiadmin.min.css" />

    </head>
<body>
    @include('sweetalert::alert')
    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2" style="background-image: url('{{ asset("assets/img/login/login.png") }}');"></div>
        <div class="contents order-2 order-md-1">

        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-7">
                    <h3>LUPA PASSWORD</h3>
                        <strong>EMPATRA DIGITECH</strong>
                            <form action="{{ route('auth.forgot_pw.index') }}" method="POST" class="user">
                                @csrf
                                <div class="form-group first">
                                    <label align="left" for="email">Email</label>
                                    <input type="email" class="form-control" placeholder="Masukan Email" id="email" name="email">
                                </div>
                                <div class="d-flex mb-5 align-items-center">
                                    <span class="ml-auto"><a href="{{ route('auth.login.index') }}" class="forgot-pass">Sudah Punyak Akun? Login Disini</a></span>
                                </div>
                                <input type="submit" value="Kirim" class="btn btn-block btn-primary" style="background-color: #2a0f60 !important; border:none !important;">
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
        {{-- login script --}}
        <script src="{{URL::to('/')}}/assets/js/login/jquery-3.3.1.min.js"></script>
        <script src="{{URL::to('/')}}/assets/js/login/popper.min.js"></script>
        <script src="{{URL::to('/')}}/assets/js/login/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/assets/js/login/main.js"></script>
</body>
</html>

