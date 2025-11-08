<?php

namespace Database\Seeders;

use App\Models\GrupoMateriaHorario;
use Illuminate\Database\Seeder;

class GrupoMateriaHorarioSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tabla primero (opcional)
        GrupoMateriaHorario::truncate();

        GrupoMateriaHorario::create([
            'id_grupo_materia' => 1, // SA - INF110
            'id_horario' => 1, // LUN 08:30-10:00
            'id_aula' => 1, // Aula 24
            'id_docente' => '333', // Código del docente
            'estado_aula' => 'ocupado'
        ]);

        GrupoMateriaHorario::create([
            'id_grupo_materia' => 1, // SA - INF110
            'id_horario' => 3, // MIE 08:30-10:00
            'id_aula' => 1, // Aula 24
            'id_docente' => '222', // Código del docente
            'estado_aula' => 'ocupado'
        ]);

        GrupoMateriaHorario::create([
            'id_grupo_materia' => 2, // SB - INF110
            'id_horario' => 2, // LUN 10:30-12:00
            'id_aula' => 2, // Aula 41 (Laboratorio)
            'id_docente' => '222', // Otro docente
            'estado_aula' => 'ocupado'
        ]);

        // Más ejemplos para testing
        /*GrupoMateriaHorario::create([
            'id_grupo_materia' => 3, // SC - INF119
            'id_horario' => 4, // MAR 14:00-15:30
            'id_aula' => 3, // Aula 15
            'id_docente' => '111', // Mismo docente
            'estado_aula' => 'ocupado'
        ]);

        GrupoMateriaHorario::create([
            'id_grupo_materia' => 4, // SD - MAT101
            'id_horario' => 5, // JUE 16:00-17:30
            'id_aula' => 4, // Aula 32
            'id_docente' => '333', // Otro docente
            'estado_aula' => 'ocupado'
        ]);*/
    }
}