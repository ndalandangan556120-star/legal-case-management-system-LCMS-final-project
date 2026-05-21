<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LCMS - Legal Case Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                margin: 0;
                padding: 0;
            }
            .hero-section {
                position: relative;
                height: 100vh;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/background.png') }}');
                background-size: cover;
                background-position: center;
                color: white;
                text-align: center;
            }
            .nav-links {
                position: absolute;
                top: 20px;
                right: 20px;
                z-index: 10;
            }
            .nav-links a {
                color: white;
                text-decoration: none;
                margin-left: 20px;
                font-weight: 600;
                padding: 10px 20px;
                border: 1px solid white;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            .nav-links a:hover {
                background-color: white;
                color: black;
            }
            .content {
                max-width: 800px;
                padding: 20px;
            }
            h1 {
                font-size: 3.5rem;
                margin-bottom: 1rem;
            }
            p {
                font-size: 1.25rem;
                margin-bottom: 2rem;
            }
            .btn-primary {
                background-color: #ef4444;
                color: white;
                padding: 15px 30px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1.1rem;
                transition: background-color 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #dc2626;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="hero-section">
            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <h1>Legal Case Management System</h1>
                <p>Efficiently manage your legal cases, clients, and hearings with our comprehensive platform.</p>
                @guest
                    <a href="{{ route('login') }}" class="btn-primary">Get Started</a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Go to Dashboard</a>
                @endguest
            </div>
        </div>
    </body>
</html>
