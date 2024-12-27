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
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->string('codproducto',30)->charset('latin1')->collation('latin1_swedish_ci');;
            $table->string('idinvitado')->nullable();
            $table->integer('cantidad');
            $table->string('codcliente')->nullable();
            $table->boolean('es_invitado')->default(true);
            $table->string('idgrupocarrito')->nullable();
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->decimal('descuento', 10, 2)->default(0.00);
            $table->decimal('impuesto', 10, 2)->default(0.00);
            $table->decimal('porcdescuento', 5, 2)->default(0.00);
            $table->decimal('porcimpuesto', 5, 2)->default(0.00);
            $table->boolean('is_checked')->default(false);
            $table->string('slug')->nullable();
            $table->string('nombre')->nullable();
            $table->string('imagen')->nullable();
            $table->string('nombregrupo')->nullable();
            $table->timestamps();

            $table->foreign('idinvitado')->references('idinvitado')->on('invitados');
            $table->foreign('codproducto')->references('codproducto')->on('producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carritos');
    }
};
