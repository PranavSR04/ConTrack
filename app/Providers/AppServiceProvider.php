<?php

namespace App\Providers;

use App\Http\Controllers\ActivityLogInsertService;
use App\ServiceInterfaces\ActivityLogInsertInterface;
use App\ServiceInterfaces\ContractInterface;
use App\ServiceInterfaces\ExperionEmployeesInterface;
use App\ServiceInterfaces\GoogleDriveInterface;
use App\ServiceInterfaces\NotificationInterface;
use App\ServiceInterfaces\RevenueProjectionInterface;
use App\Services\ContractService;
use App\Services\ExperionEmployeesService;
use App\Services\GoogleDriveService;
use App\Services\NotificationService;
use App\Services\RevenueProjectionService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;


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
            GoogleDriveInterface::class,
            GoogleDriveService::class
        );
        $this->app->bind(
            ExperionEmployeesInterface::class,
            ExperionEmployeesService::class
        );
        $this->app->bind(
            NotificationInterface::class,
            NotificationService::class
        );
        $this->app->bind(
            ActivityLogInsertInterface::class,
            ActivityLogInsertService::class
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
