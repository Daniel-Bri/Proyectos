<?php

namespace App\Http\Controllers\GestionDeHorarios;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\GrupoMateriaHorario;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisualizacionController extends Controller
{
    public function index(Request $request)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'docente', 'estudiante'])) {
            abort(403, 'No tienes permisos para ver horarios.');
        }

        // Obtener parámetros de filtro
        $fechaInicio = $request->filled('fecha_inicio') 
            ? Carbon::parse($request->fecha_inicio)->startOfWeek()
            : Carbon::now()->startOfWeek();
            
        $docenteId = $request->docente_id;
        $materiaId = $request->materia_id;
        $aulaId = $request->aula_id;

        // DEBUG: Mostrar parámetros recibidos
        \Log::info('Filtros recibidos:', [
            'docente_id' => $docenteId,
            'materia_id' => $materiaId, 
            'aula_id' => $aulaId,
            'fecha_inicio' => $fechaInicio
        ]);

        // CONSULTA CON JOIN EXPLÍCITO - VERSIÓN FUNCIONAL
        $query = GrupoMateriaHorario::with([
                'grupoMateria.materia',
                'grupoMateria.grupo', 
                'aula',
                'docente.user'
            ])
            ->join('horario', 'grupo_materia_horario.id_horario', '=', 'horario.id')
            ->where('grupo_materia_horario.estado_aula', 'ocupado')
            ->select('grupo_materia_horario.*')
            ->orderBy('horario.dia')
            ->orderBy('horario.hora_inicio');

        // Aplicar filtros
        if ($docenteId) {
            $query->where('grupo_materia_horario.id_docente', $docenteId);
            
            // DEBUG: Verificar si hay resultados con este docente
            $count = $query->count();
            \Log::info("Resultados para docente {$docenteId}: {$count}");
        }

        if ($materiaId) {
            $query->whereHas('grupoMateria', function($q) use ($materiaId) {
                $q->where('sigla_materia', $materiaId);
            });
        }

        if ($aulaId) {
            $query->where('grupo_materia_horario.id_aula', $aulaId);
        }

        $gruposHorarios = $query->get();

        // DEBUG: Verificar resultados finales
        \Log::info('Resultados obtenidos:', [
            'total' => $gruposHorarios->count(),
            'datos' => $gruposHorarios->pluck('id_docente', 'id')
        ]);

        // Obtener datos para filtros
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'codigo' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre'
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nombre')->get();

        // Formatear horarios para la vista
        $horariosFormateados = $this->formatearHorariosParaVista($gruposHorarios, $fechaInicio);

        return view('visualizacionSemanal.index', compact(
            'horariosFormateados',
            'docentes',
            'materias',
            'aulas',
            'fechaInicio',
            'docenteId',
            'materiaId',
            'aulaId'
        ));
    }

    /**
     * Formatear horarios para la vista semanal
     */
    private function formatearHorariosParaVista($gruposHorarios, $fechaInicio)
    {
        // DEBUG: Verificar entrada
        \Log::info('Datos entrando a formatearHorariosParaVista:', [
            'total_grupos_horarios' => $gruposHorarios->count(),
            'primer_registro' => $gruposHorarios->first() ? [
                'id' => $gruposHorarios->first()->id,
                'docente' => $gruposHorarios->first()->id_docente,
                'horario_dia' => $gruposHorarios->first()->horario->dia ?? 'No disponible',
                'horario_hora' => $gruposHorarios->first()->horario->hora_inicio ?? 'No disponible'
            ] : 'No hay datos'
        ]);

        $diasSemana = [
            1 => 'Lunes',
            2 => 'Martes', 
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado'
        ];

        // Mapeo de días de la base de datos a números
        $diasDB = [
            'LUN' => 1,
            'MAR' => 2, 
            'MIE' => 3,
            'JUE' => 4,
            'VIE' => 5,
            'SAB' => 6
        ];

        $horariosPorDia = [];

        // Inicializar estructura para cada día
        foreach ($diasSemana as $numeroDia => $nombreDia) {
            $fechaDia = $fechaInicio->copy()->addDays($numeroDia - 1);
            $horariosPorDia[$nombreDia] = [
                'fecha' => $fechaDia->format('Y-m-d'),
                'dia_numero' => $numeroDia,
                'horarios' => []
            ];
        }

        // Agrupar horarios por día - CORREGIDO
        foreach ($gruposHorarios as $grupoHorario) {
            // Obtener el día numérico desde la base de datos
            $diaDB = $grupoHorario->horario->dia ?? null;
            $numeroDia = $diasDB[$diaDB] ?? null;
            
            if ($numeroDia && isset($diasSemana[$numeroDia])) {
                $nombreDia = $diasSemana[$numeroDia];
                
                $grupoMateria = $grupoHorario->grupoMateria;
                
                if ($grupoMateria) {
                    $docenteNombre = $grupoHorario->docente->user->name ?? 'Docente no asignado';
                    
                    $horariosPorDia[$nombreDia]['horarios'][] = [
                        'id' => $grupoHorario->id,
                        'hora_inicio' => $grupoHorario->horario->hora_inicio ?? 'Sin hora',
                        'hora_fin' => $grupoHorario->horario->hora_fin ?? 'Sin hora',
                        'materia' => $grupoMateria->materia->nombre ?? 'Sin materia',
                        'docente' => $docenteNombre,
                        'aula' => $grupoHorario->aula->nombre ?? 'Sin aula',
                        'grupo' => $grupoMateria->grupo->nombre ?? 'Sin grupo',
                        'color' => $this->getColorMateria($grupoMateria->sigla_materia ?? ''),
                        'duracion' => $this->calcularDuracion(
                            $grupoHorario->horario->hora_inicio ?? '00:00:00', 
                            $grupoHorario->horario->hora_fin ?? '00:00:00'
                        )
                    ];
                }
            } else {
                \Log::warning('Día no reconocido en horario:', [
                    'id' => $grupoHorario->id,
                    'dia_db' => $diaDB,
                    'numero_dia' => $numeroDia
                ]);
            }
        }

        // DEBUG: Verificar salida
        \Log::info('Datos saliendo de formatearHorariosParaVista:', [
            'total_dias_con_horarios' => count(array_filter($horariosPorDia, function($dia) {
                return count($dia['horarios']) > 0;
            })),
            'horarios_por_dia' => array_map(function($dia) {
                return count($dia['horarios']);
            }, $horariosPorDia)
        ]);

        return $horariosPorDia;
    }

    /**
     * Obtener docente asignado a un grupo materia
     * (Método deshabilitado hasta que exista la tabla docente_grupo_materia)
     */
    private function obtenerDocenteDeGrupoMateria($grupoMateriaId)
    {
        return 'Docente no asignado';
        
        // Código comentado hasta que exista la tabla docente_grupo_materia
        /*
        try {
            $tableExists = DB::select("SELECT EXISTS (
                SELECT FROM information_schema.tables 
                WHERE table_schema = 'public' 
                AND table_name = 'docente_grupo_materia'
            ) as exists_table")[0]->exists_table;

            if ($tableExists) {
                $docente = DB::table('docente_grupo_materia as dgm')
                    ->join('docente as d', 'dgm.codigo_docente', '=', 'd.codigo')
                    ->join('users as u', 'd.id_users', '=', 'u.id')
                    ->where('dgm.id_grupo_materia', $grupoMateriaId)
                    ->select('u.name as nombre_docente')
                    ->first();

                return $docente->nombre_docente ?? 'Sin docente asignado';
            }
            
            return 'Docente no asignado';
            
        } catch (\Exception $e) {
            return 'Sin docente';
        }
        */
    }

    /**
     * API endpoint para obtener horarios (para AJAX)
     */
    public function apiHorarios(Request $request)
    {
        try {
            // Validar request
            $validated = $request->validate([
                'fecha_inicio' => 'required|date',
                'docente_id' => 'nullable|string',
                'materia_id' => 'nullable|string',
                'aula_id' => 'nullable|integer'
            ]);

            $fechaInicio = Carbon::parse($validated['fecha_inicio'])->startOfWeek();

            $query = Horario::select('horario.*')
                ->join('grupo_materia_horario as gmh', 'horario.id', '=', 'gmh.id_horario')
                ->join('grupo_materia as gm', 'gmh.id_grupo_materia', '=', 'gm.id')
                ->join('materia as m', 'gm.sigla_materia', '=', 'm.sigla')
                ->join('grupo as g', 'gm.id_grupo', '=', 'g.id')
                ->join('aula as a', 'gmh.id_aula', '=', 'a.id')
                ->orderBy('horario.dia')
                ->orderBy('horario.hora_inicio');

            // Aplicar filtros (solo materia y aula)
            if (!empty($validated['materia_id'])) {
                $query->where('gm.sigla_materia', $validated['materia_id']);
            }

            if (!empty($validated['aula_id'])) {
                $query->where('gmh.id_aula', $validated['aula_id']);
            }

            $horarios = $query->get();

            // Cargar relaciones
            $horarios->load([
                'grupoMateriaHorarios.aula',
                'grupoMateriaHorarios.grupoMateria.materia',
                'grupoMateriaHorarios.grupoMateria.grupo'
            ]);

            $horariosFormateados = $this->formatearHorariosParaAPI($horarios, $fechaInicio);

            return response()->json([
                'success' => true,
                'data' => $horariosFormateados,
                'semana' => [
                    'inicio' => $fechaInicio->format('Y-m-d'),
                    'fin' => $fechaInicio->copy()->endOfWeek()->format('Y-m-d'),
                    'texto' => $fechaInicio->format('d M') . ' - ' . $fechaInicio->copy()->endOfWeek()->format('d M, Y')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los horarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formatear horarios para API
     */
    private function formatearHorariosParaAPI($horarios, $fechaInicio)
    {
        $resultado = [];
        
        foreach ($horarios as $horario) {
            foreach ($horario->grupoMateriaHorarios as $grupoMateriaHorario) {
                $grupoMateria = $grupoMateriaHorario->grupoMateria;
                
                if ($grupoMateria) {
                    $resultado[] = [
                        'id' => $horario->id,
                        'dia' => $horario->dia - 1,
                        'hora_inicio' => $horario->hora_inicio,
                        'hora_fin' => $horario->hora_fin,
                        'materia' => $grupoMateria->materia->nombre ?? 'Sin materia',
                        'docente' => 'Docente no asignado',
                        'aula' => $grupoMateriaHorario->aula->nombre ?? 'Sin aula',
                        'grupo' => $grupoMateria->grupo->nombre ?? 'Sin grupo',
                        'color' => $this->getColorMateria($grupoMateria->sigla_materia ?? ''),
                        'duracion' => $this->calcularDuracion($horario->hora_inicio, $horario->hora_fin)
                    ];
                }
            }
        }
        
        return $resultado;
    }

    /**
     * Obtener color para la materia
     */
    private function getColorMateria($siglaMateria)
    {
        $colores = [
            '#3498db', '#9b59b6', '#e74c3c', '#2ecc71', '#f39c12',
            '#1abc9c', '#34495e', '#d35400', '#c0392b', '#8e44ad'
        ];
        
        // Usar el hash de la sigla para obtener un color consistente
        $hash = crc32($siglaMateria) % count($colores);
        return $colores[$hash];
    }

    /**
     * Calcular duración en horas
     */
    private function calcularDuracion($horaInicio, $horaFin)
    {
        $inicio = Carbon::parse($horaInicio);
        $fin = Carbon::parse($horaFin);
        
        return $fin->diffInHours($inicio);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'docente', 'estudiante'])) {
            abort(403, 'No tienes permisos para ver horarios.');
        }

        // Validar que el ID sea numérico
        if (!is_numeric($id)) {
            abort(404, 'Horario no encontrado.');
        }

        $horario = Horario::with([
            'grupoMateriaHorarios.aula',
            'grupoMateriaHorarios.grupoMateria.materia',
            'grupoMateriaHorarios.grupoMateria.grupo'
        ])->findOrFail($id);

        return view('visualizacionSemanal.show', compact('horario'));
    }
}