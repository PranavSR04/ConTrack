<?php

namespace App\Services;

use App\ServiceInterfaces\ExperionEmployeesInterface;
use Illuminate\Http\Request;
use App\Models\ExperionEmployees;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ExperionEmployeesService implements ExperionEmployeesInterface
{
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
    public function show(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string',
            ]);

            $users = ExperionEmployees::where(function ($query) use ($request) {
                $query->where('first_name', 'like', $request->name . '%')
                    ->orWhere('middle_name', 'like', $request->name . '%')
                    ->orWhere('last_name', 'like', $request->name . '%');
            })->get();

            if ($users->isEmpty()) {
                // 404 Not Found: No records found
                return response()->json(['error' => 'No records found.'], 404);
            }

            return response()->json($users, 200);
        } catch (ValidationException $e) {
            // 422 Unprocessable Entity: Validation error
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (ModelNotFoundException $e) {
            // 404 Not Found: Model not found exception
            return response()->json(['error' => 'Record not found.'], 404);
        } catch (\Exception $e) {
            // 500 Internal Server Error: Other exceptions
            return response()->json(['error' => 'Internal Server Error.'], 500);
        }
    }
}
