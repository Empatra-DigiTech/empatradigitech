<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>
    <meta content="@yield('meta_description', 'Empatra Digitech - Solusi digital untuk bisnis Anda: website, aplikasi, dan konsultasi IT.')" name="description">
    <meta content="" name="keywords">
    <link rel="canonical" href="{{ url()->current() }}">
    @yield('og_tags')

    <!-- Favicons -->
    <link href="{{URL::to('/')}}/assets/img/favicon.png" rel="icon">
    <link href="{{URL::to('/')}}/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{URL::to('/')}}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    {{-- <link href="{{URL::to('/')}}/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet"> --}}

    <!-- Main CSS File -->
    <link href="{{URL::to('/')}}/assets/css/main.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @yield("css")
    @vite(['resources/css/home.css', 'resources/js/home.js'])
  </head>
