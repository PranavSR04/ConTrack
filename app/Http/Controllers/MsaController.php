<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\MSAs;
use App\Models\UserNotifications;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MsaController extends Controller
{
    public function insertValues(){
        $data = [
            [
                'msa_ref_id' => 'MSA001',
                'added_by'=>1,
                'client_name' => 'Microsoft Corporation',
                'region' => 'America',
                'start_date' => '2024-02-20',
                'end_date' => '2024-09-25',
                'comments' => 'Agreement for software licensing and support services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa001-document'
            ],
            [
                'msa_ref_id' => 'MSA002',
                'added_by'=>1,
                'client_name' => 'Apple Inc.',
                'region' => 'Europe',
                'start_date' => '2019-03-10',
                'end_date' => '2020-03-15',
                'comments' => 'Agreement for hardware supply and maintenance.',
                'is_active' => false,
                'msa_doclink' => 'https://example.com/msa002-document'
            ],
            [
                'msa_ref_id' => 'MSA003',             
                'added_by'=>5,
                'client_name' => 'Amazon.com Inc.',
                'region' => 'Asia',
                'start_date' => '2020-04-05',
                'end_date' => '2024-11-30',
                'comments' => 'Agreement for cloud computing services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa003-document'
            ],
            [
                'msa_ref_id' => 'MSA004',
                'added_by'=>2,
                'client_name' => 'Alphabet Inc.',
                'region' => 'America',
                'start_date' => '2015-05-15',
                'end_date' => '2024-10-20',
                'comments' => 'Agreement for online advertising services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa004-document'
            ],
            [
                'msa_ref_id' => 'MSA005',
                'added_by'=>2,
                'client_name' => 'Facebook Inc.',
                'region' => 'Europe',
                'start_date' => '2018-06-20',
                'end_date' => '2024-12-25',
                'comments' => 'Agreement for social media platform access.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa005-document'
            ],
            [
                'msa_ref_id' => 'MSA006',
                'added_by'=>2,
                'client_name' => 'Samsung Electronics Co., Ltd.',
                'region' => 'China',
                'start_date' => '2023-07-10',
                'end_date' => '2024-01-15',
                'comments' => 'Agreement for consumer electronics distribution.',
                'is_active' => false,
                'msa_doclink' => 'https://example.com/msa006-document'
            ],
            [
                'msa_ref_id' => 'MSA007',
                'added_by'=>5,
                'client_name' => 'Walmart Inc.',
                'region' => 'America',
                'start_date' => '2021-08-05',
                'end_date' => '2024-10-10',
                'comments' => 'Agreement for retail supply chain management.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa007-document'
            ],
            [
                'msa_ref_id' => 'MSA008',
                'added_by'=>3,
                'client_name' => 'Toyota Motor Corporation',
                'region' => 'India',
                'start_date' => '2020-09-25',
                'end_date' => '2025-03-30',
                'comments' => 'Agreement for automotive manufacturing collaboration.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa008-document'
            ],
            [
                'msa_ref_id' => 'MSA009',
                'added_by'=>4,
                'client_name' => 'Sony Corporation',
                'region' => 'Europe',
                'start_date' => '2020-10-10',
                'end_date' => '2025-04-15',
                'comments' => 'Agreement for entertainment content licensing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa009-document'
            ],
            [
                'msa_ref_id' => 'MSA010',
                'added_by'=>3,
                'client_name' => 'McDonald\'s Corporation',
                'region' => 'America',
                'start_date' => '2024-01-15',
                'end_date' => '2025-05-20',
                'comments' => 'Agreement for food supply and franchising.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa010-document'
            ],
            [
                'msa_ref_id' => 'MSA011',
                'added_by'=>1,
                'client_name' => 'Tata Consultancy Services Ltd.',
                'region' => 'India',
                'start_date' => '2019-02-20',
                'end_date' => '2020-09-25',
                'comments' => 'Agreement for IT services outsourcing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa011-document'
            ],
            [
                'msa_ref_id' => 'MSA012',
                'added_by'=>3,
                'client_name' => 'Reliance Industries Limited',
                'region' => 'India',
                'start_date' => '2019-03-10',
                'end_date' => '2024-12-15',
                'comments' => 'Agreement for oil and gas exploration.',
                'is_active' => false,
                'msa_doclink' => 'https://example.com/msa012-document'
            ],
            [
                'msa_ref_id' => 'MSA013',
                'added_by'=>5,
                'client_name' => 'Infosys Limited',
                'region' => 'India',
                'start_date' => '2015-04-05',
                'end_date' => '2025-11-30',
                'comments' => 'Agreement for software development services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa013-document'
            ],
            [
                'msa_ref_id' => 'MSA014',
                 'added_by'=>2,
                'client_name' => 'HDFC Bank Limited',
                'region' => 'India',
                'start_date' => '2024-01-15',
                'end_date' => '2024-10-20',
                'comments' => 'Agreement for banking and financial services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa014-document'
            ],
            [
                'msa_ref_id' => 'MSA015',
                'added_by'=>5,
                'client_name' => 'Mahindra & Mahindra Limited',
                'region' => 'India',
                'start_date' => '2020-06-20',
                'end_date' => '2024-12-25',
                'comments' => 'Agreement for automobile manufacturing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa015-document'
            ],
            [
                'msa_ref_id' => 'MSA016',
                'added_by'=>2,
                'client_name' => 'State Bank of India',
                'region' => 'India',
                'start_date' => '2014-07-10',
                'end_date' => '2026-11-15',
                'comments' => 'Agreement for banking and financial services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa016-document'
            ],
            [
                'msa_ref_id' => 'MSA017',
                'added_by'=>4,
                'client_name' => 'Bharat Petroleum Corporation Limited',
                'region' => 'India',
                'start_date' => '2020-08-05',
                'end_date' => '2030-10-10',
                'comments' => 'Agreement for oil and gas refining.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa017-document'
            ],
            [
                'msa_ref_id' => 'MSA018',
                'added_by'=>2,
                'client_name' => 'Wipro Limited',
                'region' => 'India',
                'start_date' => '2024-02-25',
                'end_date' => '2025-03-30',
                'comments' => 'Agreement for IT services and consulting.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa018-document'
            ],
            [
                'msa_ref_id' => 'MSA019',
                'added_by'=>1,
                'client_name' => 'Indian Oil Corporation Limited',
                'region' => 'India',
                'start_date' => '2024-10-10',
                'end_date' => '2025-04-15',
                'comments' => 'Agreement for oil and gas refining.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa019-document'
            ],
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
                'msa_doclink' =>$request->msa_doclink,
            ]);

            $msa->added_by_user=$added_by_user->added_by_user;
            $action="Added ";
             ActivityLogs::create([
            'msa_id' => $msa->id,
            'performed_by' => $added_by,
            'action' =>$action,
              ]);
            
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

               $msa->update($validated);
                $msa->added_by_user=$added_by_user->added_by_user;
                $action="Updated ";
                ActivityLogs::create([
               'msa_id' => $msa->id,
               'performed_by' => $added_by,
               'action' =>$action,
                 ]);
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
