<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdendetsTable extends Migration
{
    public function up()
    {
        Schema::create('ordendet', function (Blueprint $table) {
            $table->bigIncrements('numlinea')->comment('Id autonumérico');
            $table->string('idorden', 35)->default('')->comment('Id único del pedido relacionado al móvil');
            $table->bigInteger('srorden')->nullable()->comment('Numero de pedido');
            $table->string('codproducto', 30)->nullable()->comment('Código de producto');
            $table->double('cantidad', 14, 2)->nullable()->comment('Cantidad');
            $table->double('precio', 18, 4)->nullable()->comment('Precio');
            $table->double('descuento', 18, 10)->default(0.0000000000)->comment('%descuento por política');
            $table->double('subtotal', 18, 2)->default(0.00)->comment('Subtotal (cantidad * precio – descuentos)');
            $table->unsignedInteger('orden')->nullable()->comment('Numero de línea de detalle');
            $table->double('descuentoval', 18, 2)->nullable()->comment('Valor descuento total');
            $table->double('impuesto', 18, 4)->nullable()->comment('Valor del impuesto');
            $table->double('total', 18, 2)->nullable()->comment('Subtotal + impuesto');
            $table->string('codunidadmedida', 50)->nullable()->comment('Código de unidad de medida');
            $table->double('descadicional', 18, 2)->nullable()->comment('Valor de descuento adicional');
            $table->double('porcdescuentoadic', 18, 4)->nullable()->comment('%descuento adicional otorgado por vendedor');
            $table->double('porcdescuentotal', 18, 4)->nullable()->comment('%descuento total');
            $table->decimal('porcimpuesto', 12, 2)->default(0.00)->comment('% de impuesto');
            $table->timestamps();

            // Indexes
            $table->index('srorden');
            $table->index('codproducto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ordendet');
    }
}
