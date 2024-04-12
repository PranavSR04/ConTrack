<?php

namespace App\Http\Controllers;
use App\ServiceInterfaces\OneDriveInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OneDriveController extends Controller
{
    protected $OneDriveService;

    public function __construct(OneDriveInterface $OneDriveService){
        $this->OneDriveService = $OneDriveService;
    }

    // Function to generate token
    public function token()
    {
        return $this->OneDriveService->token();
    }

    // Function to generate token
    public function store(Request $request)
    {
        return $this->OneDriveService->store($request);
    }
}
