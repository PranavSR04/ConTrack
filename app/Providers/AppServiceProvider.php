<?php

namespace App\Providers;
use App\ServiceInterfaces\RevenueProjectionInterface;
use App\Services\RevenueProjectionService;
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
