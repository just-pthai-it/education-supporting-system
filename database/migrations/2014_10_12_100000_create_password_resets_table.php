<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('password_resets', function (Blueprint $table)
        {
            $table->string('email')->primary();
            $table->string('token')->index();
            $table->dateTime('created_at')->default(DB::raw('current_timestamp()'));
            $table->dateTime('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('password_resets');
    }
}
