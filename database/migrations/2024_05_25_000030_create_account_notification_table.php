<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountNotificationTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('account_notification', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->unsignedMediumInteger('id_account');
            $table->unsignedMediumInteger('id_notification');
            $table->dateTime('read_at')->nullable();
            $table->unique(['id_account', 'id_notification']);
        });

        Schema::table('account_notification', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_notification')->references('id')->on('notifications');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('account_notification');
    }
}
