<?php
namespace App\Services;
use App\Http\Controllers\GoogleDriveController;
use App\Models\ActivityLogs;
use App\Models\MSAs;

use App\ServiceInterfaces\MsaInterface;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class MsaService implements MsaInterface {
    public function MSAList(Request $request)
    {
        try {
            

            $params = $request->all();

            $msas_query = MSAs::query();
            $offset=$request->paginate_offset;
           $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
           ->select('users.user_name as added_by_user');
            // Iterate through each request parameter
            foreach ($params as $key => $value) {
                // Check if the parameter is a filtering or sorting criterion
                switch ($key) {
                    case 'msa_ref_id':
                    case 'client_name':
                    case 'region':
                    case 'start_date':
                    case 'end_date':
                        $msas_query->where($key, 'like', '%' . $value . '%');
                        break;
                    case 'added_by_user':
                        $added_by_user 
                        ->where($key, 'like', '%' . $value . '%'); // Fetch the first matching record
                    break;
                    case 'sort_by':
                        // Extract sort order 
                        $sort_order = isset($params['sort_order']) && strtolower($params['sort_order']) === 'desc' ? 'desc' : 'asc';
                        $msas_query->orderBy($value, $sort_order);
                        break;
                    default:
                        break;
                }
            }

            $msas = $msas_query->orderByDesc('updated_at')
            ->orderByDesc('is_active')->paginate($offset);
            return response()->json($msas);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error'], 500);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'MSA not found'], 404);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function addMsa(Request $request,$user_id)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'msa_ref_id' => 'required|string|max:25',
                'client_name' => 'required|string',
                'region' => 'required|string|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'comments' => 'string',
                'file' => 'file',
            ]);

            // Check if validation fails
            if ($validator->fails()) {

                // Return validation errors if validation fails
                return $validator->errors();
            }

            // Get the validated data
            $validated = $validator->validated();


            $existingMSA = MSAs::where('msa_ref_id', $request->msa_ref_id)->exists();

            if ($existingMSA) {

                return response()->json(['error' => 'Failed to create MSA', 'message' => 'MSA with  ' . $request->msa_ref_id . ' already exists.'], 400);
            }

            $added_by = $user_id;
            // return $added_by;//session()->get(user_id)
            $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                ->select('users.user_name as added_by_user')
                ->first();

            $start_date = $request->start_date;
            $end_date = $request->end_date;
            if ($end_date <= $start_date) {
                $response = [
                    'error' => 'End date must be greater than ' . $start_date
                ];
                return response()->json($response, 400);
            } else {

                $googleDrive = new GoogleDriveController();
                $fileLink = $googleDrive->store($request);
                
                $msa = MSAs::create([
                    'msa_ref_id' => $request->msa_ref_id,
                    'added_by' => $added_by,//session()->get(user_id)
                    'client_name' => $request->client_name,
                    'region' => $request->region,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'msa_doclink'=>$fileLink,
                    'comments' => $request->comments,
                ]);

                $msa->added_by_user = $added_by_user->added_by_user;
                $action = "Added ";
                ActivityLogs::create([
                    'msa_id' => $msa->id,
                    'performed_by' => $added_by,
                    'action' => $action,
                ]);



                return response()->json(['message' => 'MSA created successfully', 'msa' => $msa], 201);
            }
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
    public function updateMsa(Request $request,$user_id)
    {
        try {
            $msa_id= $request->id;
            $msa=MSAs::findOrFail($msa_id);

            
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'client_name' => 'string|min:5|max:100',
                'region' => 'string|max:100',
                'start_date' => 'date',
                'end_date' => 'date',
                'comments' => 'string',
                'msa_doclink'=>'string',
                'file' => 'file',
            ]);

            // Check if validation fails
            if ($validator->fails()) {

                // Return validation errors if validation fails
                return $validator->errors();
            }

            // Get the validated data
            $validated = $validator->validated();

            // Check if both start_date and end_date are provided
            if (isset($validated['start_date']) && isset($validated['end_date'])) {
                if ($validated['start_date'] >= $validated['end_date']) {
                    return response()->json(['error' => 'Start date must be less than end date'], 400);
                }
            } elseif (isset($validated['start_date'])) {
                // Check with end_date in the database
                if ($msa->end_date && $validated['start_date'] >= $msa->end_date) {
                    return response()->json(['error' => 'Start date must be less than ' . $msa->end_date], 400);
                }
            } elseif (isset($validated['end_date'])) {
                // Check with start_date in the database
                if ($msa->start_date && $validated['end_date'] <= $msa->start_date) {
                    return response()->json(['error' => 'End date must be greater than ' . $msa->start_date], 400);
                }
            }

            $added_by = $request->$user_id;//session()->get(user_id)
            $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                ->select('users.user_name as added_by_user')
                ->first();
            if ($msa->is_active == 1) {
                $action = "Updated ";
                $msa->update($validated);
            } else {
                $googleDrive = new GoogleDriveController();
                // $fileLink = $googleDrive->store($request);

                if($googleDrive->store($request)){
                    $fileLink = $googleDrive->store($request);
                    $msa->update([
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'msa_doclink' => $fileLink,
                        'is_active' => 1,
                    ]);
                } else{
                    $msa->update([
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'msa_doclink' => $request->msa_doclink,
                        'is_active' => 1,
                    ]);
                }

                

                $action = "Renewed";
            }
            $msa->added_by_user = $added_by_user->added_by_user;

            ActivityLogs::create([
                'msa_id' => $msa->id,
                'performed_by' => $added_by,
                'action' => $action,
            ]);
            // Return success response
            return response()->json(['message' => 'MSA updated successfully', 'msa' => $msa], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->validator->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'MSA not found'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to update MSA', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update MSA', 'message' => $e->getMessage()], 500);
        }
    }
}