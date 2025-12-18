<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - إدارة أهدافك ومهامك</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=josefin-slab:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Vite Assets -->
        @vite(['resources/js/app.js'])

        <style>
            .hero-gradient {
                background: linear-gradient(135deg, #f0f4f9 0%, #e8f0f7 50%, #f9f5ff 100%);
                background-attachment: fixed;
            }

            .feature-icon {
                width: 64px;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                animation: float 3s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="antialiased">
        <!-- Navigation -->
        <nav class="glass border-b border-slate-200/50 sticky top-0 z-50 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-sky-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                            📋
                        </div>
                        <span class="hidden sm:inline ms-3 font-bold text-lg text-slate-800">{{ config('app.name') }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">الذهاب للداشبورد</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-ghost">تسجيل الدخول</a>
                            <a href="{{ route('register') }}" class="btn-primary">إنشاء حساب</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-gradient pt-20 pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-load">
                    <!-- Left Content -->
                    <div>
                        <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                            أدر أهدافك <span class="text-gradient">بذكاء</span> 🎯
                        </h1>

                        <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                            منصة قوية وسهلة الاستخدام لتتبع أهدافك ومهامك. خطط بفعالية، نفذ بكفاءة، وحقق نجاحاتك.
                        </p>

                        <div class="flex gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-primary text-lg px-8 py-3">
                                    ابدأ الآن ➜
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-3">
                                    إنشاء حساب مجاني
                                </a>
                                <a href="{{ route('login') }}" class="btn-ghost text-lg px-8 py-3">
                                    هل لديك حساب؟
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Right Visual -->
                    <div class="relative">
                        <div class="glass rounded-2xl p-8 shadow-glass-lg">
                            <div class="space-y-6">
                                <div class="animate-on-load" style="animation-delay: 0.2s">
                                    <div class="flex items-center gap-4 mb-2">
                                        <span class="badge badge-success">✓ مكتملة</span>
                                    </div>
                                    <div class="progress-outer">
                                        <div class="progress-inner" style="width: 100%"></div>
                                    </div>
                                    <p class="text-slate-600 mt-2">الهدف الأول • 100%</p>
                                </div>

                                <div class="animate-on-load" style="animation-delay: 0.3s">
                                    <div class="flex items-center gap-4 mb-2">
                                        <span class="badge">⏳ جاري</span>
                                    </div>
                                    <div class="progress-outer">
                                        <div class="progress-inner" style="width: 65%"></div>
                                    </div>
                                    <p class="text-slate-600 mt-2">الهدف الثاني • 65%</p>
                                </div>

                                <div class="animate-on-load" style="animation-delay: 0.4s">
                                    <div class="flex items-center gap-4 mb-2">
                                        <span class="badge">📋 قريباً</span>
                                    </div>
                                    <div class="progress-outer">
                                        <div class="progress-inner" style="width: 30%"></div>
                                    </div>
                                    <p class="text-slate-600 mt-2">الهدف الثالث • 30%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-24 bg-white/30 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 animate-on-load">
                    <h2 class="text-4xl font-bold mb-4">المميزات الرئيسية</h2>
                    <p class="text-xl text-slate-600">كل ما تحتاجه لتحقيق أهدافك في مكان واحد</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="card animate-on-load" style="animation-delay: 0.1s">
                        <div class="feature-icon mb-4">🎯</div>
                        <h3 class="card-header">إدارة الأهداف</h3>
                        <p class="card-content">
                            ضع أهدافك بوضوح وتابع تقدمك بشكل مستمر. قسّم أهدافك الكبيرة إلى مهام صغيرة قابلة للإنجاز.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="card animate-on-load" style="animation-delay: 0.2s">
                        <div class="feature-icon mb-4">⏱️</div>
                        <h3 class="card-header">تتبع الوقت</h3>
                        <p class="card-content">
                            مؤقت مدمج لتتبع الوقت المستغرق في كل مهمة. افهم أين يذهب وقتك بالضبط.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="card animate-on-load" style="animation-delay: 0.3s">
                        <div class="feature-icon mb-4">📊</div>
                        <h3 class="card-header">إحصائيات مفصلة</h3>
                        <p class="card-content">
                            رؤية شاملة لأدائك ومستويات إنجازك. تحسن مستمر بناءً على البيانات الفعلية.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="card animate-on-load" style="animation-delay: 0.4s">
                        <div class="feature-icon mb-4">📱</div>
                        <h3 class="card-header">واجهة سهلة</h3>
                        <p class="card-content">
                            تصميم بديهي وجميل. استخدم المنصة على أي جهاز بكل سهولة.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="card animate-on-load" style="animation-delay: 0.5s">
                        <div class="feature-icon mb-4">🔒</div>
                        <h3 class="card-header">آمن وموثوق</h3>
                        <p class="card-content">
                            بيانات آمنة وملفوظة. تقنيات حديثة لحماية معلوماتك الشخصية.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="card animate-on-load" style="animation-delay: 0.6s">
                        <div class="feature-icon mb-4">⚡</div>
                        <h3 class="card-header">سريع ودقيق</h3>
                        <p class="card-content">
                            أداء سريع وموثوقية عالية. تجربة استخدام سلسة بدون تأخير.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-24 glass">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center animate-on-load">
                    <div>
                        <h4 class="text-5xl font-bold text-gradient mb-2">1000+</h4>
                        <p class="text-slate-600 text-lg">مستخدم نشط</p>
                    </div>
                    <div>
                        <h4 class="text-5xl font-bold text-gradient mb-2">10K+</h4>
                        <p class="text-slate-600 text-lg">هدف تم إنجازه</p>
                    </div>
                    <div>
                        <h4 class="text-5xl font-bold text-gradient mb-2">50K+</h4>
                        <p class="text-slate-600 text-lg">مهمة مكتملة</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-gradient-to-r from-sky-400/10 to-purple-600/10">
            <div class="max-w-4xl mx-auto px-4 text-center animate-on-load">
                <h2 class="text-4xl font-bold mb-6">هل أنت مستعد لتحقيق أهدافك؟</h2>
                <p class="text-xl text-slate-600 mb-8">
                    ابدأ رحلتك نحو النجاح اليوم. مجاني وبدون تعقيدات!
                </p>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-lg px-10 py-4">
                        اذهب للداشبورد الآن ➜
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary text-lg px-10 py-4">
                        إنشاء حساب مجاني ➜
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 text-center text-slate-600 border-t border-slate-200/30 glass">
            <div class="max-w-7xl mx-auto px-4">
                <p class="mb-2">© {{ date('Y') }} {{ config('app.name') }} • جميع الحقوق محفوظة</p>
                <p class="text-sm">تم التطوير بعناية فائقة • MADE BY BIN SAEED 💫</p>
            </div>
        </footer>

        <!-- Animation Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const elements = document.querySelectorAll('.animate-on-load');
                elements.forEach(el => {
                    el.classList.add('slide-in-left');
                });
            });
        </script>
    </body>
</html>
