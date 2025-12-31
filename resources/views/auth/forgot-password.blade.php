@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
@endpush

@section('content')
    <section class="login-section">
        <div class="container">
            <div class="login-card glass">
                <div class="login-logo glass">
                    <img src="{{ asset('assets/img/Screenshot 2025-12-22 232017.png') }}" alt="Step by Step Logo" class="logo-image">
                </div>

                <h2 class="login-title">نسيت كلمة المرور؟</h2>
                <p class="login-subtitle">لا مشكلة — أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.</p>

                @if(session('status'))
                    <div class="alert alert-success glass" style="margin:1rem 0;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="w-full" style="text-align:right;">
                    @csrf

                    <div style="text-align:right; margin-top:0.5rem;">
                        <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                        <x-text-input id="email" class="form-input mt-2 w-full px-4 py-2 rounded-lg" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm error-text" />
                    </div>

                    <div style="margin-top:1.25rem;display:flex;gap:0.75rem;flex-direction:row;justify-content:space-between;">
                        <a href="{{ route('login') }}" class="btn btn-outline" style="flex:1;text-align:center;">العودة للدخول</a>
                        <button type="submit" class="btn btn-primary" style="flex:1;">إرسال رابط إعادة التعيين</button>
                    </div>
                </form>

                <div class="login-footer" style="margin-top:1.25rem;text-align:center;">
                    <p>هل لا تملك حساباً؟ <a href="{{ route('register') }}" style="color:var(--accent-color);">إنشاء حساب</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
