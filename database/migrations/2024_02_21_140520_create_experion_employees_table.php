<?php

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
                "email_id" => "john.smith@experionglobal.com",
                "password" => bcrypt("password1"),
                "first_name" => "John",
                "middle_name" => "Doe",
                "last_name" => "Smith",
            ],
            [
                "email_id" => "jane.johnson@experionglobal.com",
                "password" => bcrypt("password2"),
                "first_name" => "Jane",
                "middle_name" => "Alice",
                "last_name" => "Johnson",
            ],
            [
                "email_id" => "michael.brown@experionglobal.com",
                "password" => bcrypt("password3"),
                "first_name" => "Michael",
                "middle_name" => "",
                "last_name" => "Brown",
            ],
            [
                "email_id" => "emily.taylor@experionglobal.com",
                "password" => bcrypt("password4"),
                "first_name" => "Emily",
                "middle_name" => "Grace",
                "last_name" => "Taylor",
            ],
            [
                "email_id" => "william.jones@experionglobal.com",
                "password" => bcrypt("password5"),
                "first_name" => "William",
                "middle_name" => "",
                "last_name" => "Jones",
            ],
            [
                "email_id" => "olivia.anderson@experionglobal.com",
                "password" => bcrypt("password6"),
                "first_name" => "Olivia",
                "middle_name" => "Mae",
                "last_name" => "Anderson",
            ],
            [
                "email_id" => "ethan.miller@experionglobal.com",
                "password" => bcrypt("password7"),
                "first_name" => "Ethan",
                "middle_name" => "",
                "last_name" => "Miller",
            ],
            [
                "email_id" => "dantus.tom@experionglobal.com",
                "password" => bcrypt("password8"),
                "first_name" => "Dantus",
                "middle_name" => "George",
                "last_name" => "Tom",
            ],
            [
                "email_id" => "abhi.j@experionglobal.com",
                "password" => bcrypt("password9"),
                "first_name" => "Abhi",
                "middle_name" => "",
                "last_name" => "J",
            ]
        ];

        foreach ($dataArray as $data) {
            DB::table('experion_employees')->insert($data);
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
