<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_type');
        });

        // Insert some roles
        $adminRoles = [
            "Super Admin",
            "Moderator",
            "Transact Admin",
            "Finance Admin"
        ];
        
        self::loadRoles($adminRoles);
    }
    
    private function loadRoles($rolesType) {
        foreach($rolesType as $role) {
            DB::table('admin_roles')->insert(['role_type' => $role]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_roles');
    }
};