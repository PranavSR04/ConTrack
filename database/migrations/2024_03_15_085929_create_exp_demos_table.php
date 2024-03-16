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
        Schema::create('exp_demos', function (Blueprint $table) {
            $table->id();
            $table->string('u_id');
            $table->string('email_id');
            $table->timestamps();
        });



        DB::table('exp_demos')->insert([
            [
                "u_id" => "98884fbe-d647-472f-a636-2b1fa5c1fea6",
                "email_id" => "pranav.sr@sreegcloudgmail.onmicrosoft.com",
                "created_at" => now(),
                "updated_at" => now()
              
            ]

            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exp_demos');
    }
};
