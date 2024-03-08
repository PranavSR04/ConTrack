<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCheckController extends Controller
{
    public function notauth(){
        return response()->json(["Not Authorized User"],401);
    }
}
