{{--
    OTP Success Page
    --------------------------------------------------------
    تظهر بعد نجاح التحقق من البريد وتفعيل الحساب.
--}}


@extends('layouts.app')

@section('no_navbar')
@endsection

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

                <h2 class="login-title">تهانينا — تم تفعيل حسابك!</h2>
                <p class="login-subtitle">أصبح الآن بإمكانك الوصول إلى جميع مميزات Step by Step.</p>

                @if(auth()->check())
                    <div class="alert alert-error glass" style="background-color:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.25);margin:1rem 0;">
                        <div class="alert-icon">🎉</div>
                        <div class="alert-content">
                            <p class="alert-title" style="color:#059669;">مرحباً {{ auth()->user()->name }} — تم تفعيل حسابك بنجاح</p>
                        </div>
                    </div>
                @endif

                <div style="margin-top:1.5rem;display:flex;gap:0.75rem;flex-direction:column;">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-full">اذهب إلى لوحة التحكم</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-full">تسجيل الدخول الآن</a>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-full" style="background:transparent;color:var(--accent-color);border:2px solid var(--accent-color);">العودة إلى الصفحة الرئيسية</a>
                </div>

                <div class="login-footer" style="margin-top:1.5rem;">
                    <p>شكراً لانضمامك إلى <strong>Step by Step</strong></p>
                </div>
            </div>
        </div>
    </section>

@endsection
