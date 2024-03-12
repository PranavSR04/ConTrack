<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experion_id')->constrained('experion_employees');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('user_name',100);
            $table->string('user_mail',100);
            $table->string('user_designation',100)->nullable();
            $table->string('group_name',100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    
    $usersData = [
        [
            'experion_id' => 1,
            'role_id' => 1,
            'user_name' => 'Gokul Surendran',
            'user_mail' => 'gokul.surendran@experionglobal.com',
            'user_designation' => 'Project Manager',
            'group_name' => '',
            'is_active' => true
        ],
        [
            'experion_id' => 2,
            'role_id' => 2,
            'user_name' => 'Athul Nair',
            'user_mail' => 'athul.nair@experionglobal.com',
            'user_designation' => 'UX Designer',
            'group_name' => '',
            'is_active' => true
        ],
        [
            'experion_id' => 3,
            'role_id' => 3,
            'user_name' => 'Dantus George',
            'user_mail' => 'dantus.tom@experionglobal.com',
            'user_designation' => 'Finance Head',
            'group_name' => '',
            'is_active' => true
        ],
        [
            'experion_id' => 4,
            'role_id' => 2,
            'user_name' => 'Aneeka Geo',
            'user_mail' => 'aneeka.geo@experionglobal.com',
            'user_designation' => 'UX Designer',
            'group_name' => '',
            'is_active' => true
        ]
    ];

    foreach ($usersData as $userData) {
        $user = new User($userData);
        $user->save();
    }
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
