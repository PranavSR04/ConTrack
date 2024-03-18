<?php

use App\Models\Contracts;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('msa_id');
            $table->foreignId('contract_added_by')->constrained('users');
            $table->string('contract_ref_id', 25);
            $table->string('contract_type', 25);
            $table->date('date_of_signature');
            $table->string('comments')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('du');
            $table->longText('contract_doclink');
            $table->double('estimated_amount');
            $table->string('contract_status')->default("Active");
            $table->timestamps();
            $table->foreign('msa_id')->references('id')->on('msas');
        });

        $contractsDataArray = [
            [
                'contract_ref_id' => 'AGF7',
                'msa_id' => 1,
                'contract_added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->addMonths(2),
                'comments' => " view document to see further milestone data",
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(36),
                'du' => 'DU1',
                'estimated_amount' => 1500000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Closed',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'A166',
                'msa_id' => 2,
                'contract_added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(10),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now()->subMonths(9),
                'end_date' => now()->addMonths(10),
                'du' => 'DU2',
                'estimated_amount' => 1250000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'On Progress',
                "created_at" => now(),
                "updated_at" => now()

            ],
            [
                'contract_ref_id' => 'ABC1',
                'msa_id' => 1,
                'contract_added_by' => 4,
                'contract_type' => "FF",
                'date_of_signature' => now()->addMonths(12),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(13),
                'end_date' => now()->addMonths(36),
                'du' => 'DU3',
                'estimated_amount' => 1500000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
           
            [
                'contract_ref_id' => 'AN21',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->addMonths(7),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->addMonths(8),
                'end_date' => now()->addMonths(48),
                'du' => 'DU4',
                'estimated_amount' => 3000000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'N621',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(10),
                'comments' => "File also available in drive",
                'start_date' =>  now()->addMonths(12),
                'end_date' => now()->addMonths(48),
                'du' => 'DU1',
                'estimated_amount' => 2400000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1vb6H0Qc6m7YFqJ6nZ_7Ra6h8SWzbOu8h/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'A091',
                'msa_id' => 6,
                'contract_added_by' => 4,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(6),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->addMonths(7),
                'end_date' => now()->addMonths(29),
                'du' => 'DU2',
                'estimated_amount' => 4000000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1yJf_Ras4QgDxCQGOA_AEJpwK3Z1aZohp/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'M921',
                'msa_id' => 8,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(2),
                'comments' => "Contact me if it requires further change",
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(63),
                'du' => 'DU3',
                'estimated_amount' => 4500000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1vb6H0Qc6m7YFqJ6nZ_7Ra6h8SWzbOu8h/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB1',
                'msa_id' => 10,
                'contract_added_by' => 4,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(1),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(39),
                'du' => 'DU4',
                'estimated_amount' => 1400000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1yJf_Ras4QgDxCQGOA_AEJpwK3Z1aZohp/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB2',
                'msa_id' => 12,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(1),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(64),
                'du' => 'DU1',
                'estimated_amount' => 1500000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1vb6H0Qc6m7YFqJ6nZ_7Ra6h8SWzbOu8h/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB3',
                'msa_id' => 10,
                'contract_added_by' => 4,
                'contract_type' => "FF",
                'date_of_signature' => now()->addMonths(1),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(40),
                'du' => 'DU2',
                'estimated_amount' => 1600000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB4',
                'msa_id' => 11,
                'contract_added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(13),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(12),
                'end_date' => now()->addMonths(24),
                'du' => 'DU3',
                'estimated_amount' =>2900000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'On Progress',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB5',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->addMonths(4),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(5),
                'end_date' => now()->addMonths(65),
                'du' => 'DU4',
                'estimated_amount' => 3000000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB6',
                'msa_id' => 9,
                'contract_added_by' => 4,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(25),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(24),
                'end_date' => now()->addMonths(4),
                'du' => 'DU1',
                'estimated_amount' => 2000000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'CON64',
                'msa_id' => 9,
                'contract_added_by' => 1,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(24),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(14),
                'end_date' => now()->addWeeks(1),
                'du' => 'DU1',
                'estimated_amount' => 2600000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Expiring',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'TM001',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->subMonths(24),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(14),
                'end_date' => now()->addWeeks(1),
                'du' => 'DU1',
                'estimated_amount' => 1600000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Expiring',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'FR33',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->subMonths(4),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(5),
                'du' => 'DU1',
                'estimated_amount' => 1600000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'On Progress',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'EX33',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->subMonths(24),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(22),
                'end_date' => now()->subMonths(18),
                'du' => 'DU1',
                'estimated_amount' => 1600000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Expired',
                "created_at" => now(),
                "updated_at" => now()
            ],
        ];

        foreach ($contractsDataArray as $contractData) {
            $contractsData = new Contracts($contractData);
            $contractsData->save();
        }    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
