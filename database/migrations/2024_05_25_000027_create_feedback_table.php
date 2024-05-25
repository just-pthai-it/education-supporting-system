<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('feedback', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->text('data');
            $table->unsignedTinyInteger('type');
            $table->boolean('is_bug');
            $table->unsignedMediumInteger('id_account');
            $table->dateTime('created_at')->default(DB::raw('current_timestamp()'));
        });

        Schema::table('feedback', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('feedback');
    }
}
