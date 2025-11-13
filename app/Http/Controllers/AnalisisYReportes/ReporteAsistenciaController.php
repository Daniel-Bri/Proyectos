<?php

namespace App\Http\Controllers\AnalisisYReportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\GestionAcademica;
use App\Models\GrupoMateriaHorario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Administracion\BitacoraController;
use Illuminate\Support\Facades\Log;

class ReporteAsistenciaController extends Controller
{
    /**
     * Mostrar formulario principal de reportes de asistencia
     */
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para generar reportes de asistencia.');
        }

        // Registrar en bitácora
        BitacoraController::registrar('Acceso', 'Reporte Asistencia', null, auth()->id(), null, 'Accedió al formulario de reportes de asistencia');

        // Obtener datos para filtros
        $docentes = Docente::with('user')->get()->map(function($docente) {
            return [
                'id' => $docente->codigo,
                'nombre' => $docente->user->name ?? 'Sin nombre',
                'codigo' => $docente->codigo
            ];
        });

        $grupos = Grupo::orderBy('nombre')->get();
        $materias = Materia::orderBy('nombre')->get();
        $gestiones = GestionAcademica::orderBy('id', 'desc')->get();
        $gestionActiva = GestionAcademica::where('estado', 'curso')->first();

        return view('reportes.asistencia.index', compact(
            'docentes', 'grupos', 'materias', 'gestiones', 'gestionActiva'
        ));
    }

    /**
     * Generar reporte de asistencia - SOLO PARA EXPORTACIONES (POST)
     */
    public function generarReporte(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para generar reportes de asistencia.');
        }

        try {
            // Validación para exportaciones
            $validated = $request->validate([
                'tipo_reporte' => 'required|in:docente,grupo',
                'id_gestion' => 'required|exists:gestion_academica,id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'id_docente' => 'nullable|required_if:tipo_reporte,docente',
                'id_grupo' => 'nullable|required_if:tipo_reporte,grupo',
                'id_materia' => 'nullable',
                'estado_asistencia' => 'nullable|in:presente,tardanza,ausente,todos',
                'exportar_pdf' => 'nullable|boolean',
                'exportar_csv' => 'nullable|boolean',
                'exportar_xlsx' => 'nullable|boolean'
            ]);

            // Obtener asistencias según los filtros
            $asistencias = $this->obtenerAsistenciasFiltradas($validated);
            
            // Obtener gestión
            $gestion = GestionAcademica::findOrFail($validated['id_gestion']);

            // Procesar datos según el tipo de reporte
            $datosProcesados = $this->procesarDatosReporte($asistencias, $validated['tipo_reporte']);

            // Calcular estadísticas
            $estadisticas = $this->calcularEstadisticasAsistencia($asistencias, $validated);

            // Registrar en bitácora
            $detallesBitacora = $this->generarDetallesBitacora($validated, $estadisticas);
            BitacoraController::registrar('Exportación', 'Reporte Asistencia', null, auth()->id(), null, $detallesBitacora);

            // Exportar PDF si se solicita
            if ($request->has('exportar_pdf') && $request->exportar_pdf) {
                return $this->exportarPDF($datosProcesados, $estadisticas, $validated, $gestion);
            }

            // Exportar CSV si se solicita
            if ($request->has('exportar_csv') && $request->exportar_csv) {
                return $this->exportarCSV($asistencias, $validated, $gestion, $estadisticas);
            }

            // Exportar XLSX si se solicita
            if ($request->has('exportar_xlsx') && $request->exportar_xlsx) {
                return $this->exportarXLSX($asistencias, $validated, $gestion, $estadisticas);
            }

            // Si no hay exportación, redirigir a vista previa
            return redirect()->route('admin.reportes.asistencia.vista-previa', $request->all());

        } catch (\Exception $e) {
            Log::error('Error al generar reporte de asistencia: ' . $e->getMessage());
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Reporte Asistencia', null, auth()->id(), null, 'Error al generar reporte: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Vista previa del reporte - SOLO PARA VISTA PREVIA (GET)
     */
    public function vistaPrevia(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para generar reportes de asistencia.');
        }

        try {
            // Validación manual para GET
            $validated = $this->validarDatosManual($request->all());

            // Obtener asistencias según los filtros
            $asistencias = $this->obtenerAsistenciasFiltradas($validated);
            
            // Obtener gestión
            $gestion = GestionAcademica::findOrFail($validated['id_gestion']);

            // Procesar datos según el tipo de reporte
            $datosProcesados = $this->procesarDatosReporte($asistencias, $validated['tipo_reporte']);

            // Calcular estadísticas
            $estadisticas = $this->calcularEstadisticasAsistencia($asistencias, $validated);

            // Registrar en bitácora
            $detallesBitacora = $this->generarDetallesBitacora($validated, $estadisticas);
            BitacoraController::registrar('Vista Previa', 'Reporte Asistencia', null, auth()->id(), null, $detallesBitacora);

            // Retornar vista con resultados
            return view('reportes.asistencia.resultados', [
                'datos' => $datosProcesados,
                'estadisticas' => $estadisticas,
                'filtros' => $validated,
                'gestion' => $gestion,
                'tipo_reporte' => $validated['tipo_reporte']
            ])->with('success', 'Vista previa del reporte generada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al generar vista previa de asistencia: ' . $e->getMessage());
            
            return redirect()->route('admin.reportes.asistencia.index')
                ->with('error', 'Error al generar vista previa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validación manual para solicitudes GET
     */
    private function validarDatosManual($datos)
    {
        $errors = [];

        // Validar tipo_reporte
        if (empty($datos['tipo_reporte']) || !in_array($datos['tipo_reporte'], ['docente', 'grupo'])) {
            $errors[] = 'El tipo de reporte es requerido y debe ser "docente" o "grupo"';
        }

        // Validar gestión
        if (empty($datos['id_gestion']) || !GestionAcademica::find($datos['id_gestion'])) {
            $errors[] = 'La gestión académica es requerida y debe existir';
        }

        // Validar fechas
        if (empty($datos['fecha_inicio']) || !strtotime($datos['fecha_inicio'])) {
            $errors[] = 'La fecha de inicio es requerida y debe ser una fecha válida';
        }

        if (empty($datos['fecha_fin']) || !strtotime($datos['fecha_fin'])) {
            $errors[] = 'La fecha de fin es requerida y debe ser una fecha válida';
        }

        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin']) && 
            strtotime($datos['fecha_fin']) < strtotime($datos['fecha_inicio'])) {
            $errors[] = 'La fecha fin debe ser mayor o igual a la fecha inicio';
        }

        // Validar filtros específicos
        if ($datos['tipo_reporte'] == 'docente' && empty($datos['id_docente'])) {
            $errors[] = 'El docente es requerido para reportes por docente';
        }

        if ($datos['tipo_reporte'] == 'grupo' && empty($datos['id_grupo'])) {
            $errors[] = 'El grupo es requerido para reportes por grupo';
        }

        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        return $datos;
    }

    // Los demás métodos permanecen igual...
    private function obtenerAsistenciasFiltradas($filtros)
    {
        $query = Asistencia::with([
            'grupoMateriaHorario.docente.user',
            'grupoMateriaHorario.grupoMateria.grupo',
            'grupoMateriaHorario.grupoMateria.materia',
            'grupoMateriaHorario.horario',
            'grupoMateriaHorario.aula'
        ])
        ->whereHas('grupoMateriaHorario.grupoMateria', function($q) use ($filtros) {
            $q->where('id_gestion', $filtros['id_gestion']);
        })
        ->whereBetween('fecha', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);

        // Aplicar filtros específicos según tipo de reporte
        if ($filtros['tipo_reporte'] == 'docente' && !empty($filtros['id_docente'])) {
            $query->whereHas('grupoMateriaHorario', function($q) use ($filtros) {
                $q->where('id_docente', $filtros['id_docente']);
            });
        }

        if ($filtros['tipo_reporte'] == 'grupo' && !empty($filtros['id_grupo'])) {
            $query->whereHas('grupoMateriaHorario.grupoMateria', function($q) use ($filtros) {
                $q->where('id_grupo', $filtros['id_grupo']);
            });
        }

        // Filtro por materia
        if (!empty($filtros['id_materia'])) {
            $query->whereHas('grupoMateriaHorario.grupoMateria', function($q) use ($filtros) {
                $q->where('sigla_materia', $filtros['id_materia']);
            });
        }

        // Filtro por estado de asistencia
        if (!empty($filtros['estado_asistencia']) && $filtros['estado_asistencia'] != 'todos') {
            $query->where('estado', $filtros['estado_asistencia']);
        }

        // Ordenar por fecha y hora
        $query->orderBy('fecha', 'desc')
              ->orderBy('hora_registro', 'desc');

        return $query->get();
    }

    private function procesarDatosReporte($asistencias, $tipoReporte)
    {
        switch ($tipoReporte) {
            case 'docente':
                return $this->procesarReportePorDocente($asistencias);
            
            case 'grupo':
                return $this->procesarReportePorGrupo($asistencias);
            
            default:
                return $this->procesarReportePorDocente($asistencias);
        }
    }

    private function procesarReportePorDocente($asistencias)
    {
        $docentes = $asistencias->groupBy('grupoMateriaHorario.id_docente')->map(function($asistenciasDocente, $docenteId) {
            $docente = $asistenciasDocente->first()->grupoMateriaHorario->docente;
            
            return [
                'docente' => $docente,
                'asistencias' => $asistenciasDocente->groupBy(function($asistencia) {
                    return $asistencia->fecha;
                })->map(function($asistenciasDia) {
                    return $asistenciasDia->sortBy('hora_registro')->values();
                })
            ];
        });
        
        return [
            'tipo' => 'docente',
            'docentes' => $docentes
        ];
    }

    private function procesarReportePorGrupo($asistencias)
    {
        $grupos = $asistencias->groupBy('grupoMateriaHorario.grupoMateria.id_grupo')->map(function($asistenciasGrupo, $grupoId) {
            $grupo = $asistenciasGrupo->first()->grupoMateriaHorario->grupoMateria->grupo;
            
            return [
                'grupo' => $grupo,
                'asistencias' => $asistenciasGrupo->groupBy(function($asistencia) {
                    return $asistencia->fecha;
                })->map(function($asistenciasDia) {
                    return $asistenciasDia->sortBy('hora_registro')->values();
                })
            ];
        });
        
        return [
            'tipo' => 'grupo',
            'grupos' => $grupos
        ];
    }

    private function calcularEstadisticasAsistencia($asistencias, $filtros)
    {
        $totalAsistencias = $asistencias->count();
        $presentes = $asistencias->where('estado', 'presente')->count();
        $tardanzas = $asistencias->where('estado', 'tardanza')->count();
        $ausentes = 0;

        $porcentajePresentes = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 2) : 0;
        $porcentajeTardanzas = $totalAsistencias > 0 ? round(($tardanzas / $totalAsistencias) * 100, 2) : 0;

        // Distribución por días de la semana
        $distribucionDias = $asistencias->groupBy(function($asistencia) {
            return Carbon::parse($asistencia->fecha)->dayName;
        })->map->count();

        return [
            'total_asistencias' => $totalAsistencias,
            'presentes' => $presentes,
            'tardanzas' => $tardanzas,
            'ausentes' => $ausentes,
            'porcentaje_presentes' => $porcentajePresentes,
            'porcentaje_tardanzas' => $porcentajeTardanzas,
            'distribucion_dias' => $distribucionDias,
            'periodo' => [
                'inicio' => $filtros['fecha_inicio'],
                'fin' => $filtros['fecha_fin'],
                'dias_totales' => Carbon::parse($filtros['fecha_inicio'])->diffInDays($filtros['fecha_fin']) + 1
            ]
        ];
    }

    private function generarDetallesBitacora($filtros, $estadisticas)
    {
        $detalles = "Reporte Asistencia - Tipo: " . ucfirst($filtros['tipo_reporte']) . " - ";
        $detalles .= "Periodo: {$filtros['fecha_inicio']} a {$filtros['fecha_fin']} - ";
        $detalles .= "Registros: {$estadisticas['total_asistencias']} - ";
        $detalles .= "Presentes: {$estadisticas['presentes']} ({$estadisticas['porcentaje_presentes']}%) - ";
        $detalles .= "Tardanzas: {$estadisticas['tardanzas']} ({$estadisticas['porcentaje_tardanzas']}%)";
        
        $filtrosAplicados = [];
        if (!empty($filtros['id_docente'])) $filtrosAplicados[] = "Docente específico";
        if (!empty($filtros['id_grupo'])) $filtrosAplicados[] = "Grupo específico";
        if (!empty($filtros['id_materia'])) $filtrosAplicados[] = "Materia específica";
        if ($filtros['estado_asistencia'] != 'todos') $filtrosAplicados[] = "Estado: " . ucfirst($filtros['estado_asistencia']);
        
        if (!empty($filtrosAplicados)) {
            $detalles .= " - Filtros: " . implode(', ', $filtrosAplicados);
        }
        
        return $detalles;
    }

    private function exportarPDF($datos, $estadisticas, $filtros, $gestion)
{
    try {
        BitacoraController::registrar('Exportación PDF', 'Reporte Asistencia', null, auth()->id(), null, "PDF exportado - Tipo: " . ucfirst($filtros['tipo_reporte']));

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('reportes.asistencia.pdf', [
            'datos' => $datos,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'gestion' => $gestion,
            'fechaGeneracion' => Carbon::now()->format('d/m/Y H:i:s'),
            'usuario' => auth()->user()->name
        ])->setPaper('a4', 'portrait');

        $nombreArchivo = 'reporte_asistencia_' . $filtros['tipo_reporte'] . '_' . Carbon::now()->format('Y_m_d_His') . '.pdf';
        
        return $pdf->download($nombreArchivo);
        
    } catch (\Exception $e) {
        Log::error('Error al generar PDF: ' . $e->getMessage());
        BitacoraController::registrar('Error PDF', 'Reporte Asistencia', null, auth()->id(), null, 'Error al generar PDF: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
    }
}
    private function exportarCSV($asistencias, $filtros, $gestion, $estadisticas)
    {
        try {
            BitacoraController::registrar('Exportación CSV', 'Reporte Asistencia', null, auth()->id(), null, "CSV exportado - Tipo: " . ucfirst($filtros['tipo_reporte']) . " - Registros: " . $asistencias->count());

            $nombreArchivo = 'reporte_asistencia_' . $filtros['tipo_reporte'] . '_' . Carbon::now()->format('Y_m_d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
            ];

            $callback = function() use ($asistencias, $gestion, $filtros, $estadisticas) {
                $file = fopen('php://output', 'w');
                fwrite($file, "\xEF\xBB\xBF");
                
                fputcsv($file, ['REPORTE DE ASISTENCIA DOCENTE - SISTEMA ACADÉMICO']);
                fputcsv($file, []);
                
                fputcsv($file, ['INFORMACIÓN DEL REPORTE', '']);
                fputcsv($file, ['Tipo de Reporte', ucfirst($filtros['tipo_reporte'])]);
                fputcsv($file, ['Período', $filtros['fecha_inicio'] . ' a ' . $filtros['fecha_fin']]);
                fputcsv($file, ['Gestión Académica', $gestion->gestion]);
                fputcsv($file, ['Fecha de Generación', Carbon::now()->format('d/m/Y H:i:s')]);
                fputcsv($file, ['Generado por', auth()->user()->name]);
                fputcsv($file, []);
                fputcsv($file, []);
                
                fputcsv($file, [
                    'FECHA',
                    'HORA REGISTRO',
                    'DOCENTE',
                    'CÓDIGO DOCENTE',
                    'MATERIA',
                    'GRUPO',
                    'AULA',
                    'HORARIO',
                    'ESTADO',
                    'MÉTODO REGISTRO'
                ]);

                foreach ($asistencias as $asistencia) {
                    fputcsv($file, [
                        $asistencia->fecha,
                        $asistencia->hora_registro,
                        $this->limpiarTexto($asistencia->grupoMateriaHorario->docente->user->name ?? 'N/A'),
                        $asistencia->grupoMateriaHorario->docente->codigo ?? 'N/A',
                        $this->limpiarTexto($asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? 'N/A'),
                        $this->limpiarTexto($asistencia->grupoMateriaHorario->grupoMateria->grupo->nombre ?? 'N/A'),
                        $this->limpiarTexto($asistencia->grupoMateriaHorario->aula->nombre ?? 'N/A'),
                        $asistencia->grupoMateriaHorario->horario->hora_inicio . ' - ' . $asistencia->grupoMateriaHorario->horario->hora_fin,
                        ucfirst($asistencia->estado),
                        ucfirst($asistencia->metodo)
                    ]);
                }
                
                fputcsv($file, []);
                
                fputcsv($file, ['RESUMEN ESTADÍSTICO', '']);
                fputcsv($file, ['Total de registros', $estadisticas['total_asistencias']]);
                fputcsv($file, ['Presentes', $estadisticas['presentes'] . ' (' . $estadisticas['porcentaje_presentes'] . '%)']);
                fputcsv($file, ['Tardanzas', $estadisticas['tardanzas'] . ' (' . $estadisticas['porcentaje_tardanzas'] . '%)']);
                fputcsv($file, ['Ausentes', $estadisticas['ausentes']]);
                fputcsv($file, ['Días del período', $estadisticas['periodo']['dias_totales']]);
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error al generar CSV: ' . $e->getMessage());
            BitacoraController::registrar('Error CSV', 'Reporte Asistencia', null, auth()->id(), null, 'Error al generar CSV: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar CSV: ' . $e->getMessage());
        }
    }

    private function exportarXLSX($asistencias, $filtros, $gestion, $estadisticas)
    {
        try {
            BitacoraController::registrar('Exportación XLSX', 'Reporte Asistencia', null, auth()->id(), null, "XLSX exportado - Tipo: " . ucfirst($filtros['tipo_reporte']) . " - Registros: " . $asistencias->count());

            $nombreArchivo = 'reporte_asistencia_' . $filtros['tipo_reporte'] . '_' . Carbon::now()->format('Y_m_d_His') . '.xlsx';

            $datos = $this->prepararDatosParaXLSX($asistencias, $filtros, $gestion, $estadisticas);

            if (class_exists('Shuchkin\SimpleXLSXGen')) {
                $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($datos);
                $xlsx->downloadAs($nombreArchivo);
            } else {
                return $this->generarXLSXFallback($datos, $nombreArchivo);
            }
            
        } catch (\Exception $e) {
            Log::error('Error al generar XLSX: ' . $e->getMessage());
            BitacoraController::registrar('Error XLSX', 'Reporte Asistencia', null, auth()->id(), null, 'Error al generar XLSX: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar XLSX: ' . $e->getMessage());
        }
    }

    private function prepararDatosParaXLSX($asistencias, $filtros, $gestion, $estadisticas)
    {
        $fechaGeneracion = Carbon::now()->format('d/m/Y H:i:s');
        $usuario = auth()->user()->name;

        $datos = [];

        $datos[] = ['REPORTE DE ASISTENCIA DOCENTE - SISTEMA ACADÉMICO'];
        $datos[] = [];
        $datos[] = ['INFORMACIÓN DEL REPORTE', ''];
        $datos[] = ['Tipo de Reporte', ucfirst($filtros['tipo_reporte'])];
        $datos[] = ['Período', $filtros['fecha_inicio'] . ' a ' . $filtros['fecha_fin']];
        $datos[] = ['Gestión Académica', $gestion->gestion];
        $datos[] = ['Fecha de Generación', $fechaGeneracion];
        $datos[] = ['Generado por', $usuario];
        $datos[] = [];
        $datos[] = [];

        $datos[] = [
            'FECHA',
            'HORA REGISTRO',
            'DOCENTE',
            'CÓDIGO DOCENTE',
            'MATERIA',
            'GRUPO',
            'AULA',
            'HORARIO',
            'ESTADO',
            'MÉTODO REGISTRO'
        ];

        foreach ($asistencias as $asistencia) {
            $datos[] = [
                $asistencia->fecha,
                $asistencia->hora_registro,
                $this->limpiarTexto($asistencia->grupoMateriaHorario->docente->user->name ?? 'N/A'),
                $asistencia->grupoMateriaHorario->docente->codigo ?? 'N/A',
                $this->limpiarTexto($asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? 'N/A'),
                $this->limpiarTexto($asistencia->grupoMateriaHorario->grupoMateria->grupo->nombre ?? 'N/A'),
                $this->limpiarTexto($asistencia->grupoMateriaHorario->aula->nombre ?? 'N/A'),
                $asistencia->grupoMateriaHorario->horario->hora_inicio . ' - ' . $asistencia->grupoMateriaHorario->horario->hora_fin,
                ucfirst($asistencia->estado),
                ucfirst($asistencia->metodo)
            ];
        }

        $datos[] = [];
        $datos[] = ['RESUMEN ESTADÍSTICO', ''];
        $datos[] = ['Total de registros', $estadisticas['total_asistencias']];
        $datos[] = ['Presentes', $estadisticas['presentes'] . ' (' . $estadisticas['porcentaje_presentes'] . '%)'];
        $datos[] = ['Tardanzas', $estadisticas['tardanzas'] . ' (' . $estadisticas['porcentaje_tardanzas'] . '%)'];
        $datos[] = ['Ausentes', $estadisticas['ausentes']];
        $datos[] = ['Días del período', $estadisticas['periodo']['dias_totales']];

        return $datos;
    }

    private function generarXLSXFallback($datos, $nombreArchivo)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ];

        $callback = function() use ($datos) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            
            foreach ($datos as $fila) {
                if (is_array($fila)) {
                    fwrite($file, implode("\t", $fila) . "\n");
                } else {
                    fwrite($file, $fila . "\n");
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function limpiarTexto($texto)
    {
        if (!$texto) return 'N/A';
        $texto = str_replace(["\r", "\n", "\t", '"'], ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);
        return trim($texto);
    }
}