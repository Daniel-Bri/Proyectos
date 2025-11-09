<?php

namespace App\Http\Controllers\GestionDeHorarios;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\GrupoMateriaHorario;
use App\Models\GrupoMateria;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Aula;
use App\Models\GestionAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Administracion\BitacoraController;

class HorariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para gestionar horarios.');
        }

        // Obtener parámetros de filtro
        $docenteId = $request->docente_id;
        $materiaId = $request->materia_id;
        $grupoId = $request->grupo_id;

        // Consulta principal de horarios asignados
        $query = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'docente.user',
            'aula'
        ])
        ->orderBy('id', 'desc');

        // Aplicar filtros
        if ($docenteId) {
            $query->where('id_docente', $docenteId);
        }

        if ($materiaId) {
            $query->whereHas('grupoMateria', function($q) use ($materiaId) {
                $q->where('sigla_materia', $materiaId);
            });
        }

        if ($grupoId) {
            $query->whereHas('grupoMateria.grupo', function($q) use ($grupoId) {
                $q->where('id', $grupoId);
            });
        }

        $horarios = $query->paginate(15);

        // Obtener datos para filtros
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();

        return view('horarios.index', compact(
            'horarios',
            'docentes',
            'materias',
            'grupos',
            'docenteId',
            'materiaId',
            'grupoId'
        ));
    }

    /**
     * Show the form for creating a new horario base.
     */
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para crear horarios.');
        }

        // Días de la semana
        $dias = [
            'LUN' => 'Lunes',
            'MAR' => 'Martes',
            'MIE' => 'Miércoles',
            'JUE' => 'Jueves',
            'VIE' => 'Viernes',
            'SAB' => 'Sábado'
        ];

        // Obtener gestiones activas
        $gestiones = GestionAcademica::whereIn('estado', ['curso', 'activo'])->get();

        return view('horarios.create', compact('dias', 'gestiones'));
    }

    /**
     * Store a newly created horario base.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para crear horarios.');
        }

        // Validación para crear horario base
        $validated = $request->validate([
            'dia' => 'required|string|in:LUN,MAR,MIE,JUE,VIE,SAB',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'descripcion' => 'nullable|string|max:255'
        ]);

        try {
            // Verificar si el horario ya existe
            $horarioExistente = Horario::where('dia', $validated['dia'])
                ->where('hora_inicio', $validated['hora_inicio'])
                ->where('hora_fin', $validated['hora_fin'])
                ->first();

            if ($horarioExistente) {
                return back()->withErrors([
                    'error' => 'Este horario ya existe en el sistema.'
                ])->withInput();
            }

            // Crear nuevo horario base
            $horario = Horario::create([
                'dia' => $validated['dia'],
                'hora_inicio' => $validated['hora_inicio'],
                'hora_fin' => $validated['hora_fin'],
                'descripcion' => $validated['descripcion']
            ]);

            // Registrar en bitácora
            BitacoraController::registrarCreacion(
                'Horario Base', 
                $horario->id, 
                auth()->id(), 
                "Horario base creado: {$validated['dia']} {$validated['hora_inicio']}-{$validated['hora_fin']}"
            );

            return redirect()->route('coordinador.horarios.asignar')
                ->with('success', 'Horario base creado correctamente. Ahora puedes asignarlo.');

        } catch (\Exception $e) {
            \Log::error('Error al crear horario base: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Error al crear el horario base. Por favor, intente nuevamente.'
            ])->withInput();
        }
    }

    /**
     * Show the form for assigning a schedule.
     */
    public function asignar()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para asignar horarios.');
        }

        // Obtener la gestión académica activa
        $gestionActiva = GestionAcademica::where('estado', 'curso')->first();

        if (!$gestionActiva) {
            return back()->withErrors([
                'error' => 'No hay una gestión académica activa. Configure una gestión primero.'
            ]);
        }

        // CORREGIDO: Obtener TODOS los horarios base
        $horariosDisponibles = Horario::orderBy('dia')->orderBy('hora_inicio')->get();

        // Obtener datos para el formulario
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nombre')->get();

        return view('horarios.asignar', compact(
            'horariosDisponibles',
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'gestionActiva'
        ));
    }
    /**
     * Store a new schedule assignment.
     */
    public function storeAsignacion(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para asignar horarios.');
        }

        $validated = $request->validate([
            'id_horario' => 'required|exists:horario,id',
            'id_docente' => 'required|exists:docente,codigo',
            'sigla_materia' => 'required|exists:materia,sigla',
            'id_grupo' => 'required|exists:grupo,id',
            'id_aula' => 'required|exists:aula,id',
            'id_gestion' => 'required|exists:gestion_academica,id',
            'estado_aula' => 'required|string|in:ocupado,disponible'
        ]);

        DB::beginTransaction();

        try {
            $horario = Horario::findOrFail($validated['id_horario']);

            // Verificar si ya existe grupo_materia para esta combinación en la misma gestión
            $grupoMateria = GrupoMateria::where('sigla_materia', $validated['sigla_materia'])
                ->where('id_grupo', $validated['id_grupo'])
                ->where('id_gestion', $validated['id_gestion'])
                ->first();

            if (!$grupoMateria) {
                // Crear nueva relación grupo_materia
                $grupoMateria = GrupoMateria::create([
                    'sigla_materia' => $validated['sigla_materia'],
                    'id_grupo' => $validated['id_grupo'],
                    'id_gestion' => $validated['id_gestion']
                ]);
            }

            // Verificar conflictos de horario para el aula
            $conflictoAula = $this->verificarConflictoHorario(
                $horario->dia, 
                $horario->hora_inicio, 
                $horario->hora_fin, 
                $validated['id_aula'],
                $validated['id_gestion']
            );

            if ($conflictoAula) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El aula ya está ocupada en este horario para la gestión actual. Por favor, seleccione otro horario o aula.'
                ])->withInput();
            }

            // Verificar conflictos de horario para el docente
            $conflictoDocente = $this->verificarConflictoDocente(
                $horario->dia, 
                $horario->hora_inicio, 
                $horario->hora_fin, 
                $validated['id_docente'],
                $validated['id_gestion']
            );

            if ($conflictoDocente) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El docente ya tiene una clase asignada en este horario para la gestión actual.'
                ])->withInput();
            }

            // Crear la asignación de horario
            $grupoMateriaHorario = GrupoMateriaHorario::create([
                'id_horario' => $validated['id_horario'],
                'id_grupo_materia' => $grupoMateria->id,
                'id_docente' => $validated['id_docente'],
                'id_aula' => $validated['id_aula'],
                'estado_aula' => $validated['estado_aula']
            ]);

            DB::commit();

            // Registrar en bitácora
            $detalles = "Horario asignado: {$horario->dia} {$horario->hora_inicio}-{$horario->hora_fin}, ";
            $detalles .= "Materia: {$validated['sigla_materia']}, ";
            $detalles .= "Grupo: {$validated['id_grupo']}, ";
            $detalles .= "Docente: {$validated['id_docente']}, ";
            $detalles .= "Aula: {$validated['id_aula']}";

            BitacoraController::registrarCreacion(
                'Horario Asignado', 
                $grupoMateriaHorario->id, 
                auth()->id(), 
                $detalles
            );

            return redirect()->route('coordinador.horarios.index')
                ->with('success', 'Horario asignado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al asignar horario: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Error al asignar el horario. Por favor, intente nuevamente.'
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador', 'docente'])) {
            abort(403, 'No tienes permisos para ver horarios.');
        }

        $horarioAsignado = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'grupoMateria.gestion',
            'docente.user',
            'aula'
        ])->findOrFail($id);

        return view('horarios.show', compact('horarioAsignado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para editar horarios.');
        }

        $horarioAsignado = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'grupoMateria.gestion',
            'docente',
            'aula'
        ])->findOrFail($id);

        // Obtener datos para el formulario
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nombre')->get();
        $gestiones = GestionAcademica::orderBy('id', 'desc')->get();
        $horarios = Horario::all();

        return view('horarios.edit', compact(
            'horarioAsignado',
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'gestiones',
            'horarios'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para editar horarios.');
        }

        $validated = $request->validate([
            'id_horario' => 'required|exists:horario,id',
            'id_docente' => 'required|exists:docente,codigo',
            'sigla_materia' => 'required|exists:materia,sigla',
            'id_grupo' => 'required|exists:grupo,id',
            'id_aula' => 'required|exists:aula,id',
            'id_gestion' => 'required|exists:gestion_academica,id',
            'estado_aula' => 'required|string|in:ocupado,disponible'
        ]);

        DB::beginTransaction();

        try {
            $horarioAsignado = GrupoMateriaHorario::findOrFail($id);
            $horario = Horario::findOrFail($validated['id_horario']);

            // Verificar si ya existe grupo_materia para esta combinación
            $grupoMateria = GrupoMateria::where('sigla_materia', $validated['sigla_materia'])
                ->where('id_grupo', $validated['id_grupo'])
                ->where('id_gestion', $validated['id_gestion'])
                ->first();

            if (!$grupoMateria) {
                // Crear nueva relación grupo_materia
                $grupoMateria = GrupoMateria::create([
                    'sigla_materia' => $validated['sigla_materia'],
                    'id_grupo' => $validated['id_grupo'],
                    'id_gestion' => $validated['id_gestion']
                ]);
            }

            // Verificar conflictos de horario para el aula (excluyendo el actual)
            $conflictoAula = $this->verificarConflictoHorario(
                $horario->dia, 
                $horario->hora_inicio, 
                $horario->hora_fin, 
                $validated['id_aula'],
                $validated['id_gestion'],
                $id
            );

            if ($conflictoAula) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El aula ya está ocupada en este horario. Por favor, seleccione otro horario o aula.'
                ])->withInput();
            }

            // Verificar conflictos de horario para el docente (excluyendo el actual)
            $conflictoDocente = $this->verificarConflictoDocente(
                $horario->dia, 
                $horario->hora_inicio, 
                $horario->hora_fin, 
                $validated['id_docente'],
                $validated['id_gestion'],
                $id
            );

            if ($conflictoDocente) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El docente ya tiene una clase asignada en este horario.'
                ])->withInput();
            }

            // Actualizar la asignación de horario
            $horarioAsignado->update([
                'id_horario' => $validated['id_horario'],
                'id_grupo_materia' => $grupoMateria->id,
                'id_docente' => $validated['id_docente'],
                'id_aula' => $validated['id_aula'],
                'estado_aula' => $validated['estado_aula']
            ]);

            DB::commit();

            // Registrar en bitácora
            BitacoraController::registrarActualizacion(
                'Horario', 
                $horarioAsignado->id, 
                auth()->id(), 
                "Horario actualizado exitosamente"
            );

            return redirect()->route('coordinador.horarios.index')
                ->with('success', 'Horario actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al actualizar horario: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Error al actualizar el horario. Por favor, intente nuevamente.'
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para eliminar horarios.');
        }

        DB::beginTransaction();

        try {
            $horarioAsignado = GrupoMateriaHorario::with(['horario', 'grupoMateria.materia', 'docente', 'aula'])->findOrFail($id);
            $horarioAsignado->delete();

            DB::commit();

            // Registrar en bitácora
            BitacoraController::registrarEliminacion(
                'Horario', 
                $id, 
                auth()->id(), 
                "Horario eliminado exitosamente"
            );

            return redirect()->route('coordinador.horarios.index')
                ->with('success', 'Horario eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al eliminar horario: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Error al eliminar el horario. Por favor, intente nuevamente.'
            ]);
        }
    }

    /**
     * Método auxiliar para verificar conflictos de horario en aula
     */
    private function verificarConflictoHorario($dia, $horaInicio, $horaFin, $aulaId, $gestionId, $excluirId = null)
    {
        $query = GrupoMateriaHorario::where('id_aula', $aulaId)
            ->where('estado_aula', 'ocupado')
            ->whereHas('horario', function($q) use ($dia, $horaInicio, $horaFin) {
                $q->where('dia', $dia)
                  ->where(function($query) use ($horaInicio, $horaFin) {
                      $query->where(function($q) use ($horaInicio, $horaFin) {
                          $q->where('hora_inicio', '<', $horaFin)
                            ->where('hora_fin', '>', $horaInicio);
                      });
                  });
            })
            ->whereHas('grupoMateria', function($q) use ($gestionId) {
                $q->where('id_gestion', $gestionId);
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    /**
     * Método auxiliar para verificar conflictos de horario para docente
     */
    private function verificarConflictoDocente($dia, $horaInicio, $horaFin, $docenteId, $gestionId, $excluirId = null)
    {
        $query = GrupoMateriaHorario::where('id_docente', $docenteId)
            ->where('estado_aula', 'ocupado')
            ->whereHas('horario', function($q) use ($dia, $horaInicio, $horaFin) {
                $q->where('dia', $dia)
                  ->where(function($query) use ($horaInicio, $horaFin) {
                      $query->where(function($q) use ($horaInicio, $horaFin) {
                          $q->where('hora_inicio', '<', $horaFin)
                            ->where('hora_fin', '>', $horaInicio);
                      });
                  });
            })
            ->whereHas('grupoMateria', function($q) use ($gestionId) {
                $q->where('id_gestion', $gestionId);
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    private function obtenerFiltrosBitacora(Request $request)
    {
        $filtros = [];
        
        if ($request->filled('docente_id')) {
            $filtros[] = "docente: {$request->docente_id}";
        }
        
        if ($request->filled('materia_id')) {
            $filtros[] = "materia: {$request->materia_id}";
        }
        
        if ($request->filled('grupo_id')) {
            $filtros[] = "grupo: {$request->grupo_id}";
        }

        return $filtros ? implode(', ', $filtros) : 'Sin filtros';
    }

    /**
     * Obtener los cambios realizados en una actualización
     */
    private function obtenerCambiosBitacora(array $datosAntiguos, array $datosNuevos)
    {
        $cambios = [];
        $camposRelevantes = [
            'dia' => 'Día',
            'hora_inicio' => 'Hora inicio',
            'hora_fin' => 'Hora fin',
            'docente' => 'Docente',
            'materia' => 'Materia',
            'grupo' => 'Grupo',
            'aula' => 'Aula',
            'estado' => 'Estado aula'
        ];

        foreach ($camposRelevantes as $campo => $label) {
            if (isset($datosAntiguos[$campo]) && isset($datosNuevos[$campo]) && 
                $datosAntiguos[$campo] != $datosNuevos[$campo]) {
                $cambios[] = "{$label}: '{$datosAntiguos[$campo]}' → '{$datosNuevos[$campo]}'";
            }
        }

        return implode('; ', $cambios);
    }

    /**
     * Vista de lista de horarios para docente (versión mejorada)
     */
    public function indexDocente(Request $request)
    {
        // Obtener el docente actual
        $docente = Docente::where('id_users', auth()->id())->first();
        
        if (!$docente) {
            return view('docente.horarios.sin-docente', [
                'message' => 'No se encontró un perfil de docente asociado a tu usuario.'
            ]);
        }

        // Obtener horarios del docente usando whereHas para ordenar
        $horarios = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'aula'
        ])
        ->where('id_docente', $docente->codigo)
        ->where('estado_aula', 'ocupado')
        ->whereHas('horario', function($query) {
            $query->orderByRaw("
                CASE 
                    WHEN dia = 'LUN' THEN 1
                    WHEN dia = 'MAR' THEN 2
                    WHEN dia = 'MIE' THEN 3
                    WHEN dia = 'JUE' THEN 4
                    WHEN dia = 'VIE' THEN 5
                    WHEN dia = 'SAB' THEN 6
                END
            ")
            ->orderBy('hora_inicio');
        })
        ->paginate(10);

        // Registrar en bitácora
        BitacoraController::registrar(
            'Consulta de horarios como docente',
            'Horario',
            null,
            auth()->id(),
            $request,
            "Docente consultó listado de sus horarios"
        );

        return view('docente.horarios.index', compact('horarios', 'docente'));
    }

    /**
     * Mostrar horario semanal del docente actual
     */
    public function miHorario(Request $request)
    {
        // Obtener el docente actual - CORREGIDO
        $docente = Docente::where('id_users', auth()->id())->first();
        
        if (!$docente) {
            // Si no hay docente asociado, mostrar vista informativa
            return view('docente.horarios.sin-docente', [
                'message' => 'No se encontró un perfil de docente asociado a tu usuario. Contacta con administración.'
            ]);
        }

        // Obtener horarios del docente
        $horarios = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'aula',
            'docente.user' // Incluir la relación con usuario del docente
        ])
        ->where('id_docente', $docente->codigo)
        ->where('estado_aula', 'ocupado')
        ->orderByRaw("
            CASE 
                WHEN horario.dia = 'LUN' THEN 1
                WHEN horario.dia = 'MAR' THEN 2
                WHEN horario.dia = 'MIE' THEN 3
                WHEN horario.dia = 'JUE' THEN 4
                WHEN horario.dia = 'VIE' THEN 5
                WHEN horario.dia = 'SAB' THEN 6
            END,
            horario.hora_inicio
        ")
        ->get();

        // Agrupar por día para la vista
        $horariosPorDia = $horarios->groupBy(function($item) {
            return $item->horario->dia;
        });

        // Días de la semana en orden
        $diasOrdenados = ['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'];

        // Registrar en bitácora
        BitacoraController::registrar(
            'Consulta de horario personal',
            'Horario',
            null,
            auth()->id(),
            $request,
            "Docente consultó su horario personal"
        );

        return view('docente.horarios.mi-horario', compact(
            'horarios',
            'horariosPorDia',
            'diaOrdenados',
            'docente'
        ));
    }
}