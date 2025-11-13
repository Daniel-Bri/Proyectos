<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RoleMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ELIMINA esta línea:
        // parent::boot();
        
        // 👇 Aquí registramos manualmente el middleware
        Route::aliasMiddleware('role', RoleMiddleware::class);
    }
}