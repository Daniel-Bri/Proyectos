<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ip_whitelist', function (Blueprint $table) {
            $table->id();
            $table->string('ip_cidr');
            $table->foreignId('id_users')
                ->constrained('users')
                ->onDelete('cascade') // ← AGREGAR ESTO
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ip_whitelist');
    }
};