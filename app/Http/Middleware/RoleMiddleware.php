<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request,$role=null): Response
    {
        $user = Auth::user();
        $contrackUser = User::where("experion_id", $user->id )->where("is_active", 1)->first(); 
        // Check if the user is authenticated
        if (!$contrackUser) {
            return response()->json(['error' => 'Unauthorized, Access Denied'], 401);
        }
        
        // Check if the user has the required role
        if($role!==null){
        switch ($role) {
            case 'super_admin':
                if ($contrackUser->role_id !== 1) {
                    return response()->json(['error' => 'Forbidden, Super Admin Access Only '], 403);
                }
                break;
            case 'super_admin-admin':
                if ($contrackUser->role_id !== 2 && $contrackUser->role_id !== 1) {
                    return response()->json(['error' => 'Forbidden, Admin Access Only'], 403);
                }
                break;
            default:
                return response()->json(['error' => 'Invalid role'], 400);
        }
    }
        return ($request);
    }
}
