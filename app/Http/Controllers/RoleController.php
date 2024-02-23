<?php

namespace App\Http\Controllers;
use App\Models\Roles;

class RoleController extends Controller
{
 public function insertRole()
{
    try {
        $role2 = new Roles([
            "role_name" => "Admin",
            "role_access" => "Can edit and view contracts",
            "is_active" => true,
        ]);
        $role = new Roles([
            "role_name" => "Reader",
            "role_access" => "Can view contracts",
            "is_active" => true,
        ]);

        
        $role->save();
        $role2->save();
        
        return "Role created successfully!!";
    } catch (\Exception $e) {
        return "Error occurred: " . $e->getMessage();
    }
}

public function getRole()
{
    try {
        $role_data = Roles::all();
        return response()->json($role_data);
    } catch (\Exception $e) {
        return response()->json(["error" => "Error occurred: " . $e->getMessage()], 500);
    }
}

}
 