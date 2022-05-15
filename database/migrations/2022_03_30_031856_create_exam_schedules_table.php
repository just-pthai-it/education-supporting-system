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
            $table->string('id', 50)->primary();
            $table->string('method', 50);
            $table->string('start_at', 50);
            $table->string('end_at', 50);
            $table->unsignedTinyInteger('number_of_students');
            $table->string('note', 1000)->default('');
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
