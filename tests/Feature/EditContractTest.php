<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class EditContractTest extends TestCase
{
    /**
     * Test to check wheater close contract functionality works as expected.
     */
    public function test_to_check_close_contract(): void
    {
        $this->withoutMiddleware();
        // Mock request data
        $requestData = [
            'contract_status' => 'Closed', // Example of closing contract
        ];

        // Call the endpoint
        $randomNumber = mt_rand(1, 10);
        dump($randomNumber);
        $response = $this->json('POST', "api/contracts/edit/{$randomNumber}", $requestData);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Contract Closed',
            ]);

    }
}
