<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
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

 public function insertRole()   
 {
    $role2=new Roles([
        "role_name"=>"Admin",
        "role_access"=>"Can edit and view contracts",
        "is_active"=>true,
    ]);
    $role=new Roles([
       "role_name"=>"Reader",
        "role_access"=>"Can view contracts",
        "is_active"=>false,   
    ]);
    $role->save();
    $role2->save();
    return "Role created successfully!!";
 }
 public function getRole()
 {
    $role_data=Roles::all();
    return response()->json($role_data);
 }
}
