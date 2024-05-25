<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('academic_years', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->unsignedTinyInteger('id_training_type');
        });

        Schema::table('academic_years', function ($table)
        {
            $table->foreign('id_training_type')->references('id')->on('training_types');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('academic_years');
    }
}
