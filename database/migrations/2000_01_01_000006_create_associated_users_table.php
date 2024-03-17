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
        Schema::create('associated_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        $data = [
            ['contract_id' => 1, 'user_id' => 1],
            ['contract_id' => 1, 'user_id' => 2],
            ['contract_id' => 1, 'user_id' => 3],
            ['contract_id' => 1, 'user_id' => 4],
            ['contract_id' => 2, 'user_id' => 1],
            ['contract_id' => 2, 'user_id' => 2],
            ['contract_id' => 2, 'user_id' => 3],
            ['contract_id' => 3, 'user_id' => 1],
            ['contract_id' => 3, 'user_id' => 4],
            ['contract_id' => 4, 'user_id' => 1],
            ['contract_id' => 4, 'user_id' => 2],
            ['contract_id' => 4, 'user_id' => 3],
            ['contract_id' => 4, 'user_id' => 4],
            ['contract_id' => 5, 'user_id' => 3],
            ['contract_id' => 5, 'user_id' => 4],
        ];
        
        // Insert data into associated_users table
        DB::table('associated_users')->insert($data);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associated_users');
    }
};
