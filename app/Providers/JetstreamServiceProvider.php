<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * JetstreamServiceProvider
     * --------------------------------------------------------
     * Arabic: مزوّد Jetstream لإعداد صلاحيات واجهة برمجة التطبيقات وحذف المستخدمين.
     * English: Configures Jetstream permissions and bindings (e.g., delete user action).
     * No behavior changes; comments only.
     */
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register any Jetstream-specific services here.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        // Vite prefetch optimization
        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
