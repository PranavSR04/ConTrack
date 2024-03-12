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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name',30);
            $table->string('role_access',150);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
         // Insert default roles
         DB::table('roles')->insert([
            [
                "role_name" => "Admin",
                "role_access" => "Can edit and view contracts",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "role_name" => "Reader",
                "role_access" => "Can view contracts",
                "is_active" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "role_name" => "Super Admin",
                "role_access" => "Full Access",
                "is_active" => true,
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
        Schema::dropIfExists('roles');
    }
};
