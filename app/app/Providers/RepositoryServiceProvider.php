<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ConcessionRepositoryInterface;
use App\Repositories\ConcessionRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;

class RepositoryServiceProvider extends AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ConcessionRepositoryInterface::class, ConcessionRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }
    
    public function boot(): void
    {
        //
    }
}
