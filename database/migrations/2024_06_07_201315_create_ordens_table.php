<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdensTable extends Migration
{
    public function up()
    {
        Schema::create('orden', function (Blueprint $table) {
            $table->bigIncrements('srorden')->comment('Numero de pedido autonumérico');
            $table->string('codcliente', 20)->nullable()->comment('Código del cliente');
            $table->date('fecha')->nullable()->comment('Fecha del pedido (del servidor)');
            $table->dateTime('fechaweb')->nullable()->comment('Fecha hora del pedido (del servidor)');
            $table->string('codformaenvio', 10)->nullable()->comment('Código de forma de envío');
            $table->string('codformapago', 10)->nullable()->comment('Código de forma de pago');
            $table->string('idorden', 35)->nullable()->comment('Id único del pedido relacionado al móvil');
            $table->string('codvendedor', 10)->nullable()->comment('Código del vendedor');
            $table->string('coddireccionenvio', 50)->nullable()->comment('Código de dirección de envío');
            $table->string('numordenerp', 20)->nullable()->comment('Número de pedido en el ERP');
            $table->string('observaciones', 255)->nullable()->comment('Observaciones');
            $table->string('loginusuario', 15)->nullable()->comment('Usuario que registro el pedido');
            $table->string('referencia1', 60)->nullable()->comment('Referencia 1');
            $table->string('referencia2', 100)->nullable()->comment('(no usado)');
            $table->double('subtotal', 18, 2)->nullable()->comment('Subtotal del pedido');
            $table->double('descuento', 18, 2)->nullable()->comment('Descuento');
            $table->double('impuesto', 18, 2)->nullable()->comment('Impuesto');
            $table->double('total', 18, 2)->nullable()->comment('Total (subtotal – descuento + impuesto)');
            $table->string('estado', 60)->nullable()->comment('N=No enviado E=Enviado');
            $table->string('origen', 3)->nullable()->comment('M = Móvil');
            $table->string('errorws', 255)->default('')->comment('Error en la transferencia al ERP');
            $table->dateTime('fechamovil')->nullable()->comment('Fecha hora que envió el móvil');
            $table->dateTime('fechaenvioerp')->nullable()->comment('Fecha hora enviado al ERP');
            $table->timestamps();

            // Indexes
            $table->index('codvendedor');
            $table->index('loginusuario');
            $table->index('codcliente');
            $table->index('codformaenvio');
            $table->index('codformapago');
            $table->index('coddireccionenvio');
            $table->index('idorden');
            $table->index('fecha');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orden');
    }
}
