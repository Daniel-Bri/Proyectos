<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->string('codigo')->primary();
            $table->date('fecha_contrato');
            $table->decimal('sueldo', 10, 2);
            $table->string('telefono')->nullable();
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('docente');
    }
};