<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * AppServiceProvider
     * --------------------------------------------------------
     * Arabic: موفر خدمات التطبيق العام لتسجيل وتهيئة الاعتمادات/الإعدادات.
     * English: Application service provider — use to register and bootstrap app services.
     * Current boot registers a Vite prefetch optimization.
     */
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register bindings or services here if needed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Optimize Vite by prefetching a small set of modules.
        Vite::prefetch(concurrency: 3);
    }
}
