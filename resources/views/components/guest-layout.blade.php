@props([])
@php
    $route = \Illuminate\Support\Facades\Route::currentRouteName();
    $noSiteBg = in_array($route, ['password.reset', 'verification.notice', 'password.request', 'otp.verify', 'otp.verify.post']);
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    @stack('styles')
    @if($noSiteBg)
    <style>
        /* Force plain background for standalone auth pages */
        body { background-image: none !important; background-color: #f7f7f8 !important; }
        .login-card { box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-radius: 12px; }
        form { min-height: auto; border-radius: 12px; }
    </style>
    @endif
</head>
<body class="{{ $noSiteBg ? 'plain-bg' : '' }}">
    <main>
        {{ $slot }}
    </main>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    @stack('scripts')
</body>
</html>
