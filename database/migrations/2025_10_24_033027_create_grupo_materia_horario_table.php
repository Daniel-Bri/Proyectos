<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grupo_materia_horario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_grupo_materia')->constrained('grupo_materia')->onDelete('cascade');
            $table->foreignId('id_horario')->constrained('horario')->onDelete('cascade');
            $table->foreignId('id_aula')->constrained('aula')->onDelete('cascade');
            $table->string('id_docente'); // Cambiado a string para coincidir con código de docente
            $table->foreign('id_docente')->references('codigo')->on('docente')->onDelete('cascade');
            $table->enum('estado_aula', ['ocupado', 'disponible'])->default('ocupado');
            $table->timestamps();
            
            $table->unique(['id_grupo_materia', 'id_horario']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupo_materia_horario');
    }
};