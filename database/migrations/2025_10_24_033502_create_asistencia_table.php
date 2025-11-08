<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_registro');
            $table->enum('estado', ['presente', 'ausente', 'justificado', 'tardanza'])->default('presente');
            $table->foreignId('id_grupo_materia_horario')->constrained('grupo_materia_horario')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['fecha', 'id_grupo_materia_horario']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencia');
    }
};