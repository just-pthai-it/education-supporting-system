<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleClassesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('module_classes', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('number_plan');
            $table->unsignedTinyInteger('number_reality');
            $table->boolean('is_international');
            $table->unsignedTinyInteger('id_study_session');
            $table->string('id_module', 50);
            $table->string('id_teacher', 50)->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('module_classes');
    }
}
