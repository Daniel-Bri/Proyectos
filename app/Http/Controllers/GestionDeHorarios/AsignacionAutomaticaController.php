<?php

namespace App\Http\Controllers\GestionDeHorarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\GrupoMateria;
use App\Models\GrupoMateriaHorario;
use App\Models\GestionAcademica;
use Illuminate\Support\Facades\DB;

class AsignacionAutomaticaController extends Controller
{
    // Mostrar formulario de asignación automática
    public function index()
    {
        try {
            $gestiones = GestionAcademica::whereIn('estado', ['curso', 'activo'])->get();
            
            $estadisticas = [
                'docentes' => Docente::count(),
                'materias' => Materia::count(),
                'aulas' => Aula::count(),
                'horarios_asignados' => GrupoMateriaHorario::count()
            ];
            
            return view('coordinador.asignacionAutomatica.index', compact('gestiones', 'estadisticas'));
            
        } catch (\Exception $e) {
            return view('coordinador.asignacionAutomatica.index', [
                'gestiones' => collect([]),
                'estadisticas' => [
                    'docentes' => 0,
                    'materias' => 0,
                    'aulas' => 0,
                    'horarios_asignados' => 0
                ]
            ])->with('error', 'Error al cargar datos del sistema: ' . $e->getMessage());
        }
    }

