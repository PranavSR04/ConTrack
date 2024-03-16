<?php

use App\Models\Addendums;
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
        Schema::create('addendums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts');
            $table->longText('addendum_doclink');
            $table->timestamps();
        });

        $addendum = [
            [
                "contract_id"=>1,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>2,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>3,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>5,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>6,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>7,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>11,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
            [
                "contract_id"=>13,
                "addendum_doclink"=> "https://drive.google.com/file/d/1I41krTN2C97FTyTBRozFNO2tGOMxB9QL/view?usp=sharing"
            ],
        ];
        foreach ($addendum as $addendumData) {
            $addendumData = new Addendums($addendumData);
            $addendumData->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addendums');
    }
};
