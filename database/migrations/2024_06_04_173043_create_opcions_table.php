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
        Schema::create('opcions', function (Blueprint $table) {
            $table->id('sropcion');
            $table->string('nombre', 30)->nullable();
            $table->string('path', 100)->nullable();
            $table->integer('orden')->unsigned()->nullable();
            $table->integer('codmenucabecera')->nullable();
            $table->char('estado', 1)->default('A');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opcions');
    }
};
