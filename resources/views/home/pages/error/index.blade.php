<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | Empatra Digitech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Floating particles animation */
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        .error-container {
            text-align: center;
            color: white;
            z-index: 10;
            padding: 20px;
            max-width: 700px;
        }

        .error-code {
            position: relative;
            margin-bottom: 30px;
        }

        .error-code h1 {
            font-size: 180px;
            font-weight: 700;
            line-height: 1;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: glitch 3s infinite;
            position: relative;
            display: inline-block;
        }

        @keyframes glitch {
            0%, 100% {
                transform: translate(0);
            }
            20% {
                transform: translate(-2px, 2px);
            }
            40% {
                transform: translate(-2px, -2px);
            }
            60% {
                transform: translate(2px, 2px);
            }
            80% {
                transform: translate(2px, -2px);
            }
        }

        /* Animated search icon in the middle zero */
        .search-icon {
            position: absolute;
            width: 80px;
            height: 80px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            animation: searchRotate 4s ease-in-out infinite;
        }

        .search-circle {
            width: 50px;
            height: 50px;
            border: 6px solid white;
            border-radius: 50%;
            position: absolute;
            top: 5px;
            left: 5px;
        }

        .search-handle {
            width: 6px;
            height: 30px;
            background: white;
            position: absolute;
            bottom: 0;
            right: 10px;
            transform: rotate(45deg);
            border-radius: 3px;
        }

        @keyframes searchRotate {
            0%, 100% {
                transform: translate(-50%, -50%) rotate(0deg) scale(1);
            }
            50% {
                transform: translate(-50%, -50%) rotate(360deg) scale(1.2);
            }
        }

        .error-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .error-message {
            font-size: 16px;
            font-weight: 300;
            margin-bottom: 35px;
            line-height: 1.6;
            opacity: 0.95;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-home {
            display: inline-block;
            padding: 15px 40px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease-out 0.6s both;
            position: relative;
            overflow: hidden;
        }

        .btn-home:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-home:hover:before {
            width: 300px;
            height: 300px;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .btn-home span {
            position: relative;
            z-index: 1;
        }

        /* Responsive design */
        @media only screen and (max-width: 768px) {
            .error-code h1 {
                font-size: 120px;
            }

            .search-icon {
                width: 50px;
                height: 50px;
            }

            .search-circle {
                width: 30px;
                height: 30px;
                border-width: 4px;
            }

            .search-handle {
                width: 4px;
                height: 20px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-message {
                font-size: 14px;
            }

            .btn-home {
                padding: 12px 30px;
                font-size: 14px;
            }
        }

        @media only screen and (max-width: 480px) {
            .error-code h1 {
                font-size: 80px;
            }

            .search-icon {
                width: 35px;
                height: 35px;
            }

            .search-circle {
                width: 20px;
                height: 20px;
                border-width: 3px;
            }

            .search-handle {
                width: 3px;
                height: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating particles -->
    <script>
        // Create floating particles
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.width = Math.random() * 10 + 5 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = Math.random() * 10 + 10 + 's';
            document.body.appendChild(particle);
        }
    </script>

    <div class="error-container">
        <div class="error-code">
            <h1>
                4
                <div class="search-icon">
                    <div class="search-circle"></div>
                    <div class="search-handle"></div>
                </div>
                4
            </h1>
        </div>

        <h2 class="error-title">Ups! Halaman Tidak Ditemukan</h2>
        <p class="error-message">
            Sepertinya halaman yang Anda cari sedang bersembunyi.
            Mungkin sudah dipindahkan atau tidak pernah ada di sini.
        </p>

        <a href="{{route('home.home.index')}}" class="btn-home">
            <span>← Kembali ke Beranda</span>
        </a>
    </div>
</body>
</html>
