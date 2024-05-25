<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('permission_role', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->primary(['id_permission', 'id_role']);
            $table->unsignedTinyInteger('id_permission');
            $table->unsignedTinyInteger('id_role');
            $table->boolean('is_granted');
        });

        Schema::table('permission_role', function ($table)
        {
            $table->foreign('id_permission')->references('id')->on('permissions');
            $table->foreign('id_role')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('permission_role');
    }
}
