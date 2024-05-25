<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::create('rooms', function (Blueprint $table)
        {
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->unsignedTinyInteger('capacity')->nullable();
            $table->unsignedTinyInteger('micro')->nullable();
            $table->unsignedTinyInteger('air_conditional')->nullable();
            $table->unsignedTinyInteger('projector')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('rooms');
    }
}
