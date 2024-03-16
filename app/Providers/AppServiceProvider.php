<?php

namespace App\Providers;

use App\Services\ActivityLogInsertService;
use App\ServiceInterfaces\ActivityLogInsertInterface;
use App\ServiceInterfaces\ContractInterface;
use App\ServiceInterfaces\ExperionEmployeesInterface;
use App\ServiceInterfaces\GoogleDriveInterface;
use App\ServiceInterfaces\NotificationInterface;
use App\ServiceInterfaces\MsaInterface;
use App\ServiceInterfaces\RevenueProjectionInterface;
use App\ServiceInterfaces\UserInterface;
use App\Services\ContractService;
use App\Services\MsaService;
use App\Services\ExperionEmployeesService;
use App\Services\GoogleDriveService;
use App\Services\NotificationService;
use App\Services\RevenueProjectionService;
use App\Services\UserService;
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
            UserInterface::class,
            UserService::class
        );
            $this->app->bind(
                MsaInterface::class,
                MsaService::class
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
