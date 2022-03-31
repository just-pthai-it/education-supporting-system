<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('accounts', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->unsignedMediumInteger('id')->autoIncrement();
            $table->string('username', 50)->unique();
            $table->string('password', 100);
            $table->string('qldt_password', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('phone', 100)->nullable()->unique();
            $table->unsignedTinyInteger('id_role');
            $table->string('accountable_type', 100);
            $table->string('accountable_id', 50)->unique();
            $table->dateTime('created_at')->default(DB::raw('current_timestamp()'));
            $table->dateTime('updated_at')->default(DB::raw('current_timestamp()'));
            $table->binary('uuid', 16)->unique();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('accounts');
    }
}
