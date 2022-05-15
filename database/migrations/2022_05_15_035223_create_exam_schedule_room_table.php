<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamScheduleRoomTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('exam_schedule_room', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->string('id_exam_schedule', 50);
            $table->string('id_room', 50);
            $table->unique(['id_exam_schedule', 'id_room']);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('exam_schedule_room');
    }
}
