<?php

namespace Database\Seeders;

use App\Models\Horario;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    public function run()
    {
        Horario::create([
            'dia' => 'LUN',
            'hora_inicio' => '08:30',
            'hora_fin' => '10:00'
        ]);

        Horario::create([
            'dia' => 'LUN',
            'hora_inicio' => '10:30',
            'hora_fin' => '12:00'
        ]);

        Horario::create([
            'dia' => 'MIE',
            'hora_inicio' => '08:30',
            'hora_fin' => '10:00'
        ]);

        Horario::create([
            'dia' => 'VIE',
            'hora_inicio' => '14:00',
            'hora_fin' => '15:30'
        ]);

        Horario::create([
            'dia' => 'JUE',
            'hora_inicio' => '16:00',
            'hora_fin' => '17:30'
        ]);
    }
}