<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gestion_academica', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['planificacion', 'curso', 'finalizado'])->default('planificacion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gestion_academica');
    }
};