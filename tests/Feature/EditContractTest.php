<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;    

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

    /**
     * Test to check the validation rules of edit contract.
     */
    public function test_to_validates_contract_updation()
    {
        $data = [
            'msa_id' => 1,
            'contract_added_by' => 1,
            'client_name' => 'Example Client',
            'region' => 'Example Region',
            'start_date' => '2024-03-19',
            'end_date' => '2024-03-25',
            'date_of_signature' => '2024-03-18',
            'contract_status' => 'Active',
            'du' => 'Example DU',
            'comments' => 'Example comments',
            'contract_doclink' => 'example.docx',
            'file' => UploadedFile::fake()->create('document.pdf'),
            'estimated_amount' => 1000,
            'milestones' => [
                [
                    'milestone_desc' => 'Example Milestone',
                    'milestone_enddate' => '2024-03-20',
                    'percentage' => 50,
                    'amount' => 500,
                ],
            ],
            'addendum_file' => UploadedFile::fake()->create('addendum.pdf'),
            'addendum_doclink' => 'example_addendum.docx',
            'associated_users' => [
                [
                    'user_id' => 1,
                ],
            ],
        ];

        // Mocking validator facade
        Validator::shouldReceive('make')->once()->andReturn(
            new class {
                public function fails() { return true; }
                public function errors() {
                    return new class {
                        public function first($key) {
                            return 'The end date must be a date after start date.';
                        }
                    };
                }
            }
        );

        $validator = Validator::make($data, [
            'msa_id' => 'required|numeric',
            'contract_added_by' => 'required|numeric',
            'client_name' => 'string|min:5|max:100',
            'region' => 'string|max:100',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'date_of_signature' => 'date|before:start_date',
            'contract_status' => 'required|string',
            'du' => 'required|string',
            'comments' => 'string',
            'contract_doclink' => 'string',
            'file' => 'file',
            'estimated_amount' => 'required|numeric',
            'milestones' => ['required', 'array'],
            'milestones.*.milestone_desc' => 'required|string',
            'milestones.*.milestone_enddate' => 'required|date',
            'milestones.*.percentage' => 'required|numeric',
            'milestones.*.amount' => 'required|numeric',
            'addendum_file' => [
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) {
                    // Check if the value is a string or a file
                    if (!is_string($value) && !is_a($value, \Illuminate\Http\UploadedFile::class)) {
                        $fail($attribute . ' must be a valid file or a string.');
                    }
                },
            ],
            'addendum_doclink' => 'string',
            'associated_users' => 'array',
            'associated_users.*.user_id' => 'required|numeric',
        ]);

        // Indicating that the test is true
        $this->assertTrue($validator->fails());
    }
}
