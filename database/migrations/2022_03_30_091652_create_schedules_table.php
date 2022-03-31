<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('schedules', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->string('id_module_class', 50);
            $table->date('date');
            $table->string('shift', 50);
            $table->string('id_room', 50);
            $table->string('note', 1000);
            $table->dateTime('deleted_at')->nullable();
        });


        Schema::table('schedules', function ($table)
        {
            $table->foreign('id_module_class')->references('id')->on('module_classes');
            $table->foreign('id_room')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('schedules');
    }
}
