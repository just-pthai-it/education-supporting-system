<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumModuleTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('curriculum_module', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->unsignedTinyInteger('id_curriculum');
            $table->string('id_module', 50);
            $table->unique(['id_curriculum', 'id_module']);
        });

        Schema::table('curriculum_module', function ($table)
        {
            $table->foreign('id_curriculum')->references('id')->on('curriculums');
            $table->foreign('id_module')->references('id')->on('modules');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('curriculum_module');
    }
}
