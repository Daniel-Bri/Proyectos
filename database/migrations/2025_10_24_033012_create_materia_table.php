<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materia', function (Blueprint $table) {
            $table->string('sigla')->primary();
            $table->string('nombre');
            $table->integer('semestre');
            $table->foreignId('id_categoria')->constrained('categoria')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materia');
    }
};