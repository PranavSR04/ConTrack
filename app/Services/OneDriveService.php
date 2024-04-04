<?php

namespace App\Services;

use App\ServiceInterfaces\OneDriveInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use GuzzleHttp\Client;

use Microsoft\Graph\Exception\GraphException;

class OneDriveService implements OneDriveInterface
{
    // Function to generate token
    public function token()
    {
        // Retrieve credentials from config
        $clientId = \Config('services.onedrive.client_id');
        $clientSecret = \Config('services.onedrive.client_secret');
        $tenantId = \Config('services.onedrive.tenant_id');

        // Make a POST request to obtain an access token
        // $response = Http::asForm()->post("https://login.microsoftonline.com/$tenantId/oauth2/token", [
        //     'grant_type' => 'client_credentials',
        //     'client_id' => $clientId,
        //     'client_secret' => $clientSecret,
        //     'resource' => 'https://graph.microsoft.com'
        // ]);
        $response = Http::asForm()->post("https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token", [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default'
        ]);

        // Decode the JSON response
        $data = json_decode($response->getBody(), true);

        // Access token is in 'access_token' field
        $accessToken = $data['access_token'];
        return $accessToken;
    }

    // Function to store data in OneDrive and return a link
    public function store(Request $request)
    {
        if ($request->file !== '') {
            $validation = $request->validate([
                'file' => 'file|required',
            ]);

            try {
                $accessToken = $this->token();

                $graph = new Graph();
                $graph->setAccessToken($accessToken);

                // Get the file instance from the request
                $file = $request->file('file');

                // Get the original file name
                $fileName = $file->getClientOriginalName();

                // Read the file contents
                $fileContents = file_get_contents($file->getPathname());

                // Specify the folder path where you want to upload the file
                $folderPath = '/'; // Root folder

                // Specify the destination file path
                $destinationPath = $folderPath . $fileName;

                $userData = [
                    'accountEnabled' => true,
                    'displayName' => 'teamecho2024',
                    'mailNickname' => 'teamecho2024',
                    'userPrincipalName' => 'teamecho2024@contoso.onmicrosoft.com',
                    'passwordProfile' => [
                        'forceChangePasswordNextSignIn' => true,
                        'password' => 'teamecho@2024'
                    ]
                ];
                
                $response = Http::withToken($accessToken)->post('https://graph.microsoft.com/v1.0/users', $userData);
                return $response;

                // Upload the file to OneDrive
                $response = Http::withToken($accessToken)
                    ->attach('file', $fileContents, $fileName)
                    ->post("https://graph.microsoft.com/v1.0/drive/root:/$destinationPath:/content");

                // Decode the JSON response
                $data = $response->json();

                return response()->json($data);


                // $client = new Client();
                // $response = $client->post("https://graph.microsoft.com/v1.0/drive/root:/$destinationPath:/content", [
                //     'headers' => [
                //         'Authorization' => 'Bearer ' . $accessToken,
                //         'Content-Type' => 'application/json'
                //     ],
                //     'body' => $fileContents
                // ]);

                // return response()->json($response->getBody()->getContents());



            } catch (GraphException $e) {
                // Handle Graph API errors
                return response()->json(['error' => $e->getMessage()], 500);
            } catch (\Throwable $e) {
                // Handle other errors
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
    }
}