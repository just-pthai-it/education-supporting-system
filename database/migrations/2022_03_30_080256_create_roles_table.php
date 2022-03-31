<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('roles', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedTinyInteger('id')->autoIncrement();
            $table->string('name', 100);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('roles');
    }
}
