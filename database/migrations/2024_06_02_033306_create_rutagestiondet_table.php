<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_rutagestiondet_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutagestiondetTable extends Migration
{
    public function up()
    {
        Schema::create('rutagestiondet', function (Blueprint $table) {
            $table->increments('codrutagestiondet');
            $table->integer('codrutagestion')->unsigned();
            $table->dateTime('horaini')->nullable();
            $table->dateTime('horafin')->nullable();
            $table->char('codtipogestion', 5);
            $table->string('posiciongps', 100)->nullable();
            $table->timestamps();

            $table->foreign('codrutagestion')->references('codrutagestion')->on('rutagestion')->onDelete('cascade');
            $table->index('codrutagestion');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutagestiondet');
    }
}
