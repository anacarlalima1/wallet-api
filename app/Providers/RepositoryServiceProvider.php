<?php

namespace App\Providers;

use Domains\Wallet\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;
use Domains\Wallet\Repositories\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }
}
