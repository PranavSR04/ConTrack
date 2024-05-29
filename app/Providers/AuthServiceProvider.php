<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Extensions\CustomJwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        Auth::extend('custom-jwt', function ($app, $name, array $config) {
            $jwt = $app->make(JWT::class);
            $provider = Auth::createUserProvider($config['provider']);
            $request = $app->make(Request::class);

            return new CustomJwtGuard($jwt, $provider, $request);
        });
    }
}
