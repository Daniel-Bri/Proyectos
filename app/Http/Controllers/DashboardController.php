<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GrupoMateria;
use App\Models\GrupoMateriaHorario;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'docentes_count' => Docente::count(),
            'materias_count' => Materia::count(),
            'aulas_count' => Aula::count(),
            'grupos_count' => Grupo::count(),
        ];

        // Obtener últimas asistencias - RELACIONES CORREGIDAS
        $asistencias = Asistencia::with([
                'grupoMateriaHorario.docente.user', // Relación corregida
                'grupoMateriaHorario.grupoMateria.materia',
                'grupoMateriaHorario.grupoMateria.grupo'
            ])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_registro', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'asistencias'));
    }
}