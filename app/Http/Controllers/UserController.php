<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $usersData = [
            [
                'experion_id' => 1,
                'role_id' => 1,
                'user_name' => 'John Doe Smith',
                'user_mail' => 'john.smith@experionglobal.com',
                'user_designation' => 'Software Engineer',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 2,
                'role_id' => 2,
                'user_name' => 'Jane Alice Johnson',
                'user_mail' => 'jane.johnson@experionglobal.com',
                'user_designation' => 'UX Designer',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 3,
                'role_id' => 1,
                'user_name' => 'Michael Brown',
                'user_mail' => 'michael.brown@experionglobal.com',
                'user_designation' => 'Project Manager',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 4,
                'role_id' => 2,
                'user_name' => 'Emily Grace Taylor',
                'user_mail' => 'emily.taylor@experionglobal.com',
                'user_designation' => 'Quality Assurance',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 5,
                'role_id' => 1,
                'user_name' => 'William Jones',
                'user_mail' => 'william.jones@experionglobal.com',
                'user_designation' => 'Frontend Developer',
                'group_name' => '',
                'is_active' => true
            ]
        ];
    
        foreach ($usersData as $userData) {
            $user = new User($userData);
            $user->save();
        }
    
        return "5 meaningful users created successfully!";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
