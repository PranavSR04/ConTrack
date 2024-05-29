<?php
// app/Extensions/CustomJwtGuard.php
namespace App\Extensions;

use Illuminate\Auth\SessionGuard;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWT;

class CustomJwtGuard extends JWTGuard
{ public function __construct(JWT $jwt, UserProvider $provider, Request $request)
    {
        parent::__construct($jwt, $provider, $request);
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $user = $this->provider->retrieveByCredentials(['email_id' => $credentials['email_id']]);

        if (!$user) {
            return false;
        }

        return $this->login($user);
    }

    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials(['email' => $credentials['email']]);
        return $user !== null;
    }
}
