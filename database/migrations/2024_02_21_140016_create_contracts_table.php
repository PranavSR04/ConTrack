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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('msa_ref_id')->constrained('msas');
            $table->foreignId('contract_added_by')->constrained('users');
            $table->string('contract_ref_id',25);
            $table->string('contract_type',25);
            $table->date('date_of_signature');
            $table->string('comments')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('du');
            $table->string('contract_doclink');
            $table->double('estimated_amount');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
