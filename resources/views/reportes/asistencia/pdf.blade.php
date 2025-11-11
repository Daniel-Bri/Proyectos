<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Asistencia - {{ ucfirst($filtros['tipo_reporte']) }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 10px; 
            line-height: 1.2;
            margin: 0;
            padding: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 12px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 6px; 
        }
        .header h1 { 
            color: #2c3e50; 
            margin: 0; 
            font-size: 16px; 
        }
        .header h2 {
            color: #7f8c8d;
            margin: 2px 0;
            font-size: 12px;
        }
        .info-reporte { 
            background-color: #f8f9fa; 
            padding: 8px; 
            border-radius: 3px; 
            margin-bottom: 12px; 
            border-left: 3px solid #3498db; 
            font-size: 9px;
        }
        .estadisticas {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 12px;
        }
        .estadistica-item {
            text-align: center;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f8f9fa;
        }
        .estadistica-numero {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        .estadistica-label {
            font-size: 8px;
            color: #7f8c8d;
            margin-top: 2px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 12px; 
            font-size: 8px;
        }
        .table th { 
            background-color: #34495e; 
            color: white; 
            padding: 4px; 
            text-align: left; 
            border: 1px solid #ddd; 
            font-weight: bold;
        }
        .table td { 
            padding: 3px; 
            border: 1px solid #ddd; 
            vertical-align: top;
        }
        .table tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }
        .footer { 
            margin-top: 15px; 
            text-align: center; 
            color: #7f8c8d; 
            font-size: 8px; 
            border-top: 1px solid #bdc3c7; 
            padding-top: 6px; 
        }
        .section-header {
            background-color: #e3f2fd;
            padding: 4px;
            border-left: 3px solid #2196f3;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }
        .badge-presente {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-tardanza {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-ausente {
            background-color: #f8d7da;
            color: #721c24;
        }
        .page-break {
            page-break-before: always;
        }
        .text-small {
            font-size: 7px;
        }
        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>REPORTE DE ASISTENCIA DOCENTE</h1>
        <h2>{{ ucfirst($filtros['tipo_reporte']) == 'docente' ? 'POR DOCENTE' : 'POR GRUPO' }}</h2>
        <p class="text-small">Sistema Académico - {{ $gestion->gestion }}</p>
    </div>

    <!-- Información del Reporte -->
    <div class="info-reporte">
        <strong>Período:</strong> {{ $filtros['fecha_inicio'] }} - {{ $filtros['fecha_fin'] }}<br>
        <strong>Fecha de Generación:</strong> {{ $fechaGeneracion }}<br>
        <strong>Generado por:</strong> {{ $usuario }}<br>
        <strong>Tipo de Reporte:</strong> {{ ucfirst($filtros['tipo_reporte']) }}<br>
        <strong>Filtros Aplicados:</strong> 
        {{-- CORRECCIÓN: Verificar si las claves existen antes de acceder --}}
        @php
            $filtrosAplicados = [];
            
            if (isset($filtros['id_docente']) && !empty($filtros['id_docente'])) {
                $filtrosAplicados[] = 'Docente Específico';
            }
            
            if (isset($filtros['id_grupo']) && !empty($filtros['id_grupo'])) {
                $filtrosAplicados[] = 'Grupo Específico';
            }
            
            if (isset($filtros['id_materia']) && !empty($filtros['id_materia'])) {
                $filtrosAplicados[] = 'Materia Específica';
            }
            
            if (isset($filtros['estado_asistencia']) && $filtros['estado_asistencia'] != 'todos') {
                $filtrosAplicados[] = 'Estado: ' . ucfirst($filtros['estado_asistencia']);
            }
            
            echo implode(', ', $filtrosAplicados) ?: 'Ninguno';
        @endphp
    </div>

    <!-- Estadísticas -->
    <div class="estadisticas">
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['total_asistencias'] ?? 0 }}</div>
            <div class="estadistica-label">Total Registros</div>
        </div>
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['presentes'] ?? 0 }}</div>
            <div class="estadistica-label">Presentes ({{ $estadisticas['porcentaje_presentes'] ?? 0 }}%)</div>
        </div>
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['tardanzas'] ?? 0 }}</div>
            <div class="estadistica-label">Tardanzas ({{ $estadisticas['porcentaje_tardanzas'] ?? 0 }}%)</div>
        </div>
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['ausentes'] ?? 0 }}</div>
            <div class="estadistica-label">Ausentes</div>
        </div>
    </div>

    <!-- Contenido según tipo de reporte -->
    @if($filtros['tipo_reporte'] == 'docente')
        <!-- Reporte por Docente en PDF -->
        @if(isset($datos['docentes']) && count($datos['docentes']) > 0)
            @foreach($datos['docentes'] as $docenteData)
            <div class="section-header">
                <h3>{{ $docenteData['docente']->user->name ?? 'Docente' }} ({{ $docenteData['docente']->codigo ?? 'N/A' }})</h3>
            </div>
            
            @if(count($docenteData['asistencias']) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($docenteData['asistencias'] as $fecha => $asistenciasDia)
                            @foreach($asistenciasDia as $asistencia)
                            <tr>
                                <td class="nowrap">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                                <td class="nowrap">{{ $asistencia->hora_registro ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->sigla ?? 'N/A' }}</strong><br>
                                    <span class="text-small">{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? '' }}</span>
                                </td>
                                <td>{{ $asistencia->grupoMateriaHorario->grupoMateria->grupo->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if(($asistencia->estado ?? '') == 'presente')
                                        <span class="badge badge-presente">PRESENTE</span>
                                    @elseif(($asistencia->estado ?? '') == 'tardanza')
                                        <span class="badge badge-tardanza">TARDANZA</span>
                                    @else
                                        <span class="badge badge-ausente">AUSENTE</span>
                                    @endif
                                </td>
                                <td class="text-small">{{ ucfirst($asistencia->metodo ?? 'N/A') }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 12px;">
                    No tiene asistencias registradas en el período seleccionado
                </p>
            @endif

            <!-- Salto de página después de cada docente (excepto el último) -->
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
            @endforeach
        @else
            <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 12px;">
                No se encontraron docentes con asistencias en el período seleccionado
            </p>
        @endif

    @elseif($filtros['tipo_reporte'] == 'grupo')
        <!-- Reporte por Grupo en PDF -->
        @if(isset($datos['grupos']) && count($datos['grupos']) > 0)
            @foreach($datos['grupos'] as $grupoData)
            <div class="section-header">
                <h3>Grupo: {{ $grupoData['grupo']->nombre ?? 'N/A' }}</h3>
            </div>
            
            @if(count($grupoData['asistencias']) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Materia</th>
                            <th>Docente</th>
                            <th>Estado</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grupoData['asistencias'] as $fecha => $asistenciasDia)
                            @foreach($asistenciasDia as $asistencia)
                            <tr>
                                <td class="nowrap">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                                <td class="nowrap">{{ $asistencia->hora_registro ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->sigla ?? 'N/A' }}</strong><br>
                                    <span class="text-small">{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? '' }}</span>
                                </td>
                                <td class="text-small">{{ $asistencia->grupoMateriaHorario->docente->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if(($asistencia->estado ?? '') == 'presente')
                                        <span class="badge badge-presente">PRESENTE</span>
                                    @elseif(($asistencia->estado ?? '') == 'tardanza')
                                        <span class="badge badge-tardanza">TARDANZA</span>
                                    @else
                                        <span class="badge badge-ausente">AUSENTE</span>
                                    @endif
                                </td>
                                <td class="text-small">{{ ucfirst($asistencia->metodo ?? 'N/A') }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 12px;">
                    El grupo no tiene asistencias registradas en el período seleccionado
                </p>
            @endif

            <!-- Salto de página después de cada grupo (excepto el último) -->
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
            @endforeach
        @else
            <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 12px;">
                No se encontraron grupos con asistencias en el período seleccionado
            </p>
        @endif
    @endif

    <!-- Resumen Estadístico -->
    <div class="section-header" style="margin-top: 15px;">
        <h3>RESUMEN ESTADÍSTICO</h3>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Indicador</th>
                <th>Valor</th>
                <th>Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total de Registros</td>
                <td>{{ $estadisticas['total_asistencias'] ?? 0 }}</td>
                <td>100%</td>
            </tr>
            <tr>
                <td>Asistencias en Horario</td>
                <td>{{ $estadisticas['presentes'] ?? 0 }}</td>
                <td>{{ $estadisticas['porcentaje_presentes'] ?? 0 }}%</td>
            </tr>
            <tr>
                <td>Asistencias con Tardanza</td>
                <td>{{ $estadisticas['tardanzas'] ?? 0 }}</td>
                <td>{{ $estadisticas['porcentaje_tardanzas'] ?? 0 }}%</td>
            </tr>
            <tr>
                <td>Días del Período</td>
                <td colspan="2">{{ $estadisticas['periodo']['dias_totales'] ?? 0 }} días</td>
            </tr>
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        Reporte generado automáticamente por el Sistema Académico<br>
        {{ $fechaGeneracion }}<br>
        Página <span class="page-number"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>