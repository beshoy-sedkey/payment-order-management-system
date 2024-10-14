<?php

namespace App\Providers;

use App\Services\Repository\Contracts\OrderRepositoryInterface;
use App\Services\Repository\Contracts\PaymentRepositoryInterface;
use App\Services\Repository\Contracts\UserRepositoryInterface;
use App\Services\Repository\Elequonet\OrderRepository;
use App\Services\Repository\Elequonet\PaymentRepository;
use App\Services\Repository\Elequonet\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class , UserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class , OrderRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class , PaymentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
