<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GrupoMateria;
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

        // Obtener últimas asistencias
        $asistencias = Asistencia::with(['docente', 'grupoMateria.materia'])
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'asistencias'));
    }
}