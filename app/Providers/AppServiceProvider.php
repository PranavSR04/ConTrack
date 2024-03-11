<?php

namespace App\Providers;

use App\ServiceInterfaces\ContractInterface;
use App\ServiceInterfaces\RevenueProjectionInterface;
use App\ServiceInterfaces\UserInterface;
use App\Services\ContractService;
use App\Services\RevenueProjectionService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            RevenueProjectionInterface::class,
            RevenueProjectionService::class
        );
        $this->app->bind(
            ContractInterface::class,
            ContractService::class
            );

        $this->app->bind(
            UserInterface::class,
            UserService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Validator::extend('sum_equals', function ($attribute, $value, $parameters, $validator) {
            return array_sum(data_get($validator->getData(), $parameters[0])) == $parameters[1];
        });
    }
}
