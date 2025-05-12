<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;


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
        //
        Blade::if('canany', function ($abilities) {
            return app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($abilities);
        });

        View::composer('layouts.navigation', function ($view) {
            $pendingOrderCount = Order::where('order_status', 'in_process')->count();
            $view->with('pendingOrderCount', $pendingOrderCount);
        });
    }
}
