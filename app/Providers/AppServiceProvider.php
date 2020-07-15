<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    LeasingRequest,
    AgentCommission
};
use App\Observers\{
    LeasingRequestObserver,
    AgentCommissionObserver
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LeasingRequest::observe(LeasingRequestObserver::class);
        AgentCommission::observe(AgentCommissionObserver::class);
        Schema::defaultStringLength(191);
    }
}
