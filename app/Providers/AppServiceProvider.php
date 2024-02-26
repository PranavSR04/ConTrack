<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
