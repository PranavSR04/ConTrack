<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\MSAs;
use App\Models\UserNotifications;
use App\Http\Controllers\InsertController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MsaController extends Controller
{
    public function insertValues(){
        $data = [
           
            [
                'msa_ref_id' => 'MSA020',
                'added_by'=>2,
                'client_name' => 'Bajaj Auto Limited',
                'region' => 'India',
                'start_date' => '2024-01-15',
                'end_date' => '2025-05-20',
                'comments' => 'Agreement for motorcycle manufacturing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa020-document'
            ]
        ];

 
    foreach ($data as $msaData) {
        $msa = new MSAs($msaData);
        $msa->save();
    }
    
    return 'Values inserted';
  }

/**
 * Retrieve a list of MSAs based on specified filters or sorting criteria.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function MSAList(Request $request)
    {
        try{
        $added_by=4;//session()->get(user_id)
        $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                     ->select('users.user_name as added_by_user')
                     ->first();

        $params = $request->all();
        
        $msas_query = MSAs::query();

        // Iterate through each request parameter
        foreach ($params as $key => $value) {
            // Check if the parameter is a filtering or sorting criterion
            switch ($key) {
                case 'msa_ref_id':
                case 'client_name':
                case 'region':
                case 'start_date':
                case 'end_date':
                case 'added_by_user':
                    $msas_query->where($key,'like','%'.$value.'%');
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
        
        $msas = $msas_query->where('is_active', true)->paginate(20);
        $msas->added_by_user=$added_by_user->added_by_user;
        return response()->json($msas);
    } catch (QueryException $e) {
        return response()->json(['error' => 'Database error'], 500);
    } catch (ValidationException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'MSA not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
    }

/**
 * Add a new MSA (Master Service Agreement).
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */

    public function addMsa(Request $request)
    {   
        try{
        // Validate the incoming request data
        $validator=Validator::make($request->all(),[
            'msa_ref_id' => 'required|string|max:25',
            'client_name' => 'required|string',
            'region' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'comments' => 'string',
            'msa_doclink' => 'required|string',
           ]);

        // Check if validation fails
        if($validator->fails()){

            // Return validation errors if validation fails
            return $validator->errors();
        }

        // Get the validated data
        $validated=$validator->validated();
        

            $existingMSA = MSAs::where('msa_ref_id', $request->msa_ref_id)->exists();

            if ($existingMSA) {
            
              return response()->json(['error' => 'Failed to create MSA', 'message' => 'MSA with  ' . $request->msa_ref_id . ' already exists.'], 400);
            }

            $added_by=4;//session()->get(user_id)
            $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                     ->select('users.user_name as added_by_user')
                     ->first();

            $start_date = $request->start_date;
            $end_date = $request->end_date;
             if ($end_date <= $start_date) 
             {
               $response = [
                         'error' => 'End date must be greater than '.$start_date
                         ];
               return response()->json($response, 400);
             } else {

         

            $msa = MSAs::create([
                'msa_ref_id' => $request->msa_ref_id,
                'added_by' => $added_by,//session()->get(user_id)
                'client_name' =>$request->client_name,
                'region' =>$request->region,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'comments' => $request->comments,
                'msa_doclink' =>$request->msa_doclink
            ]);

            $msa->added_by_user=$added_by_user->added_by_user;
            $action="Added ";
            $insertController = new InsertController();
            $insertController->addToActivityLog(null,$msa->id,$added_by,$action);
            return response()->json(['message' => 'MSA created successfully', 'msa' => $msa], 201);
        }
    } catch (ValidationException $e) {
        return response()->json(['error' => 'Validation failed', 'message' => $e->validator->errors()], 422);
    } catch (QueryException $e) {
        return response()->json(['error' => 'Database error', 'message' => $e->getMessage()], 500);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Model not found', 'message' => $e->getMessage()], 404);
    }
     catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create MSA', 'message' => $e->getMessage()], 500);
        }
    }

/**
 * Update an existing MSA.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
    public function updateMsa(Request $request,$id){
        try{
       $msa = MSAs::find($id);
   
       
       if (!$msa) {
           return response()->json(['error' => 'MSA not found'], 404);
       }
        // Validate the incoming request data
        $validator=Validator::make($request->all(),[
           'client_name' => 'string|min:5|max:100',
           'region' => 'string|max:100',
           'start_date' => 'date',
           'end_date' => 'date',
           'comments' => 'string',
           'msa_doclink' => 'string',
            ]);
   
        // Check if validation fails
       if($validator->fails()){
   
           // Return validation errors if validation fails
           return $validator->errors();
       }
   
        // Get the validated data
       $validated=$validator->validated();

                 // Check if both start_date and end_date are provided
                    if (isset($validated['start_date']) && isset($validated['end_date'])) 
                    {
                        if ($validated['start_date'] >= $validated['end_date'])
                        {
                            return response()->json(['error' => 'Start date must be less than end date'], 400);
                        }
                    } 
                   elseif (isset($validated['start_date'])) 
                    {
                        // Check with end_date in the database
                        if ($msa->end_date && $validated['start_date'] >= $msa->end_date) 
                        {
                            return response()->json(['error' => 'Start date must be less than '. $msa->end_date], 400);
                        }
                    }
                elseif (isset($validated['end_date'])) 
                    {
                        // Check with start_date in the database
                        if ($msa->start_date && $validated['end_date'] <= $msa->start_date) 
                        {
                            return response()->json(['error' => 'End date must be greater than '. $msa->start_date], 400);
                        }
                    }
                  
                    $added_by=4;//session()->get(user_id)
                    $added_by_user = MSAs::join('users', 'users.id', '=', 'msas.added_by')
                             ->select('users.user_name as added_by_user')
                             ->first();
             if ($msa->is_active == 1) {
                $action="Updated ";
               $msa->update($validated);
             }else{
                $msa->update([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'msa_doclink' => $request->msa_doclink,
                    'is_active' => 1,
                ]);
            
                
             }
                $msa->added_by_user=$added_by_user->added_by_user;
                $action = "Renewed";
                $insertController = new InsertController();
                $insertController->addToActivityLog(null,$msa->id,$added_by,$action);
                    // Return success response
                  return response()->json(['message' => 'MSA updated successfully', 'msa' => $msa], 200);
                } catch (ValidationException $e) {
                    return response()->json(['error' => 'Validation failed', 'message' => $e->validator->errors()], 422);
                } catch (ModelNotFoundException $e) {
                    return response()->json(['error' => 'MSA not found'], 404);
                } catch (QueryException $e) {
                    return response()->json(['error' => 'Failed to update MSA', 'message' => $e->getMessage()], 500);
            }catch(\Exception $e){
                return response()->json(['error' => 'Failed to update MSA', 'message' => $e->getMessage()], 500);
            }
       }
       
}
