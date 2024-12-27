<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rutadet', function (Blueprint $table) {
            $table->increments('codrutadet');
            $table->char('codruta', 20)->nullable();
            $table->string('codcliente', 20)->nullable();
            $table->integer('orden')->nullable();
            $table->string('coddireccionenvio', 50)->nullable();
            $table->integer('semana');
            $table->integer('diasemana');
            $table->string('codvendedor', 50)->nullable();
            $table->timestamps();

            $table->foreign('codruta')->references('codruta')->on('rutas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutadet');
    }
};
