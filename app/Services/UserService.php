<?php

namespace App\Services;

use App\ServiceInterfaces\UserInterface;
use App\Models\ExperionEmployees;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;


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

    public function getUsers(Request $request)
    {
        try {
            //to search and sort the user
            $searchTerm = $request->input('search', '');
            $sortColumn = $request->input('sort', 'user_name');
            $sortOrder = $request->input('sort_order', 'asc');

            $users = User::leftJoin('associated_users', 'users.id', '=', 'associated_users.user_id')
                        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                        ->select('users.id','users.user_name', 'roles.role_access', \DB::raw('COUNT(associated_users.contract_id) as contracts_count'))
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


}