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
            $table->foreignId('contract_id')->nullable();
            $table->foreignId('msa_id')->nullable();
            $table->foreignId('performed_by')->nullable();
            $table->string('action',25);
            $table->timestamps();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('msa_id')->references('id')->on('msas');
            $table->foreign('performed_by')->references('id')->on('users');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
