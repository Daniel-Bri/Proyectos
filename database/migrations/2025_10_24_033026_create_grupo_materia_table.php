<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grupo_materia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_gestion')->constrained('gestion_academica')->onDelete('cascade');
            $table->foreignId('id_grupo')->constrained('grupo')->onDelete('cascade');
            $table->string('sigla_materia');
            $table->foreign('sigla_materia')->references('sigla')->on('materia')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['id_gestion', 'id_grupo', 'sigla_materia']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupo_materia');
    }
};