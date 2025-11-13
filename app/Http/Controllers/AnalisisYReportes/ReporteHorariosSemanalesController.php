<?php

namespace App\Http\Controllers\AnalisisYReportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupoMateriaHorario;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Aula;
use App\Models\GestionAcademica;
use App\Models\Horario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Administracion\BitacoraController;

class ReporteHorariosSemanalesController extends Controller
{
    /**
     * Mostrar formulario principal de reportes
     */
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para generar reportes.');
        }

        // Registrar en bitácora
        BitacoraController::registrar('Acceso', 'Reporte Horarios Semanales', null, auth()->id(), null, 'Accedió al formulario de reportes de horarios semanales');

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
        $aulas = Aula::orderBy('nombre')->get();
        $gestiones = GestionAcademica::orderBy('id', 'desc')->get();
        $gestionActiva = GestionAcademica::where('estado', 'curso')->first();

        // Generar semanas del año actual
        $semanas = $this->generarSemanasAnio();

        return view('reportes.horarios-semanales.index', compact(
            'docentes', 'materias', 'grupos', 'aulas', 'gestiones', 'gestionActiva', 'semanas'
        ));
    }

    /**
     * Generar semanas del año
     */
    private function generarSemanasAnio()
    {
        $semanas = [];
        $anioActual = Carbon::now()->year;
        
        for ($i = 1; $i <= 52; $i++) {
            $fechaInicio = Carbon::now()->setISODate($anioActual, $i)->startOfWeek();
            $fechaFin = Carbon::now()->setISODate($anioActual, $i)->endOfWeek();
            
            $semanas[$i] = [
                'numero' => $i,
                'rango' => $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y'),
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ];
        }
        
        return $semanas;
    }

    /**
     * Generar reporte principal
     */
    public function generarReporte(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para generar reportes.');
        }

        try {
            $validated = $request->validate([
                'tipo_vista' => 'required|in:docente,aula,grupo',
                'id_gestion' => 'required|exists:gestion_academica,id',
                'semana' => 'required|integer|min:1|max:52',
                'id_docente' => 'nullable',
                'id_aula' => 'nullable',
                'id_grupo' => 'nullable',
                'rango_horas' => 'nullable|in:manana,tarde,noche,todo',
                'exportar_pdf' => 'nullable|boolean',
                'exportar_csv' => 'nullable|boolean',
                'exportar_xlsx' => 'nullable|boolean'
            ]);

            // Obtener información de la semana
            $infoSemana = $this->generarSemanasAnio()[$validated['semana']];
            
            // Obtener horarios según los filtros
            $horarios = $this->obtenerHorariosFiltrados($validated);
            
            // Obtener gestión
            $gestion = GestionAcademica::findOrFail($validated['id_gestion']);

            // Procesar datos según el tipo de vista
            $datosProcesados = $this->procesarDatosVista($horarios, $validated['tipo_vista']);

            // Calcular estadísticas
            $estadisticas = $this->calcularEstadisticas($horarios, $infoSemana);

            // Registrar en bitácora
            $detallesBitacora = $this->generarDetallesBitacora($validated, $infoSemana, $estadisticas);
            BitacoraController::registrar('Generación', 'Reporte Horarios Semanales', null, auth()->id(), null, $detallesBitacora);

            // Exportar PDF si se solicita
            if ($request->has('exportar_pdf')) {
                return $this->exportarPDF($datosProcesados, $estadisticas, $validated, $gestion, $infoSemana);
            }

            // Exportar CSV si se solicita
            if ($request->has('exportar_csv')) {
                return $this->exportarCSV($horarios, $validated, $gestion, $infoSemana);
            }

            // Exportar XLSX si se solicita
            if ($request->has('exportar_xlsx')) {
                return $this->exportarXLSX($horarios, $validated, $gestion, $infoSemana);
            }

            // Retornar vista con resultados
            return view('reportes.horarios-semanales.resultados', [
                'datos' => $datosProcesados,
                'estadisticas' => $estadisticas,
                'filtros' => $validated,
                'gestion' => $gestion,
                'infoSemana' => $infoSemana,
                'tipo_vista' => $validated['tipo_vista']
            ])->with('success', 'Reporte generado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al generar reporte: ' . $e->getMessage());
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Reporte Horarios Semanales', null, auth()->id(), null, 'Error al generar reporte: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Generar detalles para bitácora
     */
    private function generarDetallesBitacora($filtros, $infoSemana, $estadisticas)
    {
        $detalles = "Reporte Semana {$infoSemana['numero']} ({$infoSemana['rango']}) - ";
        $detalles .= "Vista: " . ucfirst($filtros['tipo_vista']) . " - ";
        $detalles .= "Horarios: {$estadisticas['total_horarios']} - ";
        $detalles .= "Docentes: {$estadisticas['docentes_activos']} - ";
        $detalles .= "Aulas: {$estadisticas['aulas_utilizadas']} - ";
        $detalles .= "Materias: {$estadisticas['materias_impartidas']}";
        
        // Agregar filtros aplicados
        $filtrosAplicados = [];
        if (!empty($filtros['id_docente'])) $filtrosAplicados[] = "Docente específico";
        if (!empty($filtros['id_aula'])) $filtrosAplicados[] = "Aula específica";
        if (!empty($filtros['id_grupo'])) $filtrosAplicados[] = "Grupo específico";
        if ($filtros['rango_horas'] != 'todo') $filtrosAplicados[] = "Horario: " . ucfirst($filtros['rango_horas']);
        
        if (!empty($filtrosAplicados)) {
            $detalles .= " - Filtros: " . implode(', ', $filtrosAplicados);
        }
        
        return $detalles;
    }

    /**
     * Obtener horarios filtrados
     */
    private function obtenerHorariosFiltrados($filtros)
    {
        $query = GrupoMateriaHorario::with([
            'horario',
            'grupoMateria.grupo',
            'grupoMateria.materia',
            'docente.user',
            'aula'
        ])
        ->whereHas('grupoMateria', function($q) use ($filtros) {
            $q->where('id_gestion', $filtros['id_gestion']);
        })
        ->where('estado_aula', 'ocupado');

        // Aplicar filtros específicos
        if (!empty($filtros['id_docente'])) {
            $query->where('id_docente', $filtros['id_docente']);
        }

        if (!empty($filtros['id_aula'])) {
            $query->where('id_aula', $filtros['id_aula']);
        }

        if (!empty($filtros['id_grupo'])) {
            $query->whereHas('grupoMateria', function($q) use ($filtros) {
                $q->where('id_grupo', $filtros['id_grupo']);
            });
        }

        // Filtrar por rango de horas
        if (!empty($filtros['rango_horas']) && $filtros['rango_horas'] != 'todo') {
            $query->whereHas('horario', function($q) use ($filtros) {
                switch ($filtros['rango_horas']) {
                    case 'manana':
                        $q->whereBetween('hora_inicio', ['06:00', '12:00']);
                        break;
                    case 'tarde':
                        $q->whereBetween('hora_inicio', ['12:00', '18:00']);
                        break;
                    case 'noche':
                        $q->whereBetween('hora_inicio', ['18:00', '22:00']);
                        break;
                }
            });
        }

        // Ordenar por día y hora
        $query->join('horario', 'grupo_materia_horario.id_horario', '=', 'horario.id')
            ->orderByRaw("
                CASE 
                    WHEN horario.dia = 'LUN' THEN 1
                    WHEN horario.dia = 'MAR' THEN 2
                    WHEN horario.dia = 'MIE' THEN 3
                    WHEN horario.dia = 'JUE' THEN 4
                    WHEN horario.dia = 'VIE' THEN 5
                    WHEN horario.dia = 'SAB' THEN 6
                END
            ")
            ->orderBy('horario.hora_inicio')
            ->select('grupo_materia_horario.*');

        return $query->get();
    }

    /**
     * Procesar datos según el tipo de vista
     */
    private function procesarDatosVista($horarios, $tipoVista)
    {
        switch ($tipoVista) {
            case 'docente':
                return $this->procesarVistaPorDocente($horarios);
            
            case 'aula':
                return $this->procesarVistaPorAula($horarios);
            
            case 'grupo':
                return $this->procesarVistaPorGrupo($horarios);
            
            default:
                return $this->procesarVistaPorDocente($horarios);
        }
    }

    /**
     * Vista por Docente
     */
    private function procesarVistaPorDocente($horarios)
    {
        $docentes = $horarios->groupBy('id_docente')->map(function($horariosDocente, $docenteId) {
            $docente = $horariosDocente->first()->docente;
            
            return [
                'docente' => $docente,
                'horarios' => $horariosDocente->groupBy(function($horario) {
                    return $horario->horario->dia;
                })->map(function($horariosDia) {
                    return $horariosDia->sortBy('horario.hora_inicio')->values();
                })
            ];
        });
        
        return [
            'tipo' => 'docente',
            'docentes' => $docentes
        ];
    }

    /**
     * Vista por Aula
     */
    private function procesarVistaPorAula($horarios)
    {
        $aulas = $horarios->groupBy('id_aula')->map(function($horariosAula, $aulaId) {
            $aula = $horariosAula->first()->aula;
            
            return [
                'aula' => $aula,
                'horarios' => $horariosAula->groupBy(function($horario) {
                    return $horario->horario->dia;
                })->map(function($horariosDia) {
                    return $horariosDia->sortBy('horario.hora_inicio')->values();
                })
            ];
        });
        
        return [
            'tipo' => 'aula',
            'aulas' => $aulas
        ];
    }

    /**
     * Vista por Grupo
     */
    private function procesarVistaPorGrupo($horarios)
    {
        $grupos = $horarios->groupBy('grupoMateria.id_grupo')->map(function($horariosGrupo, $grupoId) {
            $grupo = $horariosGrupo->first()->grupoMateria->grupo;
            
            return [
                'grupo' => $grupo,
                'horarios' => $horariosGrupo->groupBy(function($horario) {
                    return $horario->horario->dia;
                })->map(function($horariosDia) {
                    return $horariosDia->sortBy('horario.hora_inicio')->values();
                })
            ];
        });
        
        return [
            'tipo' => 'grupo',
            'grupos' => $grupos
        ];
    }

    /**
     * Calcular estadísticas
     */
    private function calcularEstadisticas($horarios, $infoSemana)
    {
        $totalHoras = $horarios->count();
        $docentesActivos = $horarios->unique('id_docente')->count();
        $aulasUtilizadas = $horarios->unique('id_aula')->count();
        $materiasImpartidas = $horarios->unique('grupoMateria.sigla_materia')->count();
        
        // Distribución por días
        $distribucionDias = $horarios->groupBy('horario.dia')->map->count();
        
        return [
            'total_horarios' => $totalHoras,
            'docentes_activos' => $docentesActivos,
            'aulas_utilizadas' => $aulasUtilizadas,
            'materias_impartidas' => $materiasImpartidas,
            'distribucion_dias' => $distribucionDias,
            'porcentaje_ocupacion' => $totalHoras > 0 ? round(($totalHoras / ($aulasUtilizadas * 6 * 8)) * 100, 2) : 0
        ];
    }

    /**
     * Exportar a PDF
     */
    private function exportarPDF($datos, $estadisticas, $filtros, $gestion, $infoSemana)
    {
        try {
            // Registrar en bitácora
            BitacoraController::registrar('Exportación PDF', 'Reporte Horarios Semanales', null, auth()->id(), null, "PDF exportado - Semana {$infoSemana['numero']} - Vista: " . ucfirst($filtros['tipo_vista']));

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('reportes.horarios-semanales.pdf', [
                'datos' => $datos,
                'estadisticas' => $estadisticas,
                'filtros' => $filtros,
                'gestion' => $gestion,
                'infoSemana' => $infoSemana,
                'fechaGeneracion' => Carbon::now()->format('d/m/Y H:i:s'),
                'usuario' => auth()->user()->name
            ])->setPaper('a4', 'portrait');

            $nombreArchivo = 'reporte_horarios_' . $filtros['tipo_vista'] . '_semana_' . $infoSemana['numero'] . '_' . Carbon::now()->format('Y_m_d_His') . '.pdf';
            
            return $pdf->download($nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error al generar PDF: ' . $e->getMessage());
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error PDF', 'Reporte Horarios Semanales', null, auth()->id(), null, 'Error al generar PDF: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Exportar a CSV
     */
    private function exportarCSV($horarios, $filtros, $gestion, $infoSemana)
    {
        try {
            // Registrar en bitácora
            BitacoraController::registrar('Exportación CSV', 'Reporte Horarios Semanales', null, auth()->id(), null, "CSV exportado - Semana {$infoSemana['numero']} - Vista: " . ucfirst($filtros['tipo_vista']) . " - Registros: " . $horarios->count());

            $nombreArchivo = 'reporte_horarios_' . $filtros['tipo_vista'] . '_semana_' . $infoSemana['numero'] . '_' . Carbon::now()->format('Y_m_d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
            ];

            $callback = function() use ($horarios, $gestion, $infoSemana, $filtros) {
                $file = fopen('php://output', 'w');
                fwrite($file, "\xEF\xBB\xBF"); // BOM para Excel
                
                // ENCABEZADO INFORMATIVO
                fputcsv($file, ['REPORTE DE HORARIOS SEMANALES - SISTEMA DOCENTE']);
                fputcsv($file, []); // Línea vacía
                
                // METADATOS DEL REPORTE
                fputcsv($file, ['INFORMACIÓN DEL REPORTE', '']);
                fputcsv($file, ['Semana', 'Semana ' . $infoSemana['numero']]);
                fputcsv($file, ['Período', $infoSemana['rango']]);
                fputcsv($file, ['Gestión Académica', $gestion->gestion]);
                fputcsv($file, ['Tipo de Vista', ucfirst($filtros['tipo_vista'])]);
                fputcsv($file, ['Fecha de Generación', Carbon::now()->format('d/m/Y H:i:s')]);
                fputcsv($file, ['Generado por', auth()->user()->name]);
                fputcsv($file, []); // Línea vacía
                fputcsv($file, []); // Línea vacía
                
                // ENCABEZADOS DE COLUMNAS PRINCIPALES
                fputcsv($file, [
                    'SEMANA',
                    'DÍA',
                    'HORA INICIO', 
                    'HORA FIN',
                    'CÓDIGO MATERIA',
                    'NOMBRE MATERIA',
                    'GRUPO',
                    'CÓDIGO DOCENTE',
                    'NOMBRE DOCENTE',
                    'AULA',
                    'TIPO AULA',
                    'CAPACIDAD',
                    'ESTADO'
                ]);

                // DATOS PRINCIPALES
                foreach ($horarios as $horario) {
                    fputcsv($file, [
                        'Semana ' . $infoSemana['numero'],
                        $this->convertirDia($horario->horario->dia),
                        $this->formatearHora($horario->horario->hora_inicio),
                        $this->formatearHora($horario->horario->hora_fin),
                        $this->limpiarTexto($horario->grupoMateria->materia->sigla ?? 'N/A'),
                        $this->limpiarTexto($horario->grupoMateria->materia->nombre ?? 'N/A'),
                        $this->limpiarTexto($horario->grupoMateria->grupo->nombre ?? 'N/A'),
                        $horario->docente->codigo ?? 'N/A',
                        $this->limpiarTexto($horario->docente->user->name ?? 'N/A'),
                        $this->limpiarTexto($horario->aula->nombre ?? 'N/A'),
                        $this->limpiarTexto($horario->aula->tipo ?? 'N/A'),
                        $horario->aula->capacidad ?? 'N/A',
                        $horario->estado_aula
                    ]);
                }
                
                // LÍNEAS DE SEPARACIÓN
                fputcsv($file, []); // Línea vacía
                
                // RESUMEN ESTADÍSTICO
                fputcsv($file, ['RESUMEN ESTADÍSTICO', '']);
                fputcsv($file, ['Total de horarios registrados', count($horarios)]);
                fputcsv($file, ['Docentes involucrados', $horarios->unique('id_docente')->count()]);
                fputcsv($file, ['Aulas utilizadas', $horarios->unique('id_aula')->count()]);
                fputcsv($file, ['Materias impartidas', $horarios->unique('grupoMateria.sigla_materia')->count()]);
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('Error al generar CSV: ' . $e->getMessage());
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error CSV', 'Reporte Horarios Semanales', null, auth()->id(), null, 'Error al generar CSV: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error al generar CSV: ' . $e->getMessage());
        }
    }

    /**
     * Exportar a XLSX (formato Excel verdadero)
     */
    private function exportarXLSX($horarios, $filtros, $gestion, $infoSemana)
    {
        try {
            // Registrar en bitácora
            BitacoraController::registrar('Exportación XLSX', 'Reporte Horarios Semanales', null, auth()->id(), null, "XLSX exportado - Semana {$infoSemana['numero']} - Vista: " . ucfirst($filtros['tipo_vista']) . " - Registros: " . $horarios->count());

            $nombreArchivo = 'reporte_horarios_' . $filtros['tipo_vista'] . '_semana_' . $infoSemana['numero'] . '_' . Carbon::now()->format('Y_m_d_His') . '.xlsx';

            // Preparar datos para Excel
            $datos = $this->prepararDatosParaXLSX($horarios, $filtros, $gestion, $infoSemana);

            // Verificar si la librería SimpleXLSXGen está disponible
            if (class_exists('Shuchkin\SimpleXLSXGen')) {
                // Usar SimpleXLSXGen si está instalado
                $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($datos);
                $xlsx->downloadAs($nombreArchivo);
            } else {
                // Fallback: generar archivo TSV con extensión XLSX (más compatible)
                return $this->generarXLSXFallback($datos, $nombreArchivo);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error al generar XLSX: ' . $e->getMessage());
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error XLSX', 'Reporte Horarios Semanales', null, auth()->id(), null, 'Error al generar XLSX: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error al generar XLSX: ' . $e->getMessage());
        }
    }

    /**
     * Preparar datos para XLSX
     */
    private function prepararDatosParaXLSX($horarios, $filtros, $gestion, $infoSemana)
    {
        $fechaGeneracion = Carbon::now()->format('d/m/Y H:i:s');
        $usuario = auth()->user()->name;

        $datos = [];

        // Información del reporte
        $datos[] = ['REPORTE DE HORARIOS SEMANALES - SISTEMA DOCENTE'];
        $datos[] = []; // Línea vacía
        $datos[] = ['INFORMACIÓN DEL REPORTE', ''];
        $datos[] = ['Semana', 'Semana ' . $infoSemana['numero']];
        $datos[] = ['Período', $infoSemana['rango']];
        $datos[] = ['Gestión Académica', $gestion->gestion];
        $datos[] = ['Tipo de Vista', ucfirst($filtros['tipo_vista'])];
        $datos[] = ['Fecha de Generación', $fechaGeneracion];
        $datos[] = ['Generado por', $usuario];
        $datos[] = []; // Línea vacía
        $datos[] = []; // Línea vacía

        // Encabezados de columnas
        $datos[] = [
            'SEMANA',
            'DÍA',
            'HORA INICIO', 
            'HORA FIN',
            'CÓDIGO MATERIA',
            'NOMBRE MATERIA',
            'GRUPO',
            'CÓDIGO DOCENTE',
            'NOMBRE DOCENTE',
            'AULA',
            'TIPO AULA',
            'CAPACIDAD',
            'ESTADO'
        ];

        // Datos principales
        foreach ($horarios as $horario) {
            $datos[] = [
                'Semana ' . $infoSemana['numero'],
                $this->convertirDia($horario->horario->dia),
                $this->formatearHora($horario->horario->hora_inicio),
                $this->formatearHora($horario->horario->hora_fin),
                $this->limpiarTexto($horario->grupoMateria->materia->sigla ?? 'N/A'),
                $this->limpiarTexto($horario->grupoMateria->materia->nombre ?? 'N/A'),
                $this->limpiarTexto($horario->grupoMateria->grupo->nombre ?? 'N/A'),
                $horario->docente->codigo ?? 'N/A',
                $this->limpiarTexto($horario->docente->user->name ?? 'N/A'),
                $this->limpiarTexto($horario->aula->nombre ?? 'N/A'),
                $this->limpiarTexto($horario->aula->tipo ?? 'N/A'),
                $horario->aula->capacidad ?? 'N/A',
                $horario->estado_aula
            ];
        }

        // Resumen estadístico
        $datos[] = []; // Línea vacía
        $datos[] = ['RESUMEN ESTADÍSTICO', ''];
        $datos[] = ['Total de horarios registrados', count($horarios)];
        $datos[] = ['Docentes involucrados', $horarios->unique('id_docente')->count()];
        $datos[] = ['Aulas utilizadas', $horarios->unique('id_aula')->count()];
        $datos[] = ['Materias impartidas', $horarios->unique('grupoMateria.sigla_materia')->count()];

        return $datos;
    }

    /**
     * Generar XLSX fallback (TSV con extensión XLSX)
     */
    private function generarXLSXFallback($datos, $nombreArchivo)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ];

        $callback = function() use ($datos) {
            $file = fopen('php://output', 'w');
            
            // Escribir BOM para UTF-8
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

    /**
     * Formatear hora para mejor visualización
     */
    private function formatearHora($hora)
    {
        if (!$hora) return 'N/A';
        return date('h:i A', strtotime($hora));
    }

    /**
     * Limpiar texto para exportación
     */
    private function limpiarTexto($texto)
    {
        if (!$texto) return 'N/A';
        $texto = str_replace(["\r", "\n", "\t", '"'], ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);
        return trim($texto);
    }

    /**
     * Convertir código de día a texto
     */
    private function convertirDia($dia)
    {
        $dias = [
            'LUN' => 'Lunes',
            'MAR' => 'Martes',
            'MIE' => 'Miércoles', 
            'JUE' => 'Jueves',
            'VIE' => 'Viernes',
            'SAB' => 'Sábado'
        ];

        return $dias[$dia] ?? $dia;
    }
}