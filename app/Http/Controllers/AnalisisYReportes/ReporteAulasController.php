<?php

namespace App\Http\Controllers\AnalisisYReportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\GrupoMateriaHorario;
use App\Models\Horario;
use App\Models\GestionAcademica;
use App\Models\GrupoMateria;
use App\Models\Materia;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteAulasController extends Controller
{
    public function index()
    {
        $gestiones = GestionAcademica::whereIn('estado', ['curso', 'activo'])->get();
        $diasSemana = ['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'];
        
        return view('reportes.aulasDisponibles.index', compact('gestiones', 'diasSemana'));
    }

    public function generarReporte(Request $request)
    {
        try {
            // Validar los datos del request
            $validated = $request->validate([
                'id_gestion' => 'nullable|exists:gestion_academica,id',
                'dia' => 'nullable|in:LUN,MAR,MIE,JUE,VIE,SAB',
                'hora' => 'nullable|date_format:H:i',
                'tipo_aula' => 'nullable|string',
                'capacidad_minima' => 'nullable|integer|min:1',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            ]);

            // Obtener aulas disponibles y ocupadas según los filtros
            $aulasDisponibles = $this->obtenerAulasDisponibles($validated);
            $aulasOcupadas = $this->obtenerAulasOcupadas($validated);

            // Exportar a PDF si se solicita
            if ($request->has('exportar_pdf')) {
                return $this->generarPDF($aulasDisponibles, $aulasOcupadas, $validated);
            }

            // Retornar vista con resultados
            return view('reportes.aulasDisponibles.resultados', [
                'aulasDisponibles' => $aulasDisponibles,
                'aulasOcupadas' => $aulasOcupadas,
                'filtros' => $validated,
                'gestiones' => GestionAcademica::whereIn('estado', ['curso', 'activo'])->get(),
                'diasSemana' => ['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            ])->with('success', 'Reporte generado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    public function generarPDF(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_gestion' => 'nullable|exists:gestion_academica,id',
                'dia' => 'nullable|in:LUN,MAR,MIE,JUE,VIE,SAB',
                'hora' => 'nullable|date_format:H:i',
                'tipo_aula' => 'nullable|string',
                'capacidad_minima' => 'nullable|integer|min:1',
            ]);

            $aulasDisponibles = $this->obtenerAulasDisponibles($validated);
            $aulasOcupadas = $this->obtenerAulasOcupadas($validated);

            $data = [
                'aulasDisponibles' => $aulasDisponibles,
                'aulasOcupadas' => $aulasOcupadas,
                'filtros' => $validated,
                'fechaGeneracion' => now()->format('d/m/Y H:i'),
                'totalDisponibles' => count($aulasDisponibles),
                'totalOcupadas' => count($aulasOcupadas)
            ];

            $pdf = PDF::loadView('reportes.aulasDisponibles.viewPDF', $data);
            
            return $pdf->download('reporte-aulas-disponibles-' . now()->format('Y-m-d-H-i') . '.pdf');

        } catch (\Exception $e) {
            return redirect()->route('coordinador.reportes.aulas.disponibles')
                ->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    private function obtenerAulasDisponibles($filtros)
    {
        $query = Aula::query();

        // Aplicar filtros básicos de aula
        if (!empty($filtros['tipo_aula'])) {
            $query->where('tipo', $filtros['tipo_aula']);
        }

        if (!empty($filtros['capacidad_minima'])) {
            $query->where('capacidad', '>=', $filtros['capacidad_minima']);
        }

        // Si no hay filtros de horario, retornar todas las aulas que cumplan los filtros básicos
        if (empty($filtros['dia']) && empty($filtros['hora'])) {
            return $query->orderBy('nombre')->get();
        }

        // Filtrar por disponibilidad en horario específico
        $aulas = $query->get();
        $aulasDisponibles = collect([]);

        foreach ($aulas as $aula) {
            if ($this->aulaEstaDisponible($aula, $filtros)) {
                $aulasDisponibles->push($aula);
            }
        }

        return $aulasDisponibles;
    }

    private function aulaEstaDisponible($aula, $filtros)
    {
        $query = GrupoMateriaHorario::where('id_aula', $aula->id)
            ->whereHas('horario', function($q) use ($filtros) {
                if (!empty($filtros['dia'])) {
                    $q->where('dia', $filtros['dia']);
                }
                if (!empty($filtros['hora'])) {
                    $q->where('hora_inicio', '<=', $filtros['hora'])
                      ->where('hora_fin', '>=', $filtros['hora']);
                }
            })
            ->whereHas('grupoMateria', function($q) use ($filtros) {
                if (!empty($filtros['id_gestion'])) {
                    $q->where('id_gestion', $filtros['id_gestion']);
                }
            });

        return $query->count() === 0;
    }

    private function obtenerAulasOcupadas($filtros)
    {
        $query = GrupoMateriaHorario::with([
                'aula',
                'horario',
                'grupoMateria.materia',
                'grupoMateria.grupo',
                'docente.user'
            ])
            ->whereHas('horario', function($q) use ($filtros) {
                if (!empty($filtros['dia'])) {
                    $q->where('dia', $filtros['dia']);
                }
                if (!empty($filtros['hora'])) {
                    $q->where('hora_inicio', '<=', $filtros['hora'])
                      ->where('hora_fin', '>=', $filtros['hora']);
                }
            })
            ->whereHas('grupoMateria', function($q) use ($filtros) {
                if (!empty($filtros['id_gestion'])) {
                    $q->where('id_gestion', $filtros['id_gestion']);
                }
            })
            ->whereHas('aula', function($q) use ($filtros) {
                if (!empty($filtros['tipo_aula'])) {
                    $q->where('tipo', $filtros['tipo_aula']);
                }
                if (!empty($filtros['capacidad_minima'])) {
                    $q->where('capacidad', '>=', $filtros['capacidad_minima']);
                }
            });

        $ocupaciones = $query->get();

        // Formatear los datos para la vista
        return $ocupaciones->map(function($ocupacion) {
            return (object) [
                'aula_nombre' => $ocupacion->aula->nombre ?? 'N/A',
                'materia_nombre' => $ocupacion->grupoMateria->materia->nombre ?? 'N/A',
                'materia_sigla' => $ocupacion->grupoMateria->materia->sigla ?? 'N/A',
                'docente_nombre' => $ocupacion->docente->user->name ?? 'N/A',
                'grupo_nombre' => $ocupacion->grupoMateria->grupo->nombre ?? 'N/A',
                'dia' => $ocupacion->horario->dia ?? 'N/A',
                'hora_inicio' => $ocupacion->horario->hora_inicio ?? 'N/A',
                'hora_fin' => $ocupacion->horario->hora_fin ?? 'N/A',
                'capacidad' => $ocupacion->aula->capacidad ?? 0
            ];
        });
    }
}