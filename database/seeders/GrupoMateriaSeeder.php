<?php

namespace Database\Seeders;

use App\Models\GrupoMateria;
use Illuminate\Database\Seeder;

class GrupoMateriaSeeder extends Seeder
{
    public function run()
    {
        GrupoMateria::create([
            'id_gestion' => 2, // 2-2025 en curso
            'id_grupo' => 1, // SA
            'sigla_materia' => 'INF110'
        ]);

        GrupoMateria::create([
            'id_gestion' => 2,
            'id_grupo' => 2, // SB
            'sigla_materia' => 'INF110'
        ]);

        GrupoMateria::create([
            'id_gestion' => 2,
            'id_grupo' => 1, // SA
            'sigla_materia' => 'INF119'
        ]);
    }
}