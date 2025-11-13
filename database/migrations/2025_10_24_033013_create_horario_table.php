<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horario', function (Blueprint $table) {
            $table->id();
            $table->enum('dia', ['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('horario');
    }
};