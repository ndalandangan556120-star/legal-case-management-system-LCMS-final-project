<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LCMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @php
            $manifestPath = public_path('build/manifest.json');
            $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
            $cssAsset = $manifest['resources/css/app.css']['file'] ?? null;
            $jsAsset = $manifest['resources/js/app.js']['file'] ?? null;
        @endphp
        @if($manifest && $cssAsset && $jsAsset)
            <link rel="stylesheet" href="{{ asset('build/' . $cssAsset) }}">
            <script type="module" src="{{ asset('build/' . $jsAsset) }}"></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        
        <style>
            body {
                margin: 0;
                padding: 0;
            }
            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, rgba(0, 102, 102, 0.9) 0%, rgba(0, 153, 153, 0.9) 100%);
                background-image: linear-gradient(135deg, rgba(0, 102, 102, 0.85) 0%, rgba(0, 153, 153, 0.85) 100%), url('{{ asset('images/background.png') }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                font-family: 'Figtree', sans-serif;
            }
            .auth-modal {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                padding: 50px 40px;
                width: 90%;
                max-width: 450px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .auth-header {
                text-align: center;
                margin-bottom: 40px;
            }
            .auth-title {
                font-size: 32px;
                font-weight: 600;
                color: #006666;
                margin: 0 0 10px 0;
            }
            .auth-subtitle {
                font-size: 14px;
                color: #999;
                margin: 0;
            }
            .icon-row {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-top: 25px;
                opacity: 0.6;
            }
            .icon-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #00999933;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-modal">
                <div class="auth-header">
                    <h1 class="auth-title">Welcome Back</h1>
                    <p class="auth-subtitle">Legal Case Management System</p>
                    <div class="icon-row">
                        <div class="icon-circle">⚖️</div>
                        <div class="icon-circle">📋</div>
                        <div class="icon-circle">🏛️</div>
                    </div>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
