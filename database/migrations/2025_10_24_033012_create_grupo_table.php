<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grupo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // ← ¿ESTÁ ESTA LÍNEA?
            $table->string('gestion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupo');
    }
};