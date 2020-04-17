<?php 

namespace App\Providers;

use App\Repositories\CalculationRepository;
use App\Repositories\Interfaces\CalculationRepositoryInterface;
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
            CalculationRepository::class
        );
    }
}