<?php

use App\Models\ExperionEmployees;
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
        Schema::create('experion_employees', function (Blueprint $table) {
            $table->id();
            $table->string('email_id',100);
            $table->string('first_name',50);
            $table->string('middle_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->timestamps();
        });

        $dataArray = [
            [
                "email_id" => "gokul.surendran@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Gokul",
                "middle_name" => "",
                "last_name" => "Surendran",
            ],
            [
                "email_id" => "athul.nair@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Athul",
                "middle_name" => "",
                "last_name" => "Nair",
            ],
            [
                "email_id" => "dantus.tom@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Dantus",
                "middle_name" => "George",
                "last_name" => "Tom",
            ],
            [
                "email_id" => "aneeka.geo@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Aneeka",
                "middle_name" => "",
                "last_name" => "Geo",
            ],
            [
                "email_id" => "treesa.james@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Treesa",
                "middle_name" => "",
                "last_name" => "James",
            ],
            [
                "email_id" => "pranav.sr@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Pranav",
                "middle_name" => "S",
                "last_name" => "R",
            ],
            [
                "email_id" => "boby.benny@sreegcloudgmail.onmicrosoft.com",
                "first_name" => "Boby",
                "middle_name" => "",
                "last_name" => "Benny",
            ],
            [
                "email_id" => "gokulsurendran29@gmail.com",
                "first_name" => "Gokul",
                "middle_name" => "K",
                "last_name" => "Radakrishnan",
            ],
            [
                "email_id" => "dantusgeorgetom@gmail.com",
                "first_name" => "George",
                "middle_name" => "",
                "last_name" => "Tom",
            ]
        ];

        foreach ($dataArray as $data) {
            ExperionEmployees::create($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experion_employees');
    }
};
