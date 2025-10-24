<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $materias = [
            [
                'sigla' => 'INF110',
                'nombre' => 'Introducción a la Informática',
                'semestre' => 1,
                'id_categoria' => 1,
            ],
            [
                'sigla' => 'INF120', 
                'nombre' => 'Programación I',
                'semestre' => 1,
                'id_categoria' => 1,
            ],
            [
                'sigla' => 'INF210',
                'nombre' => 'Programación II', 
                'semestre' => 2,
                'id_categoria' => 1,
            ],
            [
                'sigla' => 'INF119',
                'nombre' => 'Estructuras Discretas', 
                'semestre' => 1,
                'id_categoria' => 2,
            ],
            // ... agregar más materias
        ];

        foreach ($materias as $materia) {
            Materia::firstOrCreate(
                ['sigla' => $materia['sigla']], // Buscar por sigla
                $materia // Crear si no existe
            );
        }
    }
}