<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('orden', function (Blueprint $table) {
            $table->bigInteger('pedido_renegociado')->nullable()->after('srorden');
            $table->string('codsucursal', 10)->nullable()->after('pedido_renegociado');
            $table->string('entregapedido', 200)->nullable()->after('codsucursal');
            $table->string('tipopedido', 3)->nullable()->after('entregapedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('orden', function (Blueprint $table) {
            $table->dropColumn('pedido_renegociado');
            $table->dropColumn('codsucursal');
            $table->dropColumn('tipopedido');
            $table->dropColumn('entregapedido');
        });
    }
};
