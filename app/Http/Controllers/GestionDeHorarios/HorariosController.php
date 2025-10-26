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
use App\Models\GestionAcademica; // Añadir este import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HorariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para gestionar horarios.');
        }

        // Obtener parámetros de filtro
        $docenteId = $request->docente_id;
        $materiaId = $request->materia_id;
        $grupoId = $request->grupo_id;

        // Consulta principal de horarios asignados - CON DEBUG
        $query = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'docente.user',
            'aula'
        ])
        ->where('estado_aula', 'ocupado')
        ->orderBy('id', 'desc');

        // Debug: ver qué datos estamos obteniendo
        $debugHorarios = $query->get();
        
        // Log para verificar los datos
        foreach ($debugHorarios as $horario) {
            \Log::info('Horario ID: ' . $horario->id);
            \Log::info('Docente: ' . ($horario->docente ? $horario->docente->codigo : 'NULL'));
            \Log::info('Materia: ' . ($horario->grupoMateria && $horario->grupoMateria->materia ? $horario->grupoMateria->materia->nombre : 'NULL'));
            \Log::info('Grupo: ' . ($horario->grupoMateria && $horario->grupoMateria->grupo ? $horario->grupoMateria->grupo->nombre : 'NULL'));
            \Log::info('Aula: ' . ($horario->aula ? $horario->aula->nombre : 'NULL'));
        }

        // Aplicar filtros (mantener tu lógica original)
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para crear horarios.');
        }

        // Obtener la gestión académica activa
        $gestionActiva = GestionAcademica::where('estado', 'curso')->first();

        if (!$gestionActiva) {
            return back()->withErrors([
                'error' => 'No hay una gestión académica activa. Configure una gestión primero.'
            ]);
        }

        // Obtener datos para el formulario
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo, // CORREGIDO: usar codigo como id
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nombre')->get(); // Quitar filtro de estado

        // Días de la semana
        $dias = [
            'LUN' => 'Lunes',
            'MAR' => 'Martes',
            'MIE' => 'Miércoles',
            'JUE' => 'Jueves',
            'VIE' => 'Viernes',
            'SAB' => 'Sábado'
        ];

        return view('horarios.create', compact(
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'dias',
            'gestionActiva'
        ));
    }

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

        // Obtener horarios disponibles (sin asignar)
        $horariosDisponibles = Horario::whereDoesntHave('grupoMateriaHorarios', function($query) {
            $query->where('estado_aula', 'ocupado');
        })->get();

        // Obtener datos para el formulario
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo, // CORREGIDO: usar codigo como id
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nombre')->get(); // Quitar filtro de estado

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para crear horarios.');
        }

        // Validación de datos
        $validated = $request->validate([
            'dia' => 'required|string|in:LUN,MAR,MIE,JUE,VIE,SAB',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'id_docente' => 'required|exists:docente,codigo', // CORREGIDO: existe en docente con codigo
            'sigla_materia' => 'required|exists:materia,sigla',
            'id_grupo' => 'required|exists:grupo,id',
            'id_aula' => 'required|exists:aula,id',
            'id_gestion' => 'required|exists:gestion_academica,id' // AÑADIDO: gestión es requerida
        ]);

        DB::beginTransaction();

        try {
            // Verificar si el horario ya existe
            $horarioExistente = Horario::where('dia', $validated['dia'])
                ->where('hora_inicio', $validated['hora_inicio'])
                ->where('hora_fin', $validated['hora_fin'])
                ->first();

            if (!$horarioExistente) {
                // Crear nuevo horario
                $horarioExistente = Horario::create([
                    'dia' => $validated['dia'],
                    'hora_inicio' => $validated['hora_inicio'],
                    'hora_fin' => $validated['hora_fin']
                ]);
            }

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
                $grupoMateriaId = $grupoMateria->id;
            } else {
                $grupoMateriaId = $grupoMateria->id;
            }

            // Verificar conflictos de horario para el aula
            $conflictoAula = $this->verificarConflictoHorario(
                $validated['dia'], 
                $validated['hora_inicio'], 
                $validated['hora_fin'], 
                $validated['id_aula']
            );

            if ($conflictoAula) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El aula ya está ocupada en este horario. Por favor, seleccione otro horario o aula.'
                ])->withInput();
            }

            // Verificar conflictos de horario para el docente
            $conflictoDocente = $this->verificarConflictoDocente(
                $validated['dia'], 
                $validated['hora_inicio'], 
                $validated['hora_fin'], 
                $validated['id_docente']
            );

            if ($conflictoDocente) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El docente ya tiene una clase asignada en este horario.'
                ])->withInput();
            }

            // Crear la asignación de horario - estado_aula siempre será 'ocupado'
            GrupoMateriaHorario::create([
                'id_horario' => $horarioExistente->id,
                'id_grupo_materia' => $grupoMateriaId,
                'id_docente' => $validated['id_docente'],
                'id_aula' => $validated['id_aula'],
                'estado_aula' => 'ocupado' // SIEMPRE OCUPADO CUANDO SE CREA
            ]);

            DB::commit();

            return redirect()->route('horarios.index')
                ->with('success', 'Horario asignado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al crear horario: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Error al asignar el horario. Por favor, intente nuevamente.'
            ])->withInput();
        }
    }

    public function storeAsignacion(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para asignar horarios.');
        }

        $validated = $request->validate([
            'id_horario' => 'required|exists:horario,id',
            'id_docente' => 'required|exists:docente,codigo', // CORREGIDO: existe en docente con codigo
            'sigla_materia' => 'required|exists:materia,sigla',
            'id_grupo' => 'required|exists:grupo,id',
            'id_aula' => 'required|exists:aula,id',
            'id_gestion' => 'required|exists:gestion_academica,id' // AÑADIDO
        ]);

        DB::beginTransaction();

        try {
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
                $grupoMateriaId = $grupoMateria->id;
            } else {
                $grupoMateriaId = $grupoMateria->id;
            }

            // Verificar conflictos de horario para el aula
            $conflictoAula = $this->verificarConflictoHorario(
                $horario->dia, 
                $horario->hora_inicio, 
                $horario->hora_fin, 
                $validated['id_aula']
            );

            if ($conflictoAula) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El aula ya está ocupada en este horario. Por favor, seleccione otro horario o aula.'
                ])->withInput();
            }

            // Verificar conflictos de horario para el docente
            $conflictoDocente = $this->verificarConflictoDocente(
                $horario->dia, 
                $horario->hora_inicio, 
                $horario->hora_fin, 
                $validated['id_docente']
            );

            if ($conflictoDocente) {
                DB::rollBack();
                return back()->withErrors([
                    'error' => 'El docente ya tiene una clase asignada en este horario.'
                ])->withInput();
            }

            // Crear la asignación de horario
            GrupoMateriaHorario::create([
                'id_horario' => $validated['id_horario'],
                'id_grupo_materia' => $grupoMateriaId,
                'id_docente' => $validated['id_docente'],
                'id_aula' => $validated['id_aula'],
                'estado_aula' => 'ocupado' // SIEMPRE OCUPADO
            ]);

            DB::commit();

            return redirect()->route('horarios.index')
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

        // Obtener la gestión académica activa
        $gestionActiva = GestionAcademica::where('estado', 'curso')->first();

        // Obtener datos para el formulario
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo, // CORREGIDO
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nombre')->get();
        $gestiones = GestionAcademica::orderBy('id', 'desc')->get();

        // Días de la semana
        $dias = [
            'LUN' => 'Lunes',
            'MAR' => 'Martes',
            'MIE' => 'Miércoles',
            'JUE' => 'Jueves',
            'VIE' => 'Viernes',
            'SAB' => 'Sábado'
        ];

        return view('horarios.edit', compact(
            'horarioAsignado',
            'docentes',
            'materias',
            'grupos',
            'aulas',
            'dias',
            'gestionActiva',
            'gestiones'
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
            'dia' => 'required|string|in:LUN,MAR,MIE,JUE,VIE,SAB',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'id_docente' => 'required|exists:docente,codigo', // CORREGIDO
            'sigla_materia' => 'required|exists:materia,sigla',
            'id_grupo' => 'required|exists:grupo,id',
            'id_aula' => 'required|exists:aula,id',
            'id_gestion' => 'required|exists:gestion_academica,id',
            'estado_aula' => 'required|string|in:ocupado,disponible' // MANTENER para edición
        ]);

        DB::beginTransaction();

        try {
            $horarioAsignado = GrupoMateriaHorario::findOrFail($id);

            // Verificar si el horario ya existe
            $horarioExistente = Horario::where('dia', $validated['dia'])
                ->where('hora_inicio', $validated['hora_inicio'])
                ->where('hora_fin', $validated['hora_fin'])
                ->first();

            if (!$horarioExistente) {
                // Crear nuevo horario
                $horarioExistente = Horario::create([
                    'dia' => $validated['dia'],
                    'hora_inicio' => $validated['hora_inicio'],
                    'hora_fin' => $validated['hora_fin']
                ]);
            }

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
                $grupoMateriaId = $grupoMateria->id;
            } else {
                $grupoMateriaId = $grupoMateria->id;
            }

            // Verificar conflictos de horario para el aula (excluyendo el actual)
            $conflictoAula = $this->verificarConflictoHorario(
                $validated['dia'], 
                $validated['hora_inicio'], 
                $validated['hora_fin'], 
                $validated['id_aula'],
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
                $validated['dia'], 
                $validated['hora_inicio'], 
                $validated['hora_fin'], 
                $validated['id_docente'],
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
                'id_horario' => $horarioExistente->id,
                'id_grupo_materia' => $grupoMateriaId,
                'id_docente' => $validated['id_docente'],
                'id_aula' => $validated['id_aula'],
                'estado_aula' => $validated['estado_aula']
            ]);

            DB::commit();

            return redirect()->route('horarios.index')
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
            $horarioAsignado = GrupoMateriaHorario::findOrFail($id);
            $horarioAsignado->delete();

            DB::commit();

            return redirect()->route('horarios.index')
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
     * Obtener horarios disponibles para un docente
     */
    public function getHorariosDocente(Request $request)
    {
        $docenteId = $request->docente_id;
        
        $horarios = GrupoMateriaHorario::with(['horario', 'grupoMateria.materia', 'grupoMateria.grupo', 'aula'])
            ->where('id_docente', $docenteId)
            ->get()
            ->map(function($horario) {
                return [
                    'id' => $horario->id,
                    'dia' => $horario->horario->dia,
                    'hora_inicio' => $horario->horario->hora_inicio,
                    'hora_fin' => $horario->horario->hora_fin,
                    'materia' => $horario->grupoMateria->materia->nombre,
                    'grupo' => $horario->grupoMateria->grupo->nombre,
                    'aula' => $horario->aula->nombre
                ];
            });

        return response()->json($horarios);
    }

    /**
     * Método auxiliar para verificar conflictos de horario en aula
     */
    private function verificarConflictoHorario($dia, $horaInicio, $horaFin, $aulaId, $excluirId = null)
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
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    /**
     * Método auxiliar para verificar conflictos de horario para docente
     */
    private function verificarConflictoDocente($dia, $horaInicio, $horaFin, $docenteId, $excluirId = null)
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
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }
}