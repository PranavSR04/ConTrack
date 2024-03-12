<?php

use App\Models\FixedFeeContracts;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ff_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts');
            $table->text('milestone_desc');
            $table->date('milestone_enddate');
            $table->decimal('percentage');
            $table->double('amount');
            $table->timestamps();
        });
        $dummydata_ff = [
            [
                'contract_id' => 1,
                'milestone_desc' => 'On BRD Signoff',
                'milestone_enddate' => now()->addMonths(7), //'2024-10-20',
                'percentage' => 20,
                'amount' => 3000.00,
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'On API creation',
                'milestone_enddate' => now()->addMonths(19), //'2025-08-15'
                'percentage' => 40,
                'amount' => 6000.00,
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'On Deployment',
                'milestone_enddate' => now()->addMonths(34), // '2027-01-01'
                'percentage' => 40,
                'amount' => 6000.00,
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'On BRD Signoff',
                'milestone_enddate' => now()->subMonths(6) , //'2023-09-10'
                'percentage' => 25,
                'amount' => 3125.00,
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'On API creation',
                'milestone_enddate' => now()->addMonths(6), // '2024-09-15'
                'percentage' => 25,
                'amount' => 3125.00,
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'On Completion of UI',
                'milestone_enddate' => now()->addMonths(9) , // '2024-12-19'
                'percentage' => 50,
                'amount' => 6250.00,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'On completion of figma design',
                'milestone_enddate' => now()->addMonths(15) , // '2025-05-20'
                'percentage' => 20,
                'amount' => 3000.00,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'On completion of UI',
                'milestone_enddate' => now()->addMonths(30) , // '2026-09-15'
                'percentage' => 40,
                'amount' => 6000.00,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'On Deployment',
                'milestone_enddate' => now()->addMonths(34), // '2027-01-01'
                'percentage' => 40,
                'amount' => 6000.00,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'On signoff of BRD',
                'milestone_enddate' => now()->addMonths(9) , // '2024-12-20'
                'percentage' => 25,
                'amount' => 7500.00,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'On completion figma design',
                'milestone_enddate' => now()->addMonths(24) ,
                'percentage' => 25,
                'amount' => 7500.00,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'On completion of UI',
                'milestone_enddate' => now()->addMonths(36),
                'percentage' => 25,
                'amount' => 7500.00,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'On Deployment',
                'milestone_enddate' => now()->addMonths(50),
                'percentage' => 25,
                'amount' => 7500.00,
            ],
            [
                'contract_id' => 11,
                'milestone_desc' => 'On signoff of BRD',
                'milestone_enddate' => now()->subMonths(10) ,
                'percentage' => 10,
                'amount' => 2900.00,
            ],
            [
                'contract_id' => 11,
                'milestone_desc' => 'On completion of UI',
                'milestone_enddate' => now()->subMonths(2) ,
                'percentage' => 30,
                'amount' => 8700.00,
            ],
            [
                'contract_id' => 11,
                'milestone_desc' => 'On Deployement',
                'milestone_enddate' => now()->addMonths(20),
                'percentage' => 60,
                'amount' => 17400.00,
            ],
            [
                'contract_id' => 13,
                'milestone_desc' => 'On signoff of BRD',
                'milestone_enddate' => now()->subMonths(22) ,
                'percentage' => 30,
                'amount' => 6000.00,
            ],
            [
                'contract_id' => 13,
                'milestone_desc' => 'On completion of UI',
                'milestone_enddate' => now()->subMonths(12) ,
                'percentage' => 20,
                'amount' => 4000.00,
            ],
            [
                'contract_id' => 13,
                'milestone_desc' => 'On completion of testing',
                'milestone_enddate' => now()->addMonths(5),
                'percentage' => 50,
                'amount' => 10000.00,
            ]

        ];
        foreach ($dummydata_ff as $ffData) {
            $ffData = new FixedFeeContracts($ffData);
            $ffData->save();
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ff_contracts');
    }
};
