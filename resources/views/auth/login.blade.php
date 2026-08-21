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
        <link href="{{URL::to('/')}}/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{URL::to('/')}}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{URL::to('/')}}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

        <!-- Login CSS - CUSTOM STYLING -->
        <link href="{{URL::to('/')}}/assets/css/login/login.css" rel="stylesheet">

    </head>
<body>
    @include('sweetalert::alert')
    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2" style="background-image: url('{{ asset("assets/img/login/login.png") }}');"></div>
        <div class="contents order-2 order-md-1">

        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-12">
                    <h3>Login ke</h3>
                    <strong>EMPATRA DIGITECH</strong>

                    <form action="{{ route('auth.login.index') }}" method="POST" class="user">
                        @csrf

                        <div class="form-group first">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Masukan Email" id="email" name="email" required>
                        </div>

                        <div class="form-group last mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Masukan Password" id="password" name="password" required>
                        </div>

                        <div class="d-flex mb-5 align-items-center">
                            <label class="control control--checkbox mb-0">
                                <span class="caption">Remember me</span>
                                <input type="checkbox" checked="checked"/>
                                <div class="control__indicator"></div>
                            </label>
                            <span class="ml-auto">
                                <a href="{{ route('auth.forgot_pw.index') }}" class="forgot-pass">Forgot Password</a>
                            </span>
                        </div>

                        <input type="submit" value="Log In" class="btn btn-block btn-primary">
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Login Scripts -->
    <script src="{{URL::to('/')}}/assets/js/login/jquery-3.3.1.min.js"></script>
    <script src="{{URL::to('/')}}/assets/js/login/popper.min.js"></script>
    <script src="{{URL::to('/')}}/assets/js/login/bootstrap.min.js"></script>
    <script src="{{URL::to('/')}}/assets/js/login/main.js"></script>
</body>
</html>
