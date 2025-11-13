<?php

namespace Database\Seeders;

use App\Models\GestionAcademica;
use Illuminate\Database\Seeder;

class GestionAcademicaSeeder extends Seeder
{
    public function run()
    {
        GestionAcademica::create([
            'nombre' => '1-2025',
            'fecha_inicio' => '2025-03-03',
            'fecha_fin' => '2025-07-27',
            'estado' => 'finalizado'
        ]);

        GestionAcademica::create([
            'nombre' => '2-2025',
            'fecha_inicio' => '2025-08-08',
            'fecha_fin' => '2025-12-25',
            'estado' => 'curso'
        ]);
    }
}