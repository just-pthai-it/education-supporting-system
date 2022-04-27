<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataVersionStudentsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('data_version_students', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id_student', 50)->primary();
            $table->unsignedSmallInteger('schedule')->default(0);
            $table->unsignedSmallInteger('exam_schedule')->default(0);
            $table->unsignedSmallInteger('notification')->default(0);
            $table->unsignedSmallInteger('module_score')->default(0);
            $table->timestamp('updated_at')->default(DB::raw('current_timestamp()'))
                  ->useCurrentOnUpdate();
            $table->foreign('id_student')->on('students')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('data_version_students');
    }
}
