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
        Schema::create('demousers', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
        // Insert default demo users
        DB::table('demousers')->insert([
            [
                "username" => "user1",
                "email" => "user1@example.com",
                "password" => bcrypt("password1"),
                "created_at" => now(),
                "updated_at" => now()
              
            ],
            [
                "username" => "user2",
                "email" => "user2@example.com",
                "password" => bcrypt("password2"),
                "created_at" => now(),
                "updated_at" => now()
          
            ],
            [
                "username" => "user3",
                "email" => "user3@example.com",
                "password" => bcrypt("password3"),
                "created_at" => now(),
                "updated_at" => now()
           
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demousers');
    }
};
