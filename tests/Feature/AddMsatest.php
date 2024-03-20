<?php

namespace Tests\Feature;

use App\Services\GoogleDriveService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddMsatest extends TestCase
{
    public function test_add_msa(): void
    {
        $this->withoutMiddleware();

              $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
        ])->post('/api/msa/add/1', [
            'msa_ref_id' => 'MSA099',
            'client_name' => 'Sun Solutions',
            'region' => 'Italy',
            'start_date' => '2024-03-15',
            'end_date' => '2025-03-31',
            'comments' => 'Msa Added',
            'file' => 'MSA File',
        ]);
        

        $response->assertStatus(200);

    }
    
   

}
