<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;

interface ExperionEmployeesInterface{
    /**
     * Display a listing of users based on the provided name.
     *
     * This function retrieves a list of users from the ExperionEmployees model
     * whose first_name, middle_name, or last_name partially match the provided name.
     * The request must include a 'name' parameter. Returns a JSON response
     * with the list of matching users or appropriate error messages.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request);
}