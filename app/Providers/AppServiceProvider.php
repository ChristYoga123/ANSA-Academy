<?php

namespace App\Providers;

use App\Contracts\PaymentServiceInterface;
use App\Services\MidtransPaymentService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentServiceInterface::class, MidtransPaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Blade::include('components.page-header', 'PageHeader');

        Paginator::useBootstrap();

        Paginator::defaultView('vendor.pagination.custom');
        
        View::composer('components.header', function($query)
        {
            $query->with('menus', config('menus')); 
        });
    }
}
