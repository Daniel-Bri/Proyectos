<?php

namespace App\Http\Controllers\GestionDeHorarios;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\GrupoMateriaHorario;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// AÑADE ESTA LÍNEA PARA IMPORTAR EL CONTROLADOR DE BITÁCORA
use App\Http\Controllers\Administracion\BitacoraController;

class VisualizacionController extends Controller
{
    public function index(Request $request)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'docente', 'coordinador'])) {
            // REGISTRO EN BITÁCORA: Intento de acceso no autorizado
            BitacoraController::registrar(
                'Intento de acceso no autorizado',
                'Visualización Horarios',
                null,
                auth()->id(),
                $request,
                'Usuario sin permisos intentó acceder a visualización de horarios'
            );
            abort(403, 'No tienes permisos para ver horarios.');
        }

        // REGISTRO EN BITÁCORA: Acceso a visualización de horarios
        BitacoraController::registrar(
            'Consulta de horarios',
            'Visualización Horarios',
            null,
            auth()->id(),
            $request,
            'Acceso a la vista de visualización semanal de horarios'
        );

        // Obtener parámetros de filtro
        $fechaInicio = $request->filled('fecha_inicio') 
            ? Carbon::parse($request->fecha_inicio)->startOfWeek()
            : Carbon::now()->startOfWeek();
            
        $codigoDocente = $request->codigo_docente;
        $docenteId = $request->docente_id;
        $materiaId = $request->materia_id;
        $grupoId = $request->grupo_id;


        // REGISTRO EN BITÁCORA: Filtros aplicados (solo si hay filtros)
        if ($codigoDocente || $docenteId || $materiaId || $grupoId) {
            $detallesFiltros = "Filtros aplicados: ";
            $filtros = [];
            if ($codigoDocente) $filtros[] = "Código docente: $codigoDocente";
            if ($docenteId) $filtros[] = "ID docente: $docenteId";
            if ($materiaId) $filtros[] = "Materia ID: $materiaId";
            if ($grupoId) $filtros[] = "Grupo ID: $grupoId";
            
            BitacoraController::registrar(
                'Aplicación de filtros',
                'Visualización Horarios',
                null,
                auth()->id(),
                $request,
                $detallesFiltros . implode(', ', $filtros)
            );
        }

        // Obtener horarios con filtros aplicados
        $gruposHorarios = $this->obtenerHorariosConFiltros($codigoDocente, $docenteId, $materiaId, $grupoId);

        // Obtener datos para filtros
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'codigo' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre'
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        
        // Obtener grupos para el filtro - CORREGIDO
        try {
            $grupos = Grupo::select('id', 'nombre')
                          ->orderBy('nombre')
                          ->get()
                          ->map(function($grupo) {
                              return [
                                  'id' => $grupo->id,
                                  'codigo' => 'GRP' . str_pad($grupo->id, 3, '0', STR_PAD_LEFT),
                                  'nombre' => $grupo->nombre
                              ];
                          });
        } catch (\Exception $e) {
            \Log::error('Error al obtener grupos: ' . $e->getMessage());
            $grupos = collect([]);
        }

        // Formatear horarios para la vista
        $horariosFormateados = $this->formatearHorariosParaVista($gruposHorarios, $fechaInicio);

        return view('visualizacionSemanal.index', compact(
            'horariosFormateados',
            'docentes',
            'materias',
            'grupos',
            'fechaInicio',
            'codigoDocente',
            'docenteId',
            'materiaId',
            'grupoId'
        ));
    }

    /**
     * Nueva función para vista de calendario semanal - CORREGIDA
     */
    public function calendario(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'docente', 'coordinador'])) {
            // REGISTRO EN BITÁCORA: Intento de acceso no autorizado
            BitacoraController::registrar(
                'Intento de acceso no autorizado',
                'Calendario Horarios',
                null,
                auth()->id(),
                $request,
                'Usuario sin permisos intentó acceder a calendario de horarios'
            );
            abort(403, 'No tienes permisos para ver horarios en calendario.');
        }

        // REGISTRO EN BITÁCORA: Acceso a calendario
        BitacoraController::registrar(
            'Consulta de calendario',
            'Calendario Horarios',
            null,
            auth()->id(),
            $request,
            'Acceso a la vista de calendario semanal de horarios'
        );

        // Obtener parámetros de filtro
        $codigoDocente = $request->codigo_docente;
        $docenteId = $request->docente_id;
        $materiaId = $request->materia_id;
        $grupoId = $request->grupo_id;
        $fechaInicio = $request->filled('fecha_inicio') 
            ? Carbon::parse($request->fecha_inicio)->startOfWeek()
            : Carbon::now()->startOfWeek();

        // Obtener horarios con filtros aplicados
        $gruposHorarios = $this->obtenerHorariosConFiltros($codigoDocente, $docenteId, $materiaId, $grupoId);

        // Obtener datos para filtros
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'codigo' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre'
            ];
        });

        $materias = Materia::orderBy('nombre')->get();
        
        try {
            $grupos = Grupo::select('id', 'nombre')
                        ->orderBy('nombre')
                        ->get()
                        ->map(function($grupo) {
                            return [
                                'id' => $grupo->id,
                                'codigo' => 'GRP' . str_pad($grupo->id, 3, '0', STR_PAD_LEFT),
                                'nombre' => $grupo->nombre
                            ];
                        });
        } catch (\Exception $e) {
            $grupos = collect([]);
        }

        // Formatear horarios para la vista de calendario
        $horariosFormateados = $this->formatearHorariosParaVista($gruposHorarios, $fechaInicio);

        return view('visualizacionSemanal.calendario', compact(
            'horariosFormateados',
            'docentes',
            'materias',
            'grupos',
            'fechaInicio',
            'codigoDocente',
            'docenteId',
            'materiaId',
            'grupoId'
        ));
    }

    /**
     * Función auxiliar para obtener horarios con filtros aplicados
     */
    private function obtenerHorariosConFiltros($codigoDocente = null, $docenteId = null, $materiaId = null, $grupoId = null)
    {
        $query = GrupoMateriaHorario::with([
                'grupoMateria.materia',
                'grupoMateria.grupo', 
                'aula',
                'docente.user',
                'horario' // AÑADIR ESTA RELACIÓN
            ])
            ->join('horario', 'grupo_materia_horario.id_horario', '=', 'horario.id')
            ->where('grupo_materia_horario.estado_aula', 'ocupado')
            ->select('grupo_materia_horario.*', 'horario.dia', 'horario.hora_inicio', 'horario.hora_fin') // SELECCIONAR CAMPOS NECESARIOS
            ->orderBy('horario.dia')
            ->orderBy('horario.hora_inicio');

        // Aplicar filtros - CORREGIDO
        if ($codigoDocente) {
            $query->whereHas('docente', function($q) use ($codigoDocente) {
                $q->where('codigo', 'LIKE', '%' . $codigoDocente . '%');
            });
        }

        if ($docenteId) {
            $query->where('grupo_materia_horario.id_docente', $docenteId);
        }

        if ($materiaId) {
            $query->whereHas('grupoMateria.materia', function($q) use ($materiaId) {
                $q->where('sigla', $materiaId);
            });
        }

        if ($grupoId) {
            $query->whereHas('grupoMateria.grupo', function($q) use ($grupoId) {
                $q->where('id', $grupoId);
            });
        }

        $resultados = $query->get();
        
        // DEBUG: Log de resultados
        

        return $resultados;
    }

    /**
     * Formatear horarios para la vista semanal - CORREGIDO
     */
    private function formatearHorariosParaVista($gruposHorarios, $fechaInicio)
    {
        // Inicializar la variable desde el principio
        $horariosFormateados = [];

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

        // Inicializar estructura para cada día
        foreach ($diasSemana as $numeroDia => $nombreDia) {
            $fechaDia = $fechaInicio->copy()->addDays($numeroDia - 1);
            $horariosFormateados[$nombreDia] = [
                'fecha' => $fechaDia->format('Y-m-d'),
                'dia_numero' => $numeroDia,
                'horarios' => []
            ];
        }

        // Agrupar horarios por día
        foreach ($gruposHorarios as $grupoHorario) {
            try {
                $diaDB = $grupoHorario->horario->dia ?? null;
                $numeroDia = $diasDB[$diaDB] ?? null;
                
                if ($numeroDia && isset($diasSemana[$numeroDia])) {
                    $nombreDia = $diasSemana[$numeroDia];
                    $grupoMateria = $grupoHorario->grupoMateria;
                    
                    if ($grupoMateria) {
                        $docenteNombre = $grupoHorario->docente->user->name ?? 'Docente no asignado';
                        $codigoDocente = $grupoHorario->docente->codigo ?? 'Sin código';
                        $grupoNombre = $grupoMateria->grupo->nombre ?? 'Sin grupo';
                        $grupoCodigo = 'GRP' . str_pad($grupoMateria->grupo->id ?? '000', 3, '0', STR_PAD_LEFT);
                        
                        $horarioData = [
                            'id' => $grupoHorario->id,
                            'hora_inicio' => Carbon::parse($grupoHorario->horario->hora_inicio ?? '00:00:00')->format('H:i'),
                            'hora_fin' => Carbon::parse($grupoHorario->horario->hora_fin ?? '00:00:00')->format('H:i'),

                            'materia' => $grupoMateria->materia->nombre ?? 'Sin materia',
                            'sigla_materia' => $grupoMateria->materia->sigla ?? 'Sin sigla',
                            'docente' => $docenteNombre,
                            'codigo_docente' => $codigoDocente,
                            'aula' => $grupoHorario->aula->nombre ?? 'Sin aula',
                            'grupo' => $grupoNombre,
                            'grupo_codigo' => $grupoCodigo,
                            'color' => $this->getColorMateria($grupoMateria->sigla_materia ?? ''),
                            'duracion' => $this->calcularDuracion(
                                $grupoHorario->horario->hora_inicio ?? '00:00:00', 
                                $grupoHorario->horario->hora_fin ?? '00:00:00'
                            )
                        ];

                        // Asegurarse de que el día existe en el array
                        if (isset($horariosFormateados[$nombreDia])) {
                            $horariosFormateados[$nombreDia]['horarios'][] = $horarioData;
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error procesando horario: ' . $e->getMessage());
            }
        }

        return $horariosFormateados;
    }

    /**
     * API endpoint para obtener horarios (para AJAX)
     */
    public function apiHorarios(Request $request)
    {
        try {
            $validated = $request->validate([
                'fecha_inicio' => 'required|date',
                'codigo_docente' => 'nullable|string',
                'docente_id' => 'nullable|string',
                'materia_id' => 'nullable|string',
                'grupo_id' => 'nullable|string'
            ]);

            // REGISTRO EN BITÁCORA: Consulta API
            BitacoraController::registrar(
                'Consulta API horarios',
                'API Horarios',
                null,
                auth()->id(),
                $request,
                'Solicitud de horarios vía API con filtros'
            );

            $fechaInicio = Carbon::parse($validated['fecha_inicio'])->startOfWeek();

            $gruposHorarios = $this->obtenerHorariosConFiltros(
                $validated['codigo_docente'] ?? null,
                $validated['docente_id'] ?? null,
                $validated['materia_id'] ?? null,
                $validated['grupo_id'] ?? null
            );

            $horariosFormateados = $this->formatearHorariosParaAPI($gruposHorarios, $fechaInicio);

            return response()->json([
                'success' => true,
                'data' => $horariosFormateados
            ]);

        } catch (\Exception $e) {
            // REGISTRO EN BITÁCORA: Error en API
            BitacoraController::registrar(
                'Error en API horarios',
                'API Horarios',
                null,
                auth()->id(),
                $request,
                'Error al cargar horarios: ' . $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los horarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formatear horarios para API
     */
    private function formatearHorariosParaAPI($gruposHorarios, $fechaInicio)
    {
        $resultado = [];
        
        foreach ($gruposHorarios as $grupoHorario) {
            $grupoMateria = $grupoHorario->grupoMateria;
            
            if ($grupoMateria) {
                $docenteNombre = $grupoHorario->docente->user->name ?? 'Docente no asignado';
                $codigoDocente = $grupoHorario->docente->codigo ?? 'Sin código';
                $grupoCodigo = 'GRP' . str_pad($grupoMateria->grupo->id ?? '000', 3, '0', STR_PAD_LEFT);
                
                $resultado[] = [
                    'id' => $grupoHorario->id,
                    'dia' => $this->getDiaNumero($grupoHorario->horario->dia ?? 'LUN'),
                    'hora_inicio' => $grupoHorario->horario->hora_inicio ?? 'Sin hora',
                    'hora_fin' => $grupoHorario->horario->hora_fin ?? 'Sin hora',
                    'materia' => $grupoMateria->materia->nombre ?? 'Sin materia',
                    'sigla_materia' => $grupoMateria->materia->sigla ?? 'Sin sigla',
                    'docente' => $docenteNombre,
                    'codigo_docente' => $codigoDocente,
                    'aula' => $grupoHorario->aula->nombre ?? 'Sin aula',
                    'grupo' => $grupoMateria->grupo->nombre ?? 'Sin grupo',
                    'grupo_codigo' => $grupoCodigo,
                    'color' => $this->getColorMateria($grupoMateria->sigla_materia ?? ''),
                    'duracion' => $this->calcularDuracion(
                        $grupoHorario->horario->hora_inicio ?? '00:00:00', 
                        $grupoHorario->horario->hora_fin ?? '00:00:00'
                    )
                ];
            }
        }
        
        return $resultado;
    }

    /**
     * Obtener número del día
     */
    private function getDiaNumero($diaDB)
    {
        $diasDB = [
            'LUN' => 0,
            'MAR' => 1, 
            'MIE' => 2,
            'JUE' => 3,
            'VIE' => 4,
            'SAB' => 5
        ];
        
        return $diasDB[$diaDB] ?? 0;
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
        
        $hash = crc32($siglaMateria) % count($colores);
        return $colores[$hash];
    }

    /**
     * Calcular duración en horas
     */
    private function calcularDuracion($horaInicio, $horaFin)
    {
        try {
            $inicio = Carbon::parse($horaInicio);
            $fin = Carbon::parse($horaFin);
            return $fin->diffInHours($inicio);
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'docente', 'estudiante'])) {
            // REGISTRO EN BITÁCORA: Intento de acceso no autorizado
            BitacoraController::registrar(
                'Intento de acceso no autorizado',
                'Detalle Horario',
                $id,
                auth()->id(),
                request(),
                'Usuario sin permisos intentó ver detalle de horario'
            );
            abort(403, 'No tienes permisos para ver horarios.');
        }

        if (!is_numeric($id)) {
            abort(404, 'Horario no encontrado.');
        }

        // REGISTRO EN BITÁCORA: Consulta de detalle
        BitacoraController::registrar(
            'Consulta detalle horario',
            'Detalle Horario',
            $id,
            auth()->id(),
            request(),
            'Visualización de detalles del horario ID: ' . $id
        );

        $horario = Horario::with([
            'grupoMateriaHorarios.aula',
            'grupoMateriaHorarios.grupoMateria.materia',
            'grupoMateriaHorarios.grupoMateria.grupo'
        ])->findOrFail($id);

        return view('visualizacionSemanal.show', compact('horario'));
    }
}