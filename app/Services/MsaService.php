<?php
namespace App\Services;

use App\Http\Controllers\ActivityLogInsertController;
use App\Models\ActivityLogs;
use App\Models\MSAs;
use App\ServiceInterfaces\MsaInterface;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validiator;
use Exception;

class MsaService implements MsaInterface
{
    /**
 * Retrieve a list of MSAs based on the provided parameters.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function MSAList(Request $request)
    {
        try {

            $params = $request->all();
            $is_active = (bool) $request->input('is_active');
            $msas_query = MSAs::query();
            foreach ($params as $key => $value) {
                // Check if the parameter is a filtering or sorting criterion
                switch ($key) {
                    case 'is_active':
                        $msas_query->where('is_active',$is_active);
                        break;
                    case 'msa_ref_id':
                    case 'client_name':
                         $msas_query->where($key,'like', $value.'%');
                    case 'region':
                    case 'start_date':
                    case 'end_date':
                        $msas_query->where($key, 'like', '%' . $value . '%');
                        break;
                    case 'sort_by':
                        $sort_order = isset ($params['sort_order']) && strtolower($params['sort_order']) === 'desc' ? 'desc' : 'asc';
                        $msas_query->orderBy($value, $sort_order);
                        break;
                    default:
                        break;
                }
            }
                $msas = $msas_query
                    ->orderByDesc('updated_at')
                    ->paginate(10);

            return response()->json($msas, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Querry error'], 500);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'MSA not found'], 404);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

        /**
     * Add a new MSA to the database.
     *
     * @param  \Illuminate\Http\Request  $request The HTTP request object containing MSA data.
     * @param  int|null  $user_id The ID of the user adding the MSA (optional).
     * @return \Illuminate\Http\JsonResponse Response containing the result of the operation.
     */
    public function addMsa(Request $request, $user_id)
    {

        // Validate the incoming request data
        $validator = validator::make($request->all(), [
            'msa_ref_id' => 'required|string|max:25',
            'client_name' => 'required|string',
            'region' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'comments' => 'string',
            'file' => 'file|max:10240',
        ]);

        // Check if validation fails
        if ($validator->fails()) {

            // Return validation errors if validation fails
            return response()->json($validator->errors());
        }

        // Get the validated data
        $validated = $validator->validated();

        try {
            $existingMSA = MSAs::where('msa_ref_id', $request->msa_ref_id)->exists();

            if ($existingMSA) {

                return response()->json(['error' => 'Failed to create MSA', 'message' => 'MSA with  ' . $request->msa_ref_id . ' already exists.'], 400);
            }
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            // Check if start date is greater than end date
            if ($start_date > $end_date) {
                return response()->json(['error' => 'Validation failed', 'message' => 'Start date cannot be after end date'], 422);
            }

            $added_by = $user_id;
            $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                ->select('users.user_name as added_by_user')
                ->first();

            $googleDrive = new GoogleDriveService();
            $fileLink = $googleDrive->store($request);

            $msa = MSAs::create([
                'msa_ref_id' => $request->msa_ref_id,
                'added_by' => $added_by,
                'client_name' => $request->client_name,
                'region' => $request->region,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'msa_doclink' => $fileLink,
                'comments' => $request->comments,
            ]);

            $msa->added_by_user = $added_by_user->added_by_user;


            $action = "Added";
            // $activityLogInsertService = new ActivityLogInsertService();
            // $insertController = new ActivityLogInsertController($activityLogInsertService);
            // $insertController->addToActivityLog(null, $msa->id, $added_by, $action);
            ActivityLogs::create([
                'contract_id'=> null,
                'msa_id'=> $msa->id,
                'performed_by'=>$added_by,
                'action'=>$action
            ]);


            return response()->json(['message' => 'MSA created successfully', 'msa' => $msa], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed'], 422);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Model not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create MSA', 'message' => $e->getMessage()], 500);
        }
    }

    /**
 * Edit an MSA (Master Service Agreement) based on the provided parameters.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $user_id
 * @return \Illuminate\Http\JsonResponse
 */
    public function editMsa(Request $request, $user_id)
    {
        try {

            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'client_name' => 'string|min:5|max:100',
                'region' => 'string|max:100',
                'start_date' => 'date',
                'end_date' => 'date',
                'comments' => 'string',
            ]);

            // Check if validation fails
            if ($validator->fails()) {

                // Return validation errors if validation fails
                return response()->json($validator->errors());
            }


            // Get the validated data
            $validated = $validator->validated();

            $msa_ref_id = $request->msa_ref_id;
            $msa = MSAs::where('msa_ref_id', $msa_ref_id)->first();

            // Check if both start_date and end_date are provided
            if (isset ($validated['start_date']) && isset ($validated['end_date'])) {
                if ($validated['start_date'] >= $validated['end_date']) {
                    return response()->json(['error' => 'Start date must be less than end date'], 400);
                }
            } elseif (isset ($validated['start_date'])) {
                // Check with end_date in the database
                if ($msa->end_date && $validated['start_date'] >= $msa->end_date) {
                    return response()->json(['error' => 'Start date must be less than ' . $msa->end_date], 400);
                }
            } elseif (isset ($validated['end_date'])) {
                // Check with start_date in the database
                if ($msa->start_date && $validated['end_date'] <= $msa->start_date) {
                    return response()->json(['error' => 'End date must be greater than ' . $msa->start_date], 400);
                }
            }

            $added_by = $user_id;
            $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                ->select('users.user_name as added_by_user')
                ->first();
            
            $msa->update($validated);

            $action = "Edited";
            // $activityLogInsertService = new ActivityLogInsertService();
            // $insertController = new ActivityLogInsertController($activityLogInsertService);
            // $insertController->addToActivityLog(null, $msa->id, $added_by, $action);
            ActivityLogs::create([
                'contract_id'=> null,
                'msa_id'=> $msa->id,
                'performed_by'=>$added_by,
                'action'=>$action
            ]);

         return response()->json(['message' => 'MSA updated successfully', 'msa' => $msa], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed'], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'MSA not found'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update MSA -', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update MS--', 'message' => $e->getMessage()], 500);
        }
    }

    /**
 * Renew an existing MSA (Master Service Agreement) based on the provided parameters.
 *
 * @param  \Illuminate\Http\Request  $request The HTTP request containing the MSA data
 * @param  int  $user_id The ID of the user performing the action
 * @return \Illuminate\Http\JsonResponse A JSON response indicating the success or failure of the MSA renewal
 */
    public function renewMsa(Request $request, $user_id)
    {
        // return response()->json($request->all());
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'msa_ref_id' => 'required|string|max:25',
                'client_name' => 'required|string',
                'region' => 'string|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'comments' => 'string',
                'file' => 'file|max:10240',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                // Return validation errors if validation fails
                return response()->json($validator->errors());
            }

            // Get the validated data
            $validated = $validator->validated();
            $msa_ref_id = $request->msa_ref_id;
            $msa = MSAs::where('msa_ref_id', $msa_ref_id)->first();

                $googleDrive = new GoogleDriveService();
                $fileLink = $googleDrive->store($request);
                $msa->update(([
                    'client_name' => $request->client_name,
                    'region' => $request->region,
                    'msa_doclink' => $fileLink,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'comments' => $request->comments,
                    'is_active' => 1
                ]));
            $added_by = $user_id;
            $action = "Renewed";
            $activityLogInsertService = new ActivityLogInsertService();
            $insertController = new ActivityLogInsertController($activityLogInsertService);
            $insertController->addToActivityLog(null, $msa->id, $added_by, $action);

            return response()->json(['message' => 'MSA renewed successfully', 'msa' => $msa], 200);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->validator->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Model not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create MSA', 'message' => $e->getMessage()], 500);
        }
    }

    /**
 * Retrieve the count of active MSAs (Master Service Agreements).
 *
 * @param  \Illuminate\Http\Request  $request The HTTP request
 * @return \Illuminate\Http\JsonResponse A JSON response containing the count of active MSAs
 */
    public function msaCount(Request $request)
    {
        try {
            $activeMSACount = MSAs::count();

            return response()->json(['active_msa_count' => $activeMSACount]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Query error'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}