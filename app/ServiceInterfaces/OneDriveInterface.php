<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface OneDriveInterface
{
    // Function to generate token
    public function token();

    // Function to store data in drive and return a link
    public function store(Request $request);
}