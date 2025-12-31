{{--
    Register Page
    --------------------------------------------------------
    صفحة التسجيل لإنشاء حساب مستخدم جديد.
    English: Registration page view — renders the signup form and styles. No behavior changes.
--}}
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
@endpush

@section('content')

    <!-- Register Section -->
    <section class="register-section">
        <div class="container">
            <div class="register-card glass">
                <!-- Logo -->
                <div class="register-logo glass">
                    <img src="{{ asset('assets/img/Screenshot 2025-12-22 232017.png') }}" alt="Step by Step Logo" class="logo-image">
                </div>

                <h2 class="register-title">انضم إلى Step by Step</h2>
                <p class="register-subtitle">ابدأ رحلتك نحو تحقيق أهدافك</p>

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

                <!-- Register Form -->
<form class="register-form" method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label for="name" class="form-label">الاسم الكامل</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-input @error('name') input-error @enderror"
            placeholder="أدخل اسمك الكامل"
            value="{{ old('name') }}"
            required
        >
        @error('name')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

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
            minlength="8"
        >
        @error('password')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-input @error('password_confirmation') input-error @enderror"
            placeholder="••••••••"
            value="{{ old('password_confirmation') }}"
            required
            minlength="8"
        >
        @error('password_confirmation')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary btn-full">إنشاء حساب</button>
</form>

                <!-- Login Link -->
                <div class="register-footer">
                    <p>لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="login-link">تسجيل الدخول</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
