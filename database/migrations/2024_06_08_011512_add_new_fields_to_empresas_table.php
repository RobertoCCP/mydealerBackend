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
        Schema::table('empresa', function (Blueprint $table) {
            $table->string('cargo1',50)->nullable()->comment('Cargo de la empresa');
            $table->string('nombre1',60)->nullable()->comment('Nombre del empleado');
            $table->string('email1', 100)->nullable()->comment('Mail de contacto');
            $table->string('foto1',60)->nullable()->comment('Preferencia 150px - 200px');
            $table->string('cargo2',50)->nullable()->comment('Cargo de la empresa');
            $table->string('nombre2',60)->nullable()->comment('Nombre del empleado');
            $table->string('email2', 100)->nullable()->comment('Mail de contacto');
            $table->string('foto2',60)->nullable()->comment('Preferencia 150px - 200px');
            $table->string('cargo3',50)->nullable()->comment('Cargo de la empresa');
            $table->string('nombre3',60)->nullable()->comment('Nombre del empleado');
            $table->string('email3', 100)->nullable()->comment('Mail de contacto');
            $table->string('foto3',60)->nullable()->comment('Preferencia 150px - 200px');
            $table->string('cargo4',50)->nullable()->comment('Cargo de la empresa');
            $table->string('nombre4',60)->nullable()->comment('Nombre del empleado');
            $table->string('email4', 100)->nullable()->comment('Mail de contacto');
            $table->string('foto4',60)->nullable()->comment('Preferencia 150px - 200px');
            $table->string('app_version',10)->nullable()->comment('version app');
            $table->string('app_empresa',50)->nullable()->comment('app de empresa');
            $table->string('fotopiecob',60)->nullable()->comment('Imagen de footer');
            $table->string('fax',16)->nullable()->comment('Fax');
            $table->string('logo',60)->nullable()->comment('Logo empresa');
            $table->string('logo_cabecera',60)->nullable()->comment('Logo cabecera');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn('cargo1');
            $table->dropColumn('nombre1');
            $table->dropColumn('email1');
            $table->dropColumn('foto1');
            $table->dropColumn('cargo2');
            $table->dropColumn('nombre2');
            $table->dropColumn('email2');
            $table->dropColumn('foto2');
            $table->dropColumn('cargo3');
            $table->dropColumn('nombre3');
            $table->dropColumn('email3');
            $table->dropColumn('foto3');
            $table->dropColumn('cargo4');
            $table->dropColumn('nombre4');
            $table->dropColumn('email4');
            $table->dropColumn('foto4');
            $table->dropColumn('app_version');
            $table->dropColumn('app_empresa');
            $table->dropColumn('fotopiecob');
            $table->dropColumn('fax');
            $table->dropColumn('logo');
            $table->dropColumn('logo_cabecera');
        });
    }
};
