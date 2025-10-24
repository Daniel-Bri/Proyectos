<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        Categoria::create(['nombre' => 'Informática']);
        Categoria::create(['nombre' => 'Matemáticas']);
        Categoria::create(['nombre' => 'Física']);
        Categoria::create(['nombre' => 'Investigación']);

    }
}