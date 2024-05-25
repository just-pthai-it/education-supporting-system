<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearMajorTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('academic_year_major', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->primary(['id_academic_year', 'id_major']);
            $table->string('id_academic_year', 50);
            $table->unsignedTinyInteger('id_major');
            $table->unsignedTinyInteger('id_curriculum')->nullable();
        });

        Schema::table('academic_year_major', function ($table)
        {
            $table->foreign('id_academic_year')->references('id')->on('academic_years');
            $table->foreign('id_major')->references('id')->on('majors');
            $table->foreign('id_curriculum')->references('id')->on('curriculums');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('academic_year_major');
    }
}
