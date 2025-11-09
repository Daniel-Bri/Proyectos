<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre' => 'Informática'], // ← FALTABA ESTA
            ['nombre' => 'Matemáticas'],
            ['nombre' => 'Física'],
            ['nombre' => 'Programación'],
            ['nombre' => 'Bases de Datos'],
            ['nombre' => 'Redes'],
            ['nombre' => 'Sistemas Operativos'],
            ['nombre' => 'Ingeniería de Software'],
            ['nombre' => 'Inteligencia Artificial'],
            ['nombre' => 'Seguridad Informática'],
            ['nombre' => 'Electrónica'],
            ['nombre' => 'Economía'],
            ['nombre' => 'Administración'],
            ['nombre' => 'Contabilidad'],
            ['nombre' => 'Investigación'],
            ['nombre' => 'Idiomas'],
            ['nombre' => 'Ética y Legislación'],
            ['nombre' => 'Proyectos'],
            ['nombre' => 'Talleres'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}