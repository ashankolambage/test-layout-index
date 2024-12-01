<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ConcessionRepositoryInterface;
use App\Repositories\ConcessionRepository;

class RepositoryServiceProvider extends AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ConcessionRepositoryInterface::class, ConcessionRepository::class);
    }
    
    public function boot(): void
    {
        //
    }
}
