<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudySessionsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('study_sessions', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedTinyInteger('id')->autoIncrement();
            $table->string('name', 100);
            $table->unsignedTinyInteger('id_term');
        });

        Schema::table('study_sessions', function ($table)
        {
            $table->foreign('id_term')->references('id')->on('terms');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('study_sessions');
    }
}
