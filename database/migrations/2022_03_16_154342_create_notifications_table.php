<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('notifications', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->text('data');
            $table->unsignedTinyInteger('type');
            $table->unsignedMediumInteger('id_account');
            $table->string('action', 255);
            $table->dateTime('created_at')->default(DB::raw('current_timestamp()'))->unique();
            $table->dateTime('updated_at')->default(DB::raw('current_timestamp()'))
                  ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('notifications');
    }
}
