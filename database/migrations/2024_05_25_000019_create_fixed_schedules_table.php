<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('fixed_schedules', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->unsignedMediumInteger('id_schedule');
            $table->date('new_date')->nullable();
            $table->string('new_shift', 50)->nullable();
            $table->string('new_id_room', 50)->nullable();
            $table->date('old_date');
            $table->string('old_shift', 50);
            $table->string('old_id_room', 50);
            $table->string('intend_time', 100)->nullable();
            $table->string('reason', 500);
            $table->string('reason_deny', 500)->nullable();
            $table->smallInteger('status')->default(0)->index();
            $table->dateTime('created_at')->default(DB::raw('current_timestamp()'));
            $table->dateTime('updated_at')->default(DB::raw('current_timestamp()'))
                  ->useCurrentOnUpdate();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('set_room_at')->nullable();
        });

        Schema::table('fixed_schedules', function ($table)
        {
            $table->foreign('id_schedule')->references('id')->on('schedules');
            $table->foreign('old_id_room')->references('id')->on('rooms');
            $table->foreign('new_id_room')->references('id')->on('rooms');
        });

    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('fixed_schedules');
    }
}
