<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTagTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('account_tag', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->unsignedMediumInteger('id_account');
            $table->unsignedSmallInteger('id_tag');
            $table->unique(['id_account', 'id_tag']);
        });

        Schema::table('account_tag', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_tag')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('account_tag');
    }
}
