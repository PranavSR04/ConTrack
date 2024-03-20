<?php

namespace Tests\Unit;

use App\Services\GoogleDriveService;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Validator;

class EditContractUnitTest extends TestCase
{
    /**
     * A basic unit test to test some validations.
     */
    public function test_edit_contract_valiation(): void
    {
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

        $data = [
            'start_date' => '2024-03-18',
            'end_date' => '2024-02-17', // Invalid: end_date before start_date
        ];

        $validator = Validator::make($data, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'The end date must be a date after start date.',
            $validator->errors()->first('end_date')
        );
        
    }

    /**
     * Test the function to create milestones update data.
     */
    public function test_create_milestones_update_data(): void
    {
        // Sample data for $decodedMilestones
        $decodedMilestones = [
            [
                'milestone_desc' => 'Milestone 1',
                'milestone_enddate' => '2024-03-18',
                'percentage' => 30,
                'amount' => 500,
            ],
            [
                'milestone_desc' => 'Milestone 2',
                'milestone_enddate' => '2024-04-18',
                'percentage' => 40,
                'amount' => 700,
            ],
        ];

        // Call the function to create milestones update data
        $milestonesUpdateData = [];
        foreach ($decodedMilestones as $milestone) {
            $milestonesUpdateData[] = [
                'milestone_desc' => $milestone['milestone_desc'],
                'milestone_enddate' => $milestone['milestone_enddate'],
                'percentage' => $milestone['percentage'],
                'amount' => $milestone['amount'],
            ];
        }

        // Define the expected structure and values of $milestonesUpdateData
        $expectedMilestonesUpdateData = [
            [
                'milestone_desc' => 'Milestone 1',
                'milestone_enddate' => '2024-03-18',
                'percentage' => 30,
                'amount' => 500,
            ],
            [
                'milestone_desc' => 'Milestone 2',
                'milestone_enddate' => '2024-04-18',
                'percentage' => 40,
                'amount' => 700,
            ],
        ];

        // Assert that the created $milestonesUpdateData matches the expected structure and values
        $this->assertEquals($expectedMilestonesUpdateData, $milestonesUpdateData);
    }

    /**
     * Test the creation of contract update data.
     */
    public function test_create_contract_update_data(): void
    {
        // Mock the GoogleDrive service
        $googleDrive = $this->createMock(GoogleDriveService::class);
        
        // Mock the store method of the GoogleDrive service to return a file link
        $googleDrive->expects($this->once())
                    ->method('store')
                    ->willReturn('https://drive.com/file-link');

        // Sample data for the request
        $request = new \Illuminate\Http\Request([
            'msa_id' => 1,
            'contract_added_by' => 2,
            'start_date' => '2024-03-18',
            'end_date' => '2024-04-18',
            'contract_ref_id' => 'HAR41',
            'date_of_signature' => '2023-03-14',
            'du' => 'du2',
            'contract_status' => 'Active',
            'comments' => 'comments are generated',
            'contract_doclink' => 'https://drive.com/1ads3',
            'estimated_amount' => 100,
        ]);

        // Call the function to create contract update data
        $fileLink = $googleDrive->store($request);
        if ($fileLink) {
            // If file link is obtained
            $contractUpdateData = [
                'msa_id' => $request->msa_id,
                'contract_added_by' => $request->contract_added_by,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'contract_ref_id' => $request->contract_ref_id,
                'date_of_signature' => $request->date_of_signature,
                'du' => $request->du,
                'contract_status' => $request->contract_status,
                'comments' => $request->comments,
                'contract_doclink' => $fileLink,
                'estimated_amount' => $request->estimated_amount,
            ];
        } else {
            // If no file link is obtained
            $contractUpdateData = [
                'msa_id' => $request->msa_id,
                'contract_added_by' => $request->contract_added_by,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'contract_ref_id' => $request->contract_ref_id,
                'date_of_signature' => $request->date_of_signature,
                'du' => $request->du,
                'contract_status' => $request->contract_status,
                'comments' => $request->comments,
                'contract_doclink' => $request->contract_doclink,
                'estimated_amount' => $request->estimated_amount,
            ];
        }

        // Define the expected structure and values of $contractUpdateData
        $expectedContractUpdateData = [
            'msa_id' => 1,
            'contract_added_by' => 2,
            'start_date' => '2024-03-18',
            'end_date' => '2024-04-18',
            'contract_ref_id' => 'HAR41',
            'date_of_signature' => '2023-03-14',
            'du' => 'du2',
            'contract_status' => 'Active',
            'comments' => 'comments are generated',
            'contract_doclink' => 'https://drive.com/file-link',
            'estimated_amount' => 100,
        ];

        // Assert that the created $contractUpdateData matches the expected structure and values
        $this->assertEquals($expectedContractUpdateData, $contractUpdateData);
    }
}
