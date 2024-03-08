<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;

interface AddendumInterface{
    /**
     * Generate a google api token.
     */
    public function token();

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$contractId);
}