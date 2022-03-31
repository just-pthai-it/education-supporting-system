<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleClassStudentTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('module_class_student', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->primary(['id_module_class', 'id_student']);
            $table->string('id_module_class', 50);
            $table->string('id_student', 50);
            $table->string('evaluation', 100)->nullable();
            $table->decimal('process_score', 4, 2)->nullable();
            $table->decimal('test_score', 4, 2)->nullable();
            $table->decimal('final_score', 4, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('module_class_student');
    }
}
