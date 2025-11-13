<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run()
    {
        Carrera::create(['nombre' => 'Ingeniería en Sistemas']);
        Carrera::create(['nombre' => 'Ingeniería Informática']);
        Carrera::create(['nombre' => 'Ingeniería en Redes y Telecomunicaciones']);
        Carrera::create(['nombre' => 'Ingienería Robotica']);
        Carrera::create(['nombre' => 'Derecho']);
        Carrera::create(['nombre' => 'Ingienería Industrial']);
        Carrera::create(['nombre' => 'Ingienería Quimica']);
        Carrera::create(['nombre' => 'Ingienería Civil']);
        Carrera::create(['nombre' => 'Economía']);
        Carrera::create(['nombre' => 'Ingienería Comercial']);
        Carrera::create(['nombre' => 'Administración de Empresas']);
        Carrera::create(['nombre' => 'Ingienería Mecanica']);
        Carrera::create(['nombre' => 'Ingienería Electromecanica']);
    }
}