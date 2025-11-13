<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('docente_carrera', function (Blueprint $table) {
            $table->string('codigo_docente');
            $table->foreign('codigo_docente')->references('codigo')->on('docente')->onDelete('cascade');
            $table->foreignId('id_carrera')->constrained('carrera')->onDelete('cascade');
            $table->timestamps();
            
            $table->primary(['codigo_docente', 'id_carrera']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('docente_carrera');
    }
};