<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BranchRepository;
use App\Repositories\SaleRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use App\Repositories\Interfaces\SaleRepositoryInterface;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
$this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);
$this->app->bind(PurchaseRepositoryInterface::class, PurchaseRepository::class);
$this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
$this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
