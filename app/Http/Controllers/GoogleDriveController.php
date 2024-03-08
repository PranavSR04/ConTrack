<?php

namespace App\Http\Controllers;

use App\Models\Contracts;
use App\Models\MSAs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleDriveController extends Controller
{
    // Function to generate token
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
        $accessToken = json_decode((string) $response->getBody(), true)['access_token'];

        return $accessToken;
    }

    // Function to store data in drive and return a link
    public function store(Request $request)
    {
        if($request->file!==''){
            $validation = $request->validate([
                'file' => 'file|required',
            ]);
    
            $accessToken = $this->token();
            // dd($accessToken);
            $name = $request->file->getClientOriginalName();
            //$mime=$request->file->getClientMimeType();
    
            $path = $request->file->getRealPath();
    
            $response = Http::withToken($accessToken)
                ->attach('data', file_get_contents($path), $name)
                ->withHeaders([
                    'Content-Type' => 'application/pdf',
                ])
                ->post('https://www.googleapis.com/upload/drive/v3/files', [
                    'name' => $name,
                ]);
    
            if ($response->successful()) {
    
                $file_id = json_decode($response->body())->id;
                $fileLink = "https://drive.google.com/file/d/{$file_id}";
                
                // return $response->json(["message"=>"File Uploaded to Google Drive"]);
                return $fileLink;
            } else {
                // return $response->json(["error"=>"Couldn't upload to Google Drive"]);
                return response(["error" => "Couldn't get file link"]);
            }
        }
        

    }
}
