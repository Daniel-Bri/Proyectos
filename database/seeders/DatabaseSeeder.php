<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            
            CarreraSeeder::class,
            CategoriaSeeder::class,
            MateriaSeeder::class,
            GrupoSeeder::class,
            AulaSeeder::class,
            HorarioSeeder::class,
            GestionAcademicaSeeder::class,
            DocenteSeeder::class,
            GrupoMateriaSeeder::class,
            GrupoMateriaHorarioSeeder::class,
        ]);
    }
}