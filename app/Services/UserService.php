<?php

namespace App\Services;

use App\Models\Group;
use App\Models\UserGroupMap;
use App\ServiceInterfaces\UserInterface;
use App\Models\ExperionEmployees;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\DB;


class UserService implements UserInterface
{
    
    public function addUser(Request $request)
    {

        try {
            // Validate the request data
            $request->validate([
                'experion_id' => 'required',
                'role_id' => 'required|exists:roles,id',
            ]);

            if ($request->input('role_id') == 1) {
                throw new Exception('There can be only one Super Admin');
            }

            // Check if the user already exists in the users table
            $existingUser = User::where('experion_id', $request->experion_id)->first();
            
            if ($existingUser) {
                // If the user was soft-deleted, restore the user
                if ($existingUser->is_active === 0) {
                    $existingUser->update([
                        'is_active' => 1,    // Restore the user by setting is_active to 1
                        'role_id' => $request->role_id, // Update the role_id
                    ]);
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

    public function addGroup(Request $request)
    {

        try {
            $request->validate([
                'group_name' => 'required|unique:group',
            ]);

            $group = new Group([
                'group_name' => $request->group_name,
                'timestamp' => now(),
            ]);
            $group->save();

            return response()->json(['message' => 'Group added successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    public function getUsers(Request $request)
    {
        try {
            //to search and sort the user
            $searchTerm = $request->input('search', '');
            $sortColumn = $request->input('sort', 'user_name');
            $sortOrder = $request->input('sort_order', 'asc');

            $users = User::leftJoin('associated_users', 'users.id', '=', 'associated_users.user_id')
                        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                        ->leftJoin('user_group_map', 'users.id', '=', 'user_group_map.user_id')
                        ->leftJoin('group', 'user_group_map.group_id', '=', 'group.id')
                        ->select(
                            'users.id',
                            'users.user_name',
                            'roles.role_access',
                            \DB::raw('GROUP_CONCAT(DISTINCT group.group_name ORDER BY group.group_name ASC) AS group_names'), // Aggregated groups
                            \DB::raw('COUNT(DISTINCT associated_users.contract_id) as contracts_count')
                        )
                        ->where('users.is_active', 1)
                        ->where('users.role_id', '!=', 1) 
                        ->when($searchTerm, function ($query) use ($searchTerm) {
                            return $query->where('users.user_name', 'like', "%$searchTerm%");
                        })
                        ->orderBy($sortColumn, $sortOrder)
                        ->groupBy('users.user_name', 'roles.role_access', 'users.id')
                        ->paginate(10);
           
            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $users
            ],200);

        } catch (QueryException $e) {
            // Handle database query exceptions
            return response()->json(['error' => 'Database error.', $e], 500);
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

    public function updateUser(Request $request, $user_id)
    {

        try {
            // Validate the request data
            $request->validate([
                'role_id' => 'sometimes|required|exists:roles,id',
                'is_active' => 'sometimes|required|boolean',
            ]);

            $user = User::findOrFail($user_id);

            // Update the user attributes based on the provided request data
            if ($request->has('role_id')) {
                $user->role_id = $request->role_id;
            } elseif ($request->has('is_active')) {
                $user->is_active = 0; // Set is_active to 0 if is_active passed
                DB::table('user_group_map')->where('user_id', $user_id)->delete();
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

    public function myContracts(Request $request, $user_id)
    {

        try {
            // Validate the input parameters
            $validator = Validator::make(['user_id' => $user_id], [
                'user_id' => 'required|exists:users,id',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $requestData = $request->all();

            $myContracts = User::where('users.id', $user_id)
            ->leftJoin('associated_users', 'users.id', '=', 'associated_users.user_id')
            ->leftJoin('contracts', function ($join) use ($requestData) {
                if (isset($requestData['added_by_me'])) {
                    $join->on('contracts.contract_added_by', '=', 'users.id');
                } 
                else if
                    (isset($requestData['associated_by_me'])) {
                    $join->on('associated_users.contract_id', '=', 'contracts.id');
                }
                else {
                    $join->on('contracts.contract_added_by', '=', 'users.id')
                         ->oron('associated_users.contract_id', '=', 'contracts.id');
                }
            })
            ->leftJoin('msas', 'msas.id', '=', 'contracts.msa_id')
            ->select('contracts.id', 'contracts.contract_ref_id', 'msas.client_name', 'contracts.start_date', 'contracts.end_date', 'contracts.contract_type', 'contracts.contract_status', 'contracts.du')
            ->where('users.id', $user_id)
            ->orderBy('contracts.updated_at', 'desc'); // Sort by updated_at column in descending order

            $myContracts->distinct('contracts.id');

            if (empty($requestData)) {
                return $myContracts->paginate(10);
            } else {
                //add search conditions
                foreach ($requestData as $key => $value) {
                    if (in_array($key, ['contract_ref_id', 'client_name', 'du', 'contract_type', 'msa_ref_id', 'contract_status'])) {
                        $myContracts->where($key, 'LIKE', '%' . $value . '%');
                    }
                    if (in_array($key, ['start_date', 'end_date'])) {
                        $myContracts->where('contracts.' . $key, 'LIKE', '%' . $value . '%');
                    }
                }
                if ($request->status) {
                    $myContracts->where('contract_status', '=', $request->status);
                } else {
                    //exclude expired default
                    $myContracts->where('contract_status', '!=', 'Expired');
                }
                if ($request->sort_by) {
                    $myContracts->orderBy($request->sort_by, $request->sort_value);
                }
                else{
                    $myContracts->orderBy('contracts.updated_at', 'desc'); //default sort
                }
                if ($myContracts->count() == 0) {
                    return response()->json(['error' => 'Data not found'], 404);
                }
                return $myContracts->paginate(10);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

            // return response()->json(["data" => $myContracts]);
         catch (QueryException $e) {
            // Handle database query exceptions
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getGroups()
    {
        $groupNames = Group::all();
        // Return the group names, you might want to return it as JSON
        return response()->json($groupNames);
    }

    public function assignUserGroups(Request $request) {
        // $experion_id = $request->experion_id;
        // $groupIds = $request->group_ids; // expecting this to be an array

        $experion_id = $request->input('experion_id');
        $groupIds = $request->input('groupIds'); // Ensure you're using the correct key here

        // Check if groupIds is an array
        if (!is_array($groupIds)) {
            return response()->json(['message' => 'Invalid group IDs provided'], 400);
        }

        // Find user with the specified experion_id
        $user = User::where('experion_id', $experion_id)->first();

        if ($user) {
            foreach ($groupIds as $groupId) {
                // Create mapping for each group
                UserGroupMap::create([
                    'user_id' => $user->id,
                    'group_id' => $groupId
                ]);
            }

            return response()->json(['message' => 'Groups assigned successfully'], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function getGroupUsers(Request $request)
    {
        try {
            // Retrieve the input parameters
            $searchTerm = $request->input('search', '');
            $sortColumn = $request->input('sort', 'user_name');
            $sortOrder = $request->input('sort_order', 'asc');
            $groupId = $request->input('group_id'); // Retrieve the group_id from request

            // Check if group_id is provided
            // if (empty($groupId)) {
            //     return response()->json(['error' => 'Group ID is required.'], 400);
            // }

            // Query to fetch users
            $users = User::leftJoin('associated_users', 'users.id', '=', 'associated_users.user_id')
                        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                        ->join('user_group_map', 'users.id', '=', 'user_group_map.user_id') // Changed to join to enforce presence in user_group_map
                        ->leftJoin('group', 'user_group_map.group_id', '=', 'group.id')
                        ->select(
                            'users.id',
                            'users.user_name',
                            'roles.role_access',
                            \DB::raw('COUNT(DISTINCT associated_users.contract_id) as contracts_count')
                        )
                        ->where('users.is_active', 1)
                        ->where('users.role_id', '!=', 1)
                        ->where('user_group_map.group_id', '=', $groupId) // Filter by group_id
                        ->when($searchTerm, function ($query) use ($searchTerm) {
                            return $query->where('users.user_name', 'like', "%$searchTerm%");
                        })
                        ->orderBy($sortColumn, $sortOrder)
                        ->groupBy('users.user_name', 'roles.role_access', 'users.id')
                        ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $users
            ], 200);

        } catch (QueryException $e) {
            // Handle database query exceptions
            return response()->json(['error' => 'Database error.', $e], 500);
        } catch (ModelNotFoundException $e) {
            // Handle model not found exceptions
            return response()->json(['error' => 'Resource not found.'], 404);
        } catch (HttpException $e) {
            // Handle HTTP exceptions
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            // Catch any other generic exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function getUsersList()
    // {
    //     // Retrieve all users with only 'id' and 'user_name' fields
    //     return User::select('id', 'user_name')->orderBy('user_name', 'asc')->get();
    // }

    public function getUsersList(Request $request)
    {
        $query = User::select('id', 'user_name')
                        ->where('users.is_active', 1)
                        ->orderBy('user_name', 'asc');

        // Check if a search query is present
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('user_name', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    public function addUsersToIndividualGroup(Request $request)
    {
        $groupId = $request->input('selectedIndividualGroup');
        $userIds = $request->input('selectedUsers');

        // Validate inputs
        if (is_null($groupId) || !is_array($userIds) || empty($userIds)) {
            return response()->json(['error' => 'Invalid input data provided.'], 400);
        }

        try {
            $newEntries = [];
            foreach ($userIds as $userId) {
                // Check for existing mapping
                $exists = UserGroupMap::where('group_id', $groupId)
                                      ->where('user_id', $userId)
                                      ->exists();
                if (!$exists) {
                    // Prepare the data if no existing mapping
                    $newEntries[] = [
                        'group_id' => $groupId,
                        'user_id' => $userId
                    ];
                }
            }

            if (!empty($newEntries)) {
                // Insert new data in one go if there are any new entries
                UserGroupMap::insert($newEntries);
                return response()->json([
                    'success' => true,
                    'message' => 'User group mappings added successfully.'
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'No new mappings were needed; all requested mappings already exist.'
                ], 200);
            }
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Failed to insert user group mappings.',
                'message' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteUserFromGroup(Request $request)
    {
         // Retrieve user and group IDs from the request
    $userToBeDeletedFromGroup = $request->input('userToBeDeletedFromGroup');
    $selectedIndividualGroup = $request->input('selectedIndividualGroup');

    // Validate the presence of the user ID and group ID
    if (is_null($userToBeDeletedFromGroup) || is_null($selectedIndividualGroup)) {
        return response()->json([
            'message' => 'Both user ID and group ID must be provided.'
        ], 400); // Bad Request
    }

    // Attempt to delete the record from the database
    try {
        $deleted  = UserGroupMap::where('user_id', $userToBeDeletedFromGroup)
                                ->where('group_id', $selectedIndividualGroup)
                                ->delete();

        // Check if any rows were deleted
        if ($deleted) {
            return response()->json([
                'message' => 'User removed from the group successfully.'
            ], 200); // OK
        } else {
            return response()->json([
                'message' => 'No such user in the group found.'
            ], 404); // Not Found
        }
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Failed to delete user from group due to a server error.',
            'error' => $e->getMessage(),
        ], 500); // Internal Server Error
    }

    }

    public function deleteGroup(Request $request) 
    {
    $selectedIndividualGroup = $request->input('selectedIndividualGroup');

    // Delete records from user_group_map table
    UserGroupMap::where('group_id', $selectedIndividualGroup)->delete();

    // Delete record from group table
    Group::where('id', $selectedIndividualGroup)->delete();

    return response()->json(['message' => 'Group and mappings deleted successfully']);
}





}