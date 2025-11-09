<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    public function run()
    {
        Grupo::create(['nombre' => 'SA', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SB', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SC', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SD', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SE', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SF', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SZ', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'F1', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'CI', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'I2', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SG', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SP', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'Z1', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'Z2', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'Z3', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'Z4', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'Z5', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'Z6', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'R1', 'gestion' => '2025']);
        Grupo::create(['nombre' => '1I', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'C1', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SH', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'SN', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'NW', 'gestion' => '2025']);
        Grupo::create(['nombre' => 'NX', 'gestion' => '2025']);
    }
}