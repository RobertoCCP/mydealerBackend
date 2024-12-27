<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gpshorario', function (Blueprint $table) {
            $table->increments('numdiasemana');
            $table->string('nombredia', 15)->charset('latin1')->collation('latin1_swedish_ci');
            $table->time('horaini');
            $table->time('horafin');
            $table->integer('frecuenciatoma');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpshorario');
    }
};
