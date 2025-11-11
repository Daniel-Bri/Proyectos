<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Horarios - Semana {{ $infoSemana['numero'] }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 10px; 
        }
        .header h1 { 
            color: #2c3e50; 
            margin: 0; 
            font-size: 24px; 
        }
        .header h2 {
            color: #7f8c8d;
            margin: 5px 0;
            font-size: 16px;
        }
        .info-reporte { 
            background-color: #f8f9fa; 
            padding: 15px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            border-left: 4px solid #3498db; 
        }
        .estadisticas {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .estadistica-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .estadistica-numero {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .estadistica-label {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            font-size: 10px;
        }
        .table th { 
            background-color: #34495e; 
            color: white; 
            padding: 8px; 
            text-align: left; 
            border: 1px solid #ddd; 
        }
        .table td { 
            padding: 6px; 
            border: 1px solid #ddd; 
            vertical-align: top;
        }
        .table tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            color: #7f8c8d; 
            font-size: 10px; 
            border-top: 1px solid #bdc3c7; 
            padding-top: 10px; 
        }
        .section-header {
            background-color: #e3f2fd;
            padding: 8px;
            border-left: 4px solid #2196f3;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>REPORTE SEMANAL DE HORARIOS</h1>
        <h2>Semana {{ $infoSemana['numero'] }}: {{ $infoSemana['fecha_inicio']->format('d/m/Y') }} - {{ $infoSemana['fecha_fin']->format('d/m/Y') }}</h2>
        <p>Sistema Académico - {{ $gestion->gestion }}</p>
    </div>

    <!-- Información del Reporte -->
    <div class="info-reporte">
        <strong>Fecha de Generación:</strong> {{ $fechaGeneracion }}<br>
        <strong>Generado por:</strong> {{ $usuario }}<br>
        <strong>Tipo de Vista:</strong> {{ ucfirst($filtros['tipo_vista']) }}<br>
        <strong>Filtros Aplicados:</strong> 
        {{ $filtros['id_docente'] ? 'Docente Específico ' : '' }}
        {{ $filtros['id_aula'] ? 'Aula Específica ' : '' }}
        {{ $filtros['id_grupo'] ? 'Grupo Específico ' : '' }}
        {{ $filtros['rango_horas'] != 'todo' ? 'Horario: ' . ucfirst($filtros['rango_horas']) : '' }}
    </div>

    <!-- Estadísticas -->
    <div class="estadisticas">
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['total_horarios'] }}</div>
            <div class="estadistica-label">Total Horarios</div>
        </div>
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['docentes_activos'] }}</div>
            <div class="estadistica-label">Docentes</div>
        </div>
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['aulas_utilizadas'] }}</div>
            <div class="estadistica-label">Aulas</div>
        </div>
        <div class="estadistica-item">
            <div class="estadistica-numero">{{ $estadisticas['materias_impartidas'] }}</div>
            <div class="estadistica-label">Materias</div>
        </div>
    </div>

    <!-- Contenido según tipo de vista -->
    @if($filtros['tipo_vista'] == 'docente')
        <!-- Vista por Docente en PDF -->
        @foreach($datos['docentes'] as $docenteData)
        <div class="section-header">
            <h3>{{ $docenteData['docente']->user->name ?? 'Docente' }} ({{ $docenteData['docente']->codigo }})</h3>
        </div>
        
        @if(count($docenteData['horarios']) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Materia</th>
                        <th>Grupo</th>
                        <th>Aula</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'] as $dia)
                        @if(isset($docenteData['horarios'][$dia]))
                            @foreach($docenteData['horarios'][$dia] as $horario)
                            <tr>
                                <td>
                                    @switch($dia)
                                        @case('LUN') Lunes @break
                                        @case('MAR') Martes @break
                                        @case('MIE') Miércoles @break
                                        @case('JUE') Jueves @break
                                        @case('VIE') Viernes @break
                                        @case('SAB') Sábado @break
                                    @endswitch
                                </td>
                                <td>{{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}</td>
                                <td>
                                    <strong>{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</strong><br>
                                    <small>{{ $horario->grupoMateria->materia->nombre ?? '' }}</small>
                                </td>
                                <td>{{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}</td>
                                <td>{{ $horario->aula->nombre ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 20px;">
                No tiene horarios asignados esta semana
            </p>
        @endif
        @endforeach

    @elseif($filtros['tipo_vista'] == 'aula')
        <!-- Vista por Aula en PDF -->
        @foreach($datos['aulas'] as $aulaData)
        <div class="section-header">
            <h3>{{ $aulaData['aula']->nombre }} - {{ $aulaData['aula']->tipo }} (Capacidad: {{ $aulaData['aula']->capacidad }})</h3>
        </div>
        
        @if(count($aulaData['horarios']) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'] as $dia)
                        @if(isset($aulaData['horarios'][$dia]))
                            @foreach($aulaData['horarios'][$dia] as $horario)
                            <tr>
                                <td>
                                    @switch($dia)
                                        @case('LUN') Lunes @break
                                        @case('MAR') Martes @break
                                        @case('MIE') Miércoles @break
                                        @case('JUE') Jueves @break
                                        @case('VIE') Viernes @break
                                        @case('SAB') Sábado @break
                                    @endswitch
                                </td>
                                <td>{{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}</td>
                                <td>
                                    <strong>{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</strong><br>
                                    <small>{{ $horario->grupoMateria->materia->nombre ?? '' }}</small>
                                </td>
                                <td>{{ $horario->docente->user->name ?? 'N/A' }}</td>
                                <td>{{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 20px;">
                El aula no tiene horarios asignados esta semana
            </p>
        @endif
        @endforeach

    @elseif($filtros['tipo_vista'] == 'grupo')
        <!-- Vista por Grupo en PDF -->
        @foreach($datos['grupos'] as $grupoData)
        <div class="section-header">
            <h3>Grupo: {{ $grupoData['grupo']->nombre }}</h3>
        </div>
        
        @if(count($grupoData['horarios']) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Aula</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'] as $dia)
                        @if(isset($grupoData['horarios'][$dia]))
                            @foreach($grupoData['horarios'][$dia] as $horario)
                            <tr>
                                <td>
                                    @switch($dia)
                                        @case('LUN') Lunes @break
                                        @case('MAR') Martes @break
                                        @case('MIE') Miércoles @break
                                        @case('JUE') Jueves @break
                                        @case('VIE') Viernes @break
                                        @case('SAB') Sábado @break
                                    @endswitch
                                </td>
                                <td>{{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}</td>
                                <td>
                                    <strong>{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</strong><br>
                                    <small>{{ $horario->grupoMateria->materia->nombre ?? '' }}</small>
                                </td>
                                <td>{{ $horario->docente->user->name ?? 'N/A' }}</td>
                                <td>{{ $horario->aula->nombre ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-bottom: 20px;">
                El grupo no tiene horarios asignados esta semana
            </p>
        @endif
        @endforeach
    @endif

    <!-- Pie de página -->
    <div class="footer">
        Reporte generado automáticamente por el Sistema Académico<br>
        {{ $fechaGeneracion }}
    </div>
</body>
</html>