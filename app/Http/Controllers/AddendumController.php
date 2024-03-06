<?php

namespace App\Http\Controllers;

use App\Models\Addendums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AddendumController extends Controller
{
    /**
     * Generate a google api token.
     */
    public function token()
    {
        $client_id = \Config('services.google.client_id');
        $client_secret = \Config('services.google.client_secret');
        $refresh_token = \Config('services.google.refresh_token');
        $folder_id = \Config('services.google.folder_id');

        $response = Http::post('https://oauth2.googleapis.com/token', [

            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',

        ]);
        // dd($response);
        $accessToken = json_decode((string) $response->getBody(), true)['access_token'];

        return $accessToken;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$contractId)
    {
        if($request->addendum_file!==''){
            $validation = $request->validate([
                'addendum_file' => 'file|required'
            ]);
    
            $accessToken = $this->token();
            // dd($accessToken);
            $name = $request->addendum_file->getClientOriginalName();
            //$mime=$request->file->getClientMimeType();
    
            $path = $request->addendum_file->getRealPath();
    
            $response = Http::withToken($accessToken)
                ->attach('data', file_get_contents($path), $name)
                ->withHeaders([
                    'Content-Type' => 'application/pdf',
                ])
                ->post('https://www.googleapis.com/upload/drive/v3/files', [
                    'name' => $name,
                ]);
    
                var_dump("inside addendum upload");
            if ($response->successful()) {
                $file_id = json_decode($response->body())->id;
    
                $uploadedFile = new Addendums;
                $uploadedFile->contract_id = $contractId;
                // $uploadedFile->file_id = $file_id;
                $fileLink = "https://drive.google.com/file/d/{$file_id}";
                $uploadedFile->addendum_doclink = $fileLink;
                $uploadedFile->save();
    
                // return $response->json(["message"=>"File Uploaded to Google Drive"]);
                return response("File Uploaded to Google Drive");
            } else {
                // return $response->json(["error"=>"Couldn't upload to Google Drive"]);
                return response("Couldn't upload to Google Drive");
            }
        }
        
    }

    /**
     * Store generated data
     */
    public function generateData(){
        $addendumData = [
            [
                "contract_id"=>1,
                "addendum_doclink"=>"https://example.com/document1"
            ],
            [
                "contract_id"=>1,
                "addendum_doclink"=>"https://example.com/document12"
            ],
            [
                "contract_id"=>1,
                "addendum_doclink"=>"https://example.com/document13"
            ],
            [
                "contract_id"=>2,
                "addendum_doclink"=>"https://example.com/document20"
            ],
            [
                "contract_id"=>2,
                "addendum_doclink"=>"https://example.com/document21"
            ],
            [
                "contract_id"=>3,
                "addendum_doclink"=>"https://example.com/document30"
            ]
        ];
        foreach ($addendumData as $data) {
            Addendums::create($data);
        }        

        return "Data inserted successfully for Addendum table";
    }

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
