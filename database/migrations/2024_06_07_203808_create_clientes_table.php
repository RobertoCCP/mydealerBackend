<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->string('codcliente', 50)->primary()->comment('Código de cliente');
            $table->string('codtipocliente', 10)->nullable()->comment('Código de tipo de cliente');
            $table->string('nombre', 300)->nullable()->comment('Nombre');
            $table->string('email', 250)->nullable()->comment('Correo electrónico');
            $table->string('pais', 60)->nullable()->comment('País');
            $table->string('provincia', 60)->nullable()->comment('Provincia');
            $table->string('ciudad', 60)->nullable()->comment('Ciudad');
            $table->string('codvendedor', 10)->nullable()->comment('Código de vendedor');
            $table->string('codformapago', 10)->nullable()->comment('Código de forma de pago');
            $table->char('estado', 1)->nullable()->comment('Estado A=Activo I=Inactivo');
            $table->double('limitecredito', 18, 2)->default(0.00)->comment('Cupo de crédito total');
            $table->double('saldopendiente', 18, 2)->default(0.00)->comment('Cupo de crédito disponible');
            $table->string('cedularuc', 20)->comment('Número de cédula o RUC');
            $table->string('codlistaprecio', 10)->nullable()->comment('Código de lista de precios asignada');
            $table->string('calificacion', 30)->nullable()->comment('Calificación del cliente');
            $table->string('nombrecomercial', 100)->nullable()->comment('Nombre Comercial');
            $table->string('login', 15)->nullable()->comment('Usuario para acceso');
            $table->string('password', 15)->nullable()->comment('Clave de acceso');

            // Índices
            $table->index('codformapago');
            $table->index('codtipocliente');
            $table->index('codvendedor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}
