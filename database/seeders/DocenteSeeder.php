<?php

namespace Database\Seeders;

use App\Models\Docente;
use Illuminate\Database\Seeder;

class DocenteSeeder extends Seeder
{
    public function run()
    {

        Docente::create([
            'codigo' => '222', 
            'fecha_contrato' => '2025-02-01',
            'sueldo' => 7500,
            'telefono' => '78111663',
            'id_users' => 2 // Otro usuario
        ]);

        Docente::create([
            'codigo' => '333',
            'fecha_contrato' => '2025-03-15',
            'sueldo' => 8200,
            'telefono' => '78111664', 
            'id_users' => 3 // Otro usuario
        ]);
    }
}