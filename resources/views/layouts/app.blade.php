<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    {{--
        Main application layout (Blade)
        - Contains the site shell: head, navbar, main content slot and footer.
        قالب رئيسي للتطبيق يحوي الشريط العلوي (Navbar) ومكان المحتوى.
    --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Step by Step')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    @php
        $__current_route = \Illuminate\Support\Facades\Route::currentRouteName();
    @endphp
    @if($__current_route === 'tasks.show')
        <link rel="stylesheet" href="{{ asset('assets/css/tasks-show.css') }}">
    @endif
    @stack('styles')
</head>
<body>
    <!-- Navbar (skip if no_navbar section is present) -->
    @unless(View::hasSection('no_navbar'))
        @hasSection('custom_navbar')
            @yield('custom_navbar')
        @else
            <!-- Default navbar -->
            <nav class="navbar">
            <div class="navbar-container">
                <a href="{{ route('home') }}" class="navbar-logo">Step by Step</a>
                <ul class="navbar-links">
                    <li>
                        @if($__current_route === 'home')
                            <a href="#features">المميزات</a>
                        @else
                            <a href="{{ route('home') }}#features">المميزات</a>
                        @endif
                    </li>

                    @guest
                        <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
                        <li><a href="{{ route('register') }}">إنشاء حساب</a></li>
                    @endguest

                    @auth
                        <li><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li>
                                    @hasSection('link_logout')
                                        <a href="#" id="logout-link" class="btn btn-glass">تسجيل الخروج</a>
                                    @else
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-glass">تسجيل الخروج</button>
                                        </form>
                                    @endif
                        </li>
                    @endauth
                </ul>
                <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme">🌙</button>
            </div>
        </nav>
        @endif
    @endunless

    <!-- محتوى الصفحة -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">جميع الحقوق محفوظة.© 2025 MADE BY BIN SAEED.</p>
        </div>
    </footer>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <!-- Toast container and helper -->
    <div id="global-toast" aria-live="polite" style="position: fixed; z-index: 60; inset: auto 1rem 1rem auto; pointer-events: none;"></div>

    <script>
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('global-toast');
            if (!container) return;

            // Add emoji prefix based on type
            const icons = {
                'error': '❌ ',
                'success': '✅ ',
                'info': 'ℹ️ '
            };
            const displayMessage = (icons[type] || '') + message;

            // Error messages stay longer (4.5 seconds)
            const finalDuration = type === 'error' ? 4500 : duration;

            const toast = document.createElement('div');
            toast.className = 'sb-toast sb-toast-' + type;
            toast.style.pointerEvents = 'auto';
            toast.style.marginTop = '0.5rem';
            toast.style.padding = '0.7rem 1rem';
            toast.style.borderRadius = '8px';
            toast.style.background = type === 'error' ? '#dc2626' : (type === 'success' ? '#16a34a' : '#1f2937');
            toast.style.color = '#fff';
            toast.style.boxShadow = type === 'error' ? '0 8px 20px rgba(220,38,38,0.3)' : '0 6px 18px rgba(0,0,0,0.18)';
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 180ms ease, transform 180ms ease';
            toast.style.fontWeight = type === 'error' ? '600' : '500';
            toast.textContent = displayMessage;

            container.appendChild(toast);
            // show
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            setTimeout(() => {
                // hide
                toast.style.opacity = '0';
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            }, finalDuration);
        }
    </script>

    @if(View::hasSection('link_logout'))
        <script>
            // handle logout via fetch POST when using link instead of form
            document.addEventListener('DOMContentLoaded', function() {
                const logoutLink = document.getElementById('logout-link');
                if (!logoutLink) return;
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch("{{ route('logout') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).then(() => {
                        // redirect to home after logout
                        window.location.href = "{{ route('home') }}";
                    }).catch(() => {
                        showToast('فشل تسجيل الخروج. حاول مرة أخرى.', 'error');
                    });
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
