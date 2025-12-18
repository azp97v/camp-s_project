<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=josefin-slab:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Vite Assets -->
        @vite(['resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div id="app" class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="glass border-b border-slate-200/50 shadow-sm sticky top-16 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-purple-600">{{ $header }}</h1>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="min-h-[calc(100vh-200px)]">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="mt-20 py-12 text-center text-sm text-slate-500 border-t border-slate-200/30 glass">
                <div class="max-w-7xl mx-auto px-4">
                    <p class="mb-2">© {{ date('Y') }} {{ config('app.name') }} • جميع الحقوق محفوظة</p>
                    <p>تم التطوير بعناية فائقة • MADE BY BIN SAEED</p>
                </div>
            </footer>
        </div>
    </body>
</html>
