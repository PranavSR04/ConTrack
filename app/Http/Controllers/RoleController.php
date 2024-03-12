<?php

namespace App\Http\Controllers;
use App\Models\Roles;

class RoleController extends Controller
{
 public function insertRole()
{
    try {
        $role1 = new Roles([
            "role_name" => "Super Admin",
            "role_access" => "Full Access",
            "is_active" => true,
        ]);
        $role2 = new Roles([
            "role_name" => "Admin",
            "role_access" => "View and Edit",
            "is_active" => true,
        ]);
        $role3 = new Roles([
            "role_name" => "Reader",
            "role_access" => "View Only",
            "is_active" => true,
        ]);
       

        $role1->save();
        $role2->save();
        $role3->save();

        
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
 