<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('exam_schedules', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->string('id_module_class', 50);
            $table->string('method', 50);
            $table->string('start_at', 50);
            $table->string('end_at', 50);
            $table->unsignedTinyInteger('number_of_students');
            $table->string('id_room', 50)->nullable();
            $table->unique(['id_module_class', 'id_room']);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('exam_schedules');
    }
}
