<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('classes', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->string('id_academic_year', 50);
            $table->string('id_faculty', 50);
        });

        Schema::table('classes', function ($table)
        {
            $table->foreign('id_academic_year')->references('id')->on('academic_years');
            $table->foreign('id_faculty')->references('id')->on('faculties');
        });

    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('classes');
    }
}
