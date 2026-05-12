<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DirectoryTree\Authorization\Authorization;

use App\Models\Payout;
use App\Models\Invoice;
use App\Observers\PayoutObserver;
use App\Observers\InvoiceCreatedObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Authorization::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Payout Model Observer
        Payout::observe(PayoutObserver::class);

        // Register Invoice Model Observer
        Invoice::observe(InvoiceCreatedObserver::class);
    }
}
