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
                'sigla' => 'MAT101',
                'nombre' => 'CALCULO I',
                'semestre' => 1,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'INF119',
                'nombre' => 'ESTRUCTURAS DISCRETAS',
                'semestre' => 1,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'INF110',
                'nombre' => 'INTRODUCCION A LA INFORMATICA',
                'semestre' => 1,
                'id_categoria' => 1, // Informática
            ],
            [
                'sigla' => 'FIS100',
                'nombre' => 'FISICA I',
                'semestre' => 1,
                'id_categoria' => 3, // Física
            ],
            [
                'sigla' => 'LIN100',
                'nombre' => 'INGLES TECNICO I',
                'semestre' => 1,
                'id_categoria' => 16, // Idiomas
            ],
            [
                'sigla' => 'MAT102',
                'nombre' => 'CALCULO II',
                'semestre' => 2,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'MAT103',
                'nombre' => 'ALGEBRA LINEAL',
                'semestre' => 2,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'INF120',
                'nombre' => 'PROGRAMACION I',
                'semestre' => 2,
                'id_categoria' => 4, // Programación
            ],
            [
                'sigla' => 'FIS102',
                'nombre' => 'FISICA II',
                'semestre' => 2,
                'id_categoria' => 3, // Física
            ],
            [
                'sigla' => 'LIN101',
                'nombre' => 'INGLES TECNICO II',
                'semestre' => 2,
                'id_categoria' => 16, // Idiomas
            ],
            [
                'sigla' => 'MAT207',
                'nombre' => 'ECUACIONES DIFERENCIALES',
                'semestre' => 3,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'INF210',
                'nombre' => 'PROGRAMACION II',
                'semestre' => 3,
                'id_categoria' => 4, // Programación
            ],
            [
                'sigla' => 'INF211',
                'nombre' => 'ARQUITECTURA DE COMPUTADORAS',
                'semestre' => 3,
                'id_categoria' => 1, // Informática
            ],
            [
                'sigla' => 'FIS200',
                'nombre' => 'FISICA III',
                'semestre' => 3,
                'id_categoria' => 3, // Física
            ],
            [
                'sigla' => 'ADM100',
                'nombre' => 'ADMINISTRACION',
                'semestre' => 3,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'MAT202',
                'nombre' => 'PROBABILIDAD Y ESTADISTICA I',
                'semestre' => 4,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'MAT205',
                'nombre' => 'METODOS NUMERICOS',
                'semestre' => 4,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'INF220',
                'nombre' => 'ESTRUCTURA DE DATOS I',
                'semestre' => 4,
                'id_categoria' => 4, // Programación
            ],
            [
                'sigla' => 'INF221',
                'nombre' => 'PROGRAMACION ENSAMBLADOR',
                'semestre' => 4,
                'id_categoria' => 4, // Programación
            ],
            [
                'sigla' => 'ADM200',
                'nombre' => 'CONTABILIDAD',
                'semestre' => 4,
                'id_categoria' => 14, // Contabilidad
            ],
            [
                'sigla' => 'MAT302',
                'nombre' => 'PROBABILIDAD Y ESTADISTICA II',
                'semestre' => 5,
                'id_categoria' => 2, // Matemáticas
            ],
            [
                'sigla' => 'INF318',
                'nombre' => 'PROGRAMACION LOGICA Y FUNCIONAL',
                'semestre' => 5,
                'id_categoria' => 4, // Programación
            ],
            [
                'sigla' => 'INF310',
                'nombre' => 'ESTRUCTURA DE DATOS II',
                'semestre' => 5,
                'id_categoria' => 4, // Programación
            ],
            [
                'sigla' => 'INF312',
                'nombre' => 'BASE DE DATOS I',
                'semestre' => 5,
                'id_categoria' => 5, // Bases de Datos
            ],
            [
                'sigla' => 'INF319',
                'nombre' => 'LENGUAJES FORMALES',
                'semestre' => 5,
                'id_categoria' => 1, // Informática
            ],
            [
                'sigla' => 'ECO300',
                'nombre' => 'ECONOMIA PARA LA GESTION',
                'semestre' => 5,
                'id_categoria' => 12, // Economía
            ],
            [
                'sigla' => 'ADM330',
                'nombre' => 'ORGANIZACIÓN Y METODOS',
                'semestre' => 5,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'ELC001',
                'nombre' => 'ADMINISTRACION DE RECURSOS HUMANOS',
                'semestre' => 5,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'ELC002',
                'nombre' => 'COSTOS Y PRESUPUESTOS',
                'semestre' => 5,
                'id_categoria' => 14, // Contabilidad
            ],
            [
                'sigla' => 'MAT329',
                'nombre' => 'INVESTIGACION OPERATIVA I',
                'semestre' => 6,
                'id_categoria' => 15, // Investigación
            ],
            [
                'sigla' => 'INF342',
                'nombre' => 'SISTEMAS DE INFORMACION I',
                'semestre' => 6,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'INF323',
                'nombre' => 'SISTEMAS OPERATIVOS I',
                'semestre' => 6,
                'id_categoria' => 7, // Sistemas Operativos
            ],
            [
                'sigla' => 'ADM320',
                'nombre' => 'FINANZAS PARA LA EMPRESA',
                'semestre' => 6,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'INF322',
                'nombre' => 'BASE DE DATOS II',
                'semestre' => 6,
                'id_categoria' => 5, // Bases de Datos
            ],
            [
                'sigla' => 'INF329',
                'nombre' => 'COMPILADORES',
                'semestre' => 6,
                'id_categoria' => 1, // Informática
            ],
            [
                'sigla' => 'ELC003',
                'nombre' => 'PRODUCCION Y MARKETING',
                'semestre' => 6,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'ELC004',
                'nombre' => 'REINGENIERIA',
                'semestre' => 6,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'MAT419',
                'nombre' => 'INVESTIGACION OPERATIVA II',
                'semestre' => 7,
                'id_categoria' => 15, // Investigación
            ],
            [
                'sigla' => 'INF418',
                'nombre' => 'INTELIGENCIA ARTIFICIAL',
                'semestre' => 7,
                'id_categoria' => 9, // Inteligencia Artificial
            ],
            [
                'sigla' => 'INF432',
                'nombre' => 'SOPORTE PARA LA TOMA DE DECISIONES',
                'semestre' => 7,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'INF413',
                'nombre' => 'SISTEMAS OPERATIVOS II',
                'semestre' => 7,
                'id_categoria' => 7, // Sistemas Operativos
            ],
            [
                'sigla' => 'INF433',
                'nombre' => 'REDES I',
                'semestre' => 7,
                'id_categoria' => 6, // Redes
            ],
            [
                'sigla' => 'INF412',
                'nombre' => 'SISTEMAS DE INFORMACION II',
                'semestre' => 7,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'ELC005',
                'nombre' => 'INGENIERIA DE LA CALIDAD',
                'semestre' => 7,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'ELC006',
                'nombre' => 'BENCHMARKING',
                'semestre' => 7,
                'id_categoria' => 13, // Administración
            ],
            [
                'sigla' => 'ECO449',
                'nombre' => 'PREPARACION Y EVALUACION DE PROYECTOS',
                'semestre' => 8,
                'id_categoria' => 18, // Proyectos
            ],
            [
                'sigla' => 'INF428',
                'nombre' => 'SISTEMAS EXPERTOS',
                'semestre' => 8,
                'id_categoria' => 9, // Inteligencia Artificial
            ],
            [
                'sigla' => 'INF442',
                'nombre' => 'SISTEMAS DE INFORMACION GEOGRAFICA',
                'semestre' => 8,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'INF423',
                'nombre' => 'REDES II',
                'semestre' => 8,
                'id_categoria' => 6, // Redes
            ],
            [
                'sigla' => 'INF462',
                'nombre' => 'AUDITORIA INFORMATICA',
                'semestre' => 8,
                'id_categoria' => 10, // Seguridad Informática
            ],
            [
                'sigla' => 'INF422',
                'nombre' => 'INGENIERIA DE SOFTWARE I',
                'semestre' => 8,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'ELC007',
                'nombre' => 'INGENIERIA MACROECONOMICA',
                'semestre' => 8,
                'id_categoria' => 12, // Economía
            ],
            [
                'sigla' => 'ELC008',
                'nombre' => 'LEGISLACION EN CIENCIAS DE LA COMPUTACION',
                'semestre' => 8,
                'id_categoria' => 17, // Ética y Legislación
            ],
            [
                'sigla' => 'INF511',
                'nombre' => 'TALLER DE GRADO I',
                'semestre' => 9,
                'id_categoria' => 18, // Proyectos
            ],
            [
                'sigla' => 'INF512',
                'nombre' => 'INGENIERIA DE SOFTWARE II',
                'semestre' => 9,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'INF513',
                'nombre' => 'TECNOLOGIA WEB',
                'semestre' => 9,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'INF552',
                'nombre' => 'ARQUITECTURA DE SOFTWARE',
                'semestre' => 9,
                'id_categoria' => 8, // Ingeniería de Software
            ],
            [
                'sigla' => 'GRL001',
                'nombre' => 'MODALIDAD DE TITULACION LICENCIATURA',
                'semestre' => 10,
                'id_categoria' => 18, // Proyectos
            ],
        ];

        foreach ($materias as $materia) {
            \App\Models\Materia::create($materia);
        }
    }
}