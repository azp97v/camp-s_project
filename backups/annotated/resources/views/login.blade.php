@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
@endpush

@section('content')

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-card glass">
                <!-- Logo -->
                <div class="login-logo glass">
                    <img src="{{ asset('assets/img/Screenshot 2025-12-22 232017.png') }}" alt="Step by Step Logo" class="logo-image">
                </div>

                <h2 class="login-title">مرحباً بعودتك</h2>
                <p class="login-subtitle">سجل دخولك لمتابعة أهدافك</p>

                <!-- Login Form -->
                <form class="login-form" method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input
            type="email"
            id="email"
            name="email"
            class="form-input"
            placeholder="example@email.com"
            required
        >
    </div>

    <div class="form-group">
        <label for="password" class="form-label">كلمة المرور</label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="••••••••"
            required
        >
    </div>

    <button type="submit" class="btn btn-primary btn-full">تسجيل الدخول</button>
</form>

                <!-- Register Link -->
                <div class="login-footer">
                    <p>ليس لديك حساب؟ <a href="{{ route('register') }}" class="register-link">إنشاء حساب جديد</a></p>
                </div>
            </div>
        </div>
    </section>

@endsection
