<?php

namespace App\Providers;

use App\Models\WebResource;
use Filament\View\PanelsRenderHook;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\MidtransPaymentService;
use App\Contracts\PaymentServiceInterface;
use Filament\Support\Facades\FilamentView;

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
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): string => Blade::render('<livewire:action-shortcuts />'),
        );
        
        Schema::defaultStringLength(191);

        Blade::include('components.page-header', 'PageHeader');

        Paginator::useBootstrap();

        Paginator::defaultView('vendor.pagination.custom');
        
        View::composer('components.header', function($query)
        {
            $query->with('menus', config('menus')); 
        });

        View::composer('layouts.app', function($query)
        {
            $query->with('webResource', WebResource::with('media')->first()); 
        });
    }
}
