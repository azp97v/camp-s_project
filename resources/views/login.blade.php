{{--
    Login Page
    --------------------------------------------------------
    صفحة تسجيل الدخول للمستخدم.
    English: Login page view — renders the login form and styles. No behavior changes.
--}}
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

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-error glass">
                        <div class="alert-icon">⚠️</div>
                        <div class="alert-content">
                            <p class="alert-title">خطأ في البيانات</p>
                            <ul class="alert-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form class="login-form login-form-large" method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input
            type="email"
            id="email"
            name="email"
            class="form-input @error('email') input-error @enderror"
            placeholder="example@email.com"
            value="{{ old('email') }}"
            required
        >
        @error('email')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="form-label">كلمة المرور</label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-input @error('password') input-error @enderror"
            placeholder="••••••••"
            required
        >
        @error('password')
            <span class="error-text">{{ $message }}</span>
        @enderror

        <!-- Forgot Password Link -->
        <div style="text-align: right; margin-top: 0.5rem;">
            <a href="{{ route('password.request') }}" class="forgot-password-link">
                هل نسيت كلمة المرور؟
            </a>
        </div>
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
