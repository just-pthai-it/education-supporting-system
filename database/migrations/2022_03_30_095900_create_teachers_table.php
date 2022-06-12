<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('teachers', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->boolean('is_female')->default(0);
            $table->dateTime('birth')->nullable();
            $table->string('university_teacher_degree', 200)->nullable();
            $table->boolean('is_head_of_department');
            $table->boolean('is_head_of_faculty');
            $table->boolean('is_active');
            $table->string('id_department', 50);
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('teachers');
    }
}
