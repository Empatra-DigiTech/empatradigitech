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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..800&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{URL::to('/')}}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    {{-- <link href="{{URL::to('/')}}/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet"> --}}

    <!-- Main CSS File -->
    <link href="{{URL::to('/')}}/assets/css/tokens.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/assets/css/main.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @yield("css")
    @vite(['resources/css/home.css', 'resources/js/home.js'])
  </head>
