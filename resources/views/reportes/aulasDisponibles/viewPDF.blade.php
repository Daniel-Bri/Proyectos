<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Aulas Disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #026773;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #026773;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .filtros {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background: #026773;
            color: white;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th {
            background: #e9ecef;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .disponible {
            background: #d4edda;
        }
        .ocupada {
            background: #f8d7da;
        }
        .resumen {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Aulas Disponibles</h1>
        <p>Sistema de Gestión de Horarios - FICCT</p>
        <p>Generado el: {{ $fechaGeneracion }}</p>
    </div>

    <!-- FILTROS APLICADOS -->
    @if(!empty(array_filter($filtros)))
    <div class="filtros">
        <strong>Filtros Aplicados:</strong>
        @if(!empty($filtros['dia'])) | Día: {{ $filtros['dia'] }} @endif
        @if(!empty($filtros['hora'])) | Hora: {{ $filtros['hora'] }} @endif
        @if(!empty($filtros['tipo_aula'])) | Tipo: {{ $filtros['tipo_aula'] }} @endif
        @if(!empty($filtros['capacidad_minima'])) | Capacidad mínima: {{ $filtros['capacidad_minima'] }} @endif
    </div>
    @endif

    <!-- RESUMEN -->
    <div class="resumen">
        <span>Total Aulas Disponibles: {{ $totalDisponibles }}</span>
        <span>Total Aulas Ocupadas: {{ $totalOcupadas }}</span>
        <span>Total Aulas: {{ $totalDisponibles + $totalOcupadas }}</span>
    </div>

    <!-- AULAS DISPONIBLES -->
    <div class="section">
        <div class="section-title">AULAS DISPONIBLES ({{ $totalDisponibles }})</div>
        
        @if($totalDisponibles > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Capacidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aulasDisponibles as $aula)
                <tr class="disponible">
                    <td>{{ $aula->nombre }}</td>
                    <td>{{ ucfirst($aula->tipo) }}</td>
                    <td>{{ $aula->capacidad }} estudiantes</td>
                    <td><strong>DISPONIBLE</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #666; padding: 20px;">
            No hay aulas disponibles con los filtros aplicados
        </p>
        @endif
    </div>

    <!-- AULAS OCUPADAS -->
    <div class="section">
        <div class="section-title">AULAS OCUPADAS ({{ $totalOcupadas }})</div>
        
        @if($totalOcupadas > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Aula</th>
                    <th>Materia</th>
                    <th>Docente</th>
                    <th>Grupo</th>
                    <th>Horario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aulasOcupadas as $ocupacion)
                <tr class="ocupada">
                    <td>{{ $ocupacion->aula_nombre }}</td>
                    <td>{{ $ocupacion->materia_nombre }}</td>
                    <td>{{ $ocupacion->docente_nombre }}</td>
                    <td>{{ $ocupacion->grupo_nombre }}</td>
                    <td>{{ $ocupacion->dia }} {{ $ocupacion->hora_inicio }}-{{ $ocupacion->hora_fin }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #666; padding: 20px;">
            No hay aulas ocupadas con los filtros aplicados
        </p>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión de Horarios</p>
        <p>Universidad Autónoma Gabriel René Moreno - FICCT</p>
    </div>
</body>
</html>