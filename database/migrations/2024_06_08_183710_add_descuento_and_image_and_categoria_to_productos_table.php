<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(
            'producto',
            function (Blueprint $table) {
                if (!Schema::hasColumn('producto', 'porcdescuento')) {
                    $table->decimal('porcdescuento', 12, 2)->default(0)->comment('Porcentaje de descuento')->after('porcimpuesto');
                }
                if (!Schema::hasColumn('producto', 'imagen')) {
                    $table->string('imagen')->nullable()->after('porcdescuento')->comment('URL de la imagen del producto');
                }
                if (!Schema::hasColumn('producto', 'categoria')) {
                    $table->string('categoria')->nullable()->after('imagen')->comment('Categoría del producto');
                }
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->dropColumn('porcdescuento');
            $table->dropColumn('imagen');
            $table->dropColumn('categoria');
        });
    }
};
