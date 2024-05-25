<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('terms', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedTinyInteger('id')->autoIncrement();
            $table->string('name', 100);
            $table->unsignedTinyInteger('id_school_year');
        });

        Schema::table('terms', function ($table)
        {
            $table->foreign('id_school_year')->references('id')->on('school_years');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('terms');
    }
}
