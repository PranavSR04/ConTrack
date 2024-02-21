<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->nullable();
            $table->foreignId('msa_id')->constrained('msas')->nullable();
            $table->foreignId('performed_by')->constrained('users')->nullable();
            $table->string('action',25);
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
