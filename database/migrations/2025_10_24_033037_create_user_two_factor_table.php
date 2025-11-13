<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_two_factor', function (Blueprint $table) {
            $table->string('secret');
            $table->text('recovery_codes');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->primary('id_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_two_factor');
    }
};