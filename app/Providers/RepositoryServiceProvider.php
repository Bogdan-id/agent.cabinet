<?php 

namespace App\Providers;

use App\Repositories\{
    CalculationRepository,
    LeasingRequestRepository
};
use App\Repositories\Interfaces\{
    CalculationRepositoryInterface,
    LeasingRequestRepositoryInterface
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bind the interface to an implementation repository class
     */
    public function register()
    {
        $this->app->bind(
            CalculationRepositoryInterface::class,
            CalculationRepository::class,
            LeasingRequestRepositoryInterface::class,
            LeasingRequestRepository::class
        );
    }
}