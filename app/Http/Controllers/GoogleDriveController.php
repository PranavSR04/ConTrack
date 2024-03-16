<?php

namespace App\Http\Controllers;

use App\Models\Contracts;
use App\Models\MSAs;
use App\ServiceInterfaces\GoogleDriveInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleDriveController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveInterface $googleDriveService){
        $this->googleDriveService = $googleDriveService;
    }

    // Function to generate token
    public function token()
    {
        return $this->googleDriveService->token();
    }

    // Function to store data in drive and return a link
    public function store(Request $request)
    {
        return $this->googleDriveService->store($request);
    }
}
