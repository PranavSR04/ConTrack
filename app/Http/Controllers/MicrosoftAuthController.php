<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExperionEmployees;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use myPHPnotes\Microsoft\Auth;
use myPHPnotes\Microsoft\Models\User;
use App\Models\User as ContrackUser;


class MicrosoftAuthController extends Controller
{
    public function loginAzure(Request $request)
    {
        $microsoft = new Auth(env('TENANT_ID'), env('CLIENT_ID'), env('CLIENT_SECRET'), env('CALLBACK_URL'), ["User.Read"]);
        $accessToken = $request->access_token;
        $microsoft->setAccessToken($accessToken);

        $user = new User;
        // return response()->json([$user]);
        if (!$user) {
            return response()->json(['error' => 'Invalid Email or Password']);
        }

        $email_id = $user->data->getUserPrincipalName();
        // return response()->json([$email_id]);
        // $u_id = $user->data->getId();
        $expuser = ExperionEmployees::where('email_id', $email_id)->first();
        // return response()->json([$expuser]);


        if (!$expuser) {
            return response()->json(['error' => 'Invalid Email or Password. You may not be a registered employee. Please check your credentials or contact your administrator.']);

        }
        $credentials = [
            'email_id' => $email_id
        ];
        $jwt_token = auth()->attempt($credentials);
        // return response()->json([$jwt_token]);
        return $this->handleRoleCheck($jwt_token);

    }


    protected function createNewToken($token, $contrackUser,$user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 3600,
            'user' => $user,
            'contrackUser' => $contrackUser,

        ]);
    }
    protected function handleRoleCheck($token)
    {

        $user = auth()->user();
        // $expuser = ExperionEmployees::where('email_id', $user->email_id)->first();

        $contrackUser = ContrackUser::where("experion_id", $user->id)->where("is_active", 1)->first();

        // Check if the user is authenticated
        if (!$contrackUser) {
            return response()->json(['error' => 'Access Denied. You do not have permission to access this application within the organization'], 401);
        } else {
            return $this->createNewToken($token, $contrackUser,$user);

        }

    }


}