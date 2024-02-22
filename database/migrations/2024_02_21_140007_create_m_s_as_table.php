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
        Schema::create('msas', function (Blueprint $table) {
            $table->id();

            $table->string('msa_ref_id',25);
            $table->foreignId('added_by')->constrained('users');
            $table->string('client_name',100);
            $table->string('region',100);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('comments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('msa_doclink');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msas');
    }
};