    // Asignación automática COMPLETA (reinicia todo)
    public function asignacionCompleta(Request $request)
    {
        $request->validate([
            'id_gestion' => 'required|exists:gestion_academica,id',
            'max_horas_docente' => 'required|integer|min:1|max:50'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Eliminar horarios existentes para esta gestión
                $this->eliminarHorariosGestion($request->id_gestion);

                // 2. Ejecutar algoritmo de asignación
                $resultado = $this->ejecutarAlgoritmoAsignacion(
                    $request->id_gestion, 
                    $request->max_horas_docente,
                    'completa'
                );

                return redirect()
                    ->route('coordinador.horarios.index')
                    ->with('success', "✅ Asignación completa realizada: {$resultado['asignaciones']} horarios creados");
            });
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', "❌ Error en asignación: {$e->getMessage()}");
        }
    }

    // Asignación automática INTELIGENTE (respeta existentes)
    public function asignacionInteligente(Request $request)
    {
        $request->validate([
            'id_gestion' => 'required|exists:gestion_academica,id',
            'max_horas_docente' => 'required|integer|min:1|max:50'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Ejecutar algoritmo respetando horarios existentes
                $resultado = $this->ejecutarAlgoritmoAsignacion(
                    $request->id_gestion, 
                    $request->max_horas_docente,
                    'inteligente'
                );

                return redirect()
                    ->route('coordinador.horarios.index')
                    ->with('success', "🧠 Asignación inteligente realizada: {$resultado['asignaciones']} nuevos horarios creados");
            });
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', "❌ Error en asignación: {$e->getMessage()}");
        }
    }

    private function eliminarHorariosGestion($gestionId)
    {
        // Eliminar horarios de grupo_materia_horario para esta gestión
        GrupoMateriaHorario::whereHas('grupoMateria', function($query) use ($gestionId) {
            $query->where('id_gestion', $gestionId);
        })->delete();
    }

    private function ejecutarAlgoritmoAsignacion($gestionId, $maxHorasDocente, $tipo)
    {
        $asignacionesCreadas = 0;
        
        // 1. Obtener datos necesarios usando tus modelos
        $gruposMateria = GrupoMateria::with(['materia', 'grupo'])
            ->where('id_gestion', $gestionId)
            ->get();

        $docentes = Docente::with(['user'])->get();
        $aulas = Aula::all();
        $horariosBase = Horario::all();
        
        $horariosExistentes = $tipo === 'inteligente' 
            ? $this->obtenerHorariosExistentes($gestionId)
            : collect([]);

        // 2. Algoritmo de asignación mejorado
        foreach ($gruposMateria as $grupoMateria) {
            // Solo asignar si no existe ya (en modo inteligente)
            if ($tipo === 'inteligente' && $this->tieneHorarioAsignado($grupoMateria->id, $horariosExistentes)) {
                continue;
            }

            $docenteAsignado = $this->seleccionarDocenteOptimo(
                $grupoMateria, 
                $docentes, 
                $horariosExistentes,
                $maxHorasDocente
            );

            $horarioAula = $this->seleccionarHorarioYAula(
                $grupoMateria,
                $horariosBase,
                $aulas,
                $horariosExistentes
            );

            if ($docenteAsignado && $horarioAula) {
                // Crear la asignación
                GrupoMateriaHorario::create([
                    'estado_aula' => 'ocupado',
                    'id_grupo_materia' => $grupoMateria->id,
                    'id_horario' => $horarioAula['horario']->id,
                    'id_aula' => $horarioAula['aula']->id,
                    'id_docente' => $docenteAsignado->codigo
                ]);

                $asignacionesCreadas++;
                
                // Actualizar horarios existentes para siguiente iteración
                if ($tipo === 'inteligente') {
                    $horariosExistentes->push([
                        'id_horario' => $horarioAula['horario']->id,
                        'id_aula' => $horarioAula['aula']->id,
                        'id_docente' => $docenteAsignado->codigo,
                        'id_grupo_materia' => $grupoMateria->id
                    ]);
                }
            }
        }

        return ['asignaciones' => $asignacionesCreadas];
    }

    private function obtenerHorariosExistentes($gestionId)
    {
        return GrupoMateriaHorario::with(['horario', 'aula', 'docente'])
            ->whereHas('grupoMateria', function($query) use ($gestionId) {
                $query->where('id_gestion', $gestionId);
            })
            ->get()
            ->map(function($gmh) {
                return [
                    'id_horario' => $gmh->id_horario,
                    'id_aula' => $gmh->id_aula,
                    'id_docente' => $gmh->id_docente,
                    'id_grupo_materia' => $gmh->id_grupo_materia
                ];
            });
    }

    private function tieneHorarioAsignado($grupoMateriaId, $horariosExistentes)
    {
        return $horariosExistentes->contains('id_grupo_materia', $grupoMateriaId);
    }

    private function seleccionarDocenteOptimo($grupoMateria, $docentes, $horariosExistentes, $maxHoras)
    {
        // Ordenar docentes por especialidad (si tuvieran) y luego por carga horaria
        $docentesOrdenados = $docentes->sortBy(function($docente) use ($horariosExistentes) {
            return $this->calcularHorasDocente($docente->codigo, $horariosExistentes);
        });

        foreach ($docentesOrdenados as $docente) {
            $horasAsignadas = $this->calcularHorasDocente($docente->codigo, $horariosExistentes);
            if ($horasAsignadas < $maxHoras) {
                return $docente;
            }
        }
        return null;
    }

    private function calcularHorasDocente($codigoDocente, $horariosExistentes)
    {
        $horasDocente = $horariosExistentes->where('id_docente', $codigoDocente)->count();
        return $horasDocente * 1.5; // Asumiendo 1.5 horas por horario
    }

    private function seleccionarHorarioYAula($grupoMateria, $horariosBase, $aulas, $horariosExistentes)
    {
        // Combinaciones de horario y aula
        foreach ($horariosBase as $horario) {
            foreach ($aulas as $aula) {
                if ($this->combinacionDisponible($horario, $aula, $horariosExistentes)) {
                    return [
                        'horario' => $horario,
                        'aula' => $aula
                    ];
                }
            }
        }
        return null;
    }

    private function combinacionDisponible($horario, $aula, $horariosExistentes)
    {
        // Verificar si el horario y aula están disponibles
        $ocupado = $horariosExistentes->where('id_horario', $horario->id)
                                    ->where('id_aula', $aula->id)
                                    ->isNotEmpty();
        return !$ocupado;
    }

    public function preview(Request $request)
    {
        // Lógica para generar previsualización
        $horariosGenerados = []; // Tu lógica aquí
        $conflictos = []; // Tu lógica aquí
        
        return view('coordinador.asignacionAutomatica.preview', [
            'horariosGenerados' => $horariosGenerados,
            'conflictos' => $conflictos,
            'resumen' => [
                'total_asignaciones' => count($horariosGenerados),
                'docentes_asignados' => 0, // Calcular
                'aulas_utilizadas' => 0, // Calcular
                'conflictos' => count($conflictos)
            ],
            'idGestion' => $request->id_gestion,
            'tipoAsignacion' => $request->tipo_asignacion,
            'maxHorasDocente' => $request->max_horas_docente
        ]);
    }

    public function confirmar(Request $request)
    {
        // Lógica para guardar definitivamente
        $resultado = $this->ejecutarAlgoritmoAsignacion(
            $request->id_gestion,
            $request->max_horas_docente,
            $request->tipo_asignacion
        );
        
        return redirect()->route('coordinador.asignacion-automatica.confirmacion')
            ->with(['resultado' => $resultado]);
    }

    public function confirmacion()
    {
        $resultado = session('resultado', []);
        
        return view('coordinador.asignacionAutomatica.confirmacion', [
            'resultado' => $resultado,
            'gestionNombre' => 'Gestión 2025', // Obtener de BD
            'tipoAsignacion' => 'completa', // O session
            'maxHorasDocente' => 40 // O session
        ]);
    }
}