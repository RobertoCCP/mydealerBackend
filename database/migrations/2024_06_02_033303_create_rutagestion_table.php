<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_rutagestion_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutagestionTable extends Migration
{
    public function up()
    {
        Schema::create('rutagestion', function (Blueprint $table) {
            $table->increments('codrutagestion');
            $table->string('codvendedor', 10);
            $table->date('fecha');
            $table->string('codruta', 5)->nullable();
            $table->string('codcliente', 20);
            $table->dateTime('horaini')->nullable();
            $table->dateTime('horafin')->nullable();
            $table->char('codestado', 1)->nullable();
            $table->string('coddireccionenvio', 50)->nullable();
            $table->date('fechaproxvisita')->nullable();
            $table->string('observacion', 255)->nullable();
            $table->timestamps();

            $table->index(['codvendedor', 'fecha']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutagestion');
    }
}
