<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\CarRepositoryInterface;
use App\Repositories\Contracts\TripRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\CarRepository;
use App\Repositories\UserRepository;
use App\Repositories\TripRepository;
use App\Repositories\ReviewRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class);

        $this->app->bind(
            CarRepositoryInterface::class,
            CarRepository::class);



        $this->app->bind(
            TripRepositoryInterface::class,
            TripRepository::class
        );
    


        $this->app->bind(
            ReviewRepositoryInterface::class,
            ReviewRepository::class
        );
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 
    }
}
