<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('temporal_token')->nullable();
            $table->boolean('password_set')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        // Eliminar tablas dependientes primero
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('ip_whitelist');
        Schema::dropIfExists('login_intentos'); // Si existe
        
        // Luego eliminar users
        Schema::dropIfExists('users');
    }
};