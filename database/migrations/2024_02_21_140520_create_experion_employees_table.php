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
            $table->string('password',100);
            $table->string('first_name',50);
            $table->string('middle_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->timestamps();
        });

        $dataArray = [
            [
                "email_id" => "gokul.surendran@experionglobal.com",
                "password" => bcrypt("password1"),
                "first_name" => "Gokul",
                "middle_name" => "",
                "last_name" => "Surendran",
            ],
            [
                "email_id" => "athul.nair@experionglobal.com",
                "password" => bcrypt("password2"),
                "first_name" => "Athul",
                "middle_name" => "",
                "last_name" => "Nair",
            ],
            [
                "email_id" => "dantus.tom@experionglobal.com",
                "password" => bcrypt("password3"),
                "first_name" => "Dantus",
                "middle_name" => "George",
                "last_name" => "Tom",
            ],
            [
                "email_id" => "aneeka.geo@experionglobal.com",
                "password" => bcrypt("password4"),
                "first_name" => "Aneeka",
                "middle_name" => "",
                "last_name" => "Geo",
            ],
            [
                "email_id" => "treesa.james@experionglobal.com",
                "password" => bcrypt("password5"),
                "first_name" => "Treesa",
                "middle_name" => "",
                "last_name" => "James",
            ],
            [
                "email_id" => "pranav.sr@experionglobal.com",
                "password" => bcrypt("password6"),
                "first_name" => "Pranav",
                "middle_name" => "S",
                "last_name" => "R",
            ],
            [
                "email_id" => "boby.benny@experionglobal.com",
                "password" => bcrypt("password7"),
                "first_name" => "Boby",
                "middle_name" => "",
                "last_name" => "Benny",
            ],
            [
                "email_id" => "gokulsurendran29@gmail.com",
                "password" => bcrypt("password8"),
                "first_name" => "Gokul",
                "middle_name" => "S",
                "last_name" => "K",
            ],
            [
                "email_id" => "dantusgeorgetom@gmail.com",
                "password" => bcrypt("password9"),
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
