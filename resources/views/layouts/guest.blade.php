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
    <body class="text-gray-900 antialiased min-h-screen">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200">
            <!-- Header Logo -->
            <div class="mb-8 animate-on-load">
                <a href="/" class="inline-block">
                    <div class="w-16 h-16 rounded-full glass flex items-center justify-center">
                        <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-purple-600">📋</span>
                    </div>
                </a>
            </div>

            <!-- Main Form Container -->
            <div class="w-full sm:max-w-md px-6 py-8 glass rounded-2xl overflow-hidden shadow-2xl animate-on-load" style="animation-delay: 0.1s">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-sm text-slate-500 animate-on-load" style="animation-delay: 0.2s">
                © {{ date('Y') }} {{ config('app.name') }} • MADE BY BIN SAEED
            </p>
        </div>
    </body>
</html>
