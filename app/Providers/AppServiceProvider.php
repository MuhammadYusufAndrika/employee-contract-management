<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;

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
        // Share expiring contracts count with all views for the notification badge
        View::composer('*', function ($view) {
            try {
                if (Auth::check()) {
                    $expiringCount = Contract::expiringWithinDays(30)->count();
                    $view->with('globalExpiringCount', $expiringCount);
                }
            } catch (\Exception $e) {
                // Silently handle any errors during view composition
            }
        });
    }
}
