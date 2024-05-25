<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('modules', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->unsignedTinyInteger('credit');
            $table->unsignedTinyInteger('semester');
            $table->unsignedTinyInteger('theory')->nullable();
            $table->unsignedTinyInteger('exercise')->nullable();
            $table->unsignedTinyInteger('project')->nullable();
            $table->unsignedTinyInteger('experiment')->nullable();
            $table->unsignedSmallInteger('practice')->nullable();
            $table->unsignedTinyInteger('option');
            $table->string('id_department', 50);
        });

        Schema::table('modules', function ($table)
        {
            $table->foreign('id_department')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('modules');
    }
}
