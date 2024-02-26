<?php

namespace App\Http\Controllers;

use App\Models\ExperionEmployees;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;




use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
   
 
    public function addUser(Request $request){
 
    try {
        // Validate the request data
        $request->validate([
            'experion_id' => 'required|exists:experion_employees,id',
            'role_id' => 'required|exists:roles,id', 
        ]);

        // Check if the user already exists in the users table
        $existingUser = User::where('experion_id', $request->experion_id)->first();

        if ($existingUser) {
            // If the user was soft-deleted, restore the user
            if ($existingUser->is_active === 0) {
                $existingUser->update(['is_active' => 1]); // Restore the user by setting is_active to 1
                return response()->json(['message' => 'User restored successfully'], 200);
            } else {
                throw new Exception('User already exists');
            }
        }
        
        // Get the experion employee data
        $experionEmployee = ExperionEmployees::where('id', $request->experion_id)->first();

        if (!$experionEmployee) {
            throw new ModelNotFoundException('Experion employee not found');
        }

        // Build the user_name
        $user_name = $experionEmployee->first_name . ' ' . $experionEmployee->middle_name . ' ' . $experionEmployee->last_name;

        // Create a new user
        $user = new User([
            'experion_id' => $experionEmployee->id,
            'role_id' => $request->role_id,
            'user_name' => $user_name,
            'user_mail' => $experionEmployee->email_id,
            'is_active' => 1, // Set default is_active value
            'user_designation' => $request->designation,
            'timestamp' => now(),
        ]);

        // Save the user
        $user->save();

        return response()->json(['message' => 'User added successfully'], 201);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => $e->getMessage()], 404);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    }
}

    public function getUsers(Request $request){
        try{    
           // Default values for parameters
        $searchTerm = $request->input('search', '');
        $sortColumn = $request->input('sort', 'user_name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $users = User::leftJoin('contracts', 'users.id', '=', 'contracts.contract_added_by')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.user_name', 'roles.role_access', \DB::raw('COUNT(contracts.id) as contracts_count'))
                    ->where('users.is_active', 1)
                    ->when($searchTerm, function ($query) use ($searchTerm) {
                        return $query->where('users.user_name', 'like', "%$searchTerm%");
                    })
                    ->orderBy($sortColumn, $sortOrder)
                    ->groupBy('users.user_name', 'roles.role_access', 'users.id')
                    ->get();

    return response()->json($users);
} catch (QueryException $e) {
    // Handle database query exceptions
    return response()->json(['error' => 'Database error.'], 500);
} catch (ModelNotFoundException $e) {
    // Handle model not found exceptions
    return response()->json(['error' => 'Resource not found.'], 404);
} catch (HttpException $e) {
    // Handle HTTP exceptions
    return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
} catch (QueryException $e) {
    // Catch any other generic exceptions
    return response()->json(['error' => $e->getMessage()], 500);
}

    }

    public function updateUser(Request $request,$user_id){
        
    try {
        // Validate the request data
        $request->validate([
            'role_id' => 'sometimes|required|exists:roles,id',
            'is_active' => 'sometimes|required|boolean',
        ]);
        
        // $id = $request->input('id'); 

        // Find the user by ID
        $user = User::findOrFail($user_id);

        // Update the user attributes based on the provided request data
        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        elseif ($request->has('is_active')) {
            $user->is_active = 0; // Set is_active to 0 if delete_flag is true
            $message = 'User soft deleted successfully';
        } 

        $user->save();

        // Check if the $message variable is set and send the appropriate response
        if (isset($message)) {
            return response()->json(['message' => $message], 200);
        } else {
            return response()->json(['message' => 'User updated successfully'], 200);
        }
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'User not found'], 404);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    }
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
                'role_id' => 3,
                'user_name' => 'Michael Brown',
                'user_mail' => 'michael.brown@experionglobal.com',
                'user_designation' => 'Project Manager',
                'group_name' => '',
                'is_active' => true
            ],
            [
                'experion_id' => 4,
                'role_id' => 3,
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
