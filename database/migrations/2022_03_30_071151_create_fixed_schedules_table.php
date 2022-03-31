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
            $table->date('new_date');
            $table->string('new_shift', 50);
            $table->string('new_id_room', 50)->nullable();
            $table->date('old_date');
            $table->string('old_shift', 50);
            $table->string('old_id_room', 50);
            $table->string('reason', 500);
            $table->string('reason_deny', 500)->nullable();
            $table->tinyInteger('status')->default(0)->index();
            $table->dateTime('created_at')->default(DB::raw('current_timestamp()'));
            $table->dateTime('updated_at')->default(DB::raw('current_timestamp()'))
                  ->useCurrentOnUpdate();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('set_room_at')->nullable();
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
