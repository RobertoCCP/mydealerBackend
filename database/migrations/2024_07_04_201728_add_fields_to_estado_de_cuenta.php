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
        Schema::table('estadocuenta', function (Blueprint $table) {
            $table->string('coddocumento')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('codcuota')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estadocuenta', function (Blueprint $table) {
            $table->dropColumn('coddocumento');
            $table->dropColumn('descripcion');
            $table->dropColumn('codcuota');
        });
    }
};
