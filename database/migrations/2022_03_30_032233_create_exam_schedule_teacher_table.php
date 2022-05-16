<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamScheduleTeacherTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('exam_schedule_teacher', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->unsignedMediumInteger('id_exam_schedule');
            $table->string('id_teacher', 50);
            $table->unique(['id_exam_schedule', 'id_teacher']);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('exam_schedules_teacher');
    }
}
