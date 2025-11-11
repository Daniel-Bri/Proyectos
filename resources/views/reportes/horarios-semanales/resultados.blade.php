@extends('layouts.app')

@section('title', 'Resultados - Reporte de Horarios')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-calendar-week mr-3"></i>
                    Resultados del Reporte
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Semana {{ $infoSemana['numero'] }}: {{ $infoSemana['fecha_inicio']->format('d/m/Y') }} - {{ $infoSemana['fecha_fin']->format('d/m/Y') }}
                </p>
            </div>
            <div class="text-right text-deep-teal-200">
                <p class="text-sm"><strong>Gestión:</strong> {{ $gestion->gestion }}</p>
                <p class="text-sm"><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Panel de Estadísticas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-3 sm:p-4 text-center shadow-sm">
                <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-blue-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-clock text-sm sm:text-base"></i>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-blue-800">{{ $estadisticas['total_horarios'] }}</div>
                <div class="text-xs sm:text-sm text-blue-600 font-medium">Total Horarios</div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-3 sm:p-4 text-center shadow-sm">
                <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-green-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-chalkboard-teacher text-sm sm:text-base"></i>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-green-800">{{ $estadisticas['docentes_activos'] }}</div>
                <div class="text-xs sm:text-sm text-green-600 font-medium">Docentes</div>
            </div>
            
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-3 sm:p-4 text-center shadow-sm">
                <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-amber-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-building text-sm sm:text-base"></i>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-amber-800">{{ $estadisticas['aulas_utilizadas'] }}</div>
                <div class="text-xs sm:text-sm text-amber-600 font-medium">Aulas</div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-3 sm:p-4 text-center shadow-sm">
                <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-purple-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-book text-sm sm:text-base"></i>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-purple-800">{{ $estadisticas['materias_impartidas'] }}</div>
                <div class="text-xs sm:text-sm text-purple-600 font-medium">Materias</div>
            </div>
        </div>

        <!-- Información de Filtros -->
        <div class="bg-deep-teal-25 rounded-2xl p-4 sm:p-6 border border-deep-teal-100 shadow-sm mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-deep-teal-800 mb-3 flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filtros Aplicados
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 bg-[#3CA6A6] text-white text-sm font-medium rounded-full">
                            <i class="fas fa-eye mr-1"></i>
                            Vista: {{ ucfirst($tipo_vista) }}
                        </span>
                        @if($filtros['id_docente'])
                            <span class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded-full">
                                <i class="fas fa-user mr-1"></i>
                                Docente Específico
                            </span>
                        @endif
                        @if($filtros['id_aula'])
                            <span class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                                <i class="fas fa-door-open mr-1"></i>
                                Aula Específica
                            </span>
                        @endif
                        @if($filtros['id_grupo'])
                            <span class="inline-flex items-center px-3 py-1 bg-amber-500 text-white text-sm font-medium rounded-full">
                                <i class="fas fa-users mr-1"></i>
                                Grupo Específico
                            </span>
                        @endif
                        @if($filtros['rango_horas'] != 'todo')
                            <span class="inline-flex items-center px-3 py-1 bg-purple-500 text-white text-sm font-medium rounded-full">
                                <i class="fas fa-clock mr-1"></i>
                                Horario: {{ ucfirst($filtros['rango_horas']) }}
                            </span>
                        @endif
                    </div>
                </div>
                
<div class="flex flex-col sm:flex-row gap-2">
    <form action="{{ route('admin.reportes.horarios-semanales.generar') }}" method="POST" class="w-full sm:w-auto">
        @csrf
        @foreach($filtros as $key => $value)
            @if($value && !in_array($key, ['exportar_pdf', 'exportar_csv', 'exportar_xlsx']))
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        <button type="submit" name="exportar_pdf" value="1" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm mb-2">
            <i class="fas fa-file-pdf mr-2"></i>
            PDF
        </button>
        <button type="submit" name="exportar_csv" value="1" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm mb-2">
            <i class="fas fa-file-csv mr-2"></i>
            CSV
        </button>
        <button type="submit" name="exportar_xlsx" value="1" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm mb-2">
            <i class="fas fa-file-excel mr-2"></i>
            XLSX
        </button>
    </form>
    <a href="{{ route('admin.reportes.horarios-semanales.index') }}" 
       class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver
    </a>
</div>
            </div>
        </div>

        <!-- Contenido según el tipo de vista -->
        @if($tipo_vista == 'docente')
            <!-- Vista por Docente -->
            <div class="bg-white rounded-2xl border border-deep-teal-100 shadow-lg overflow-hidden">
                <div class="gradient-bg px-4 py-4 sm:px-6">
                    <h5 class="text-lg font-bold text-[#F2E3D5] mb-0 flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Vista por Docente
                    </h5>
                </div>
                <div class="p-4 sm:p-6">
                    @if(count($datos['docentes']) > 0)
                        @foreach($datos['docentes'] as $docenteData)
                        <div class="bg-white rounded-xl border border-deep-teal-100 shadow-sm mb-6 overflow-hidden">
                            <div class="bg-deep-teal-25 px-4 py-3 border-b border-deep-teal-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <h6 class="font-bold text-deep-teal-800 mb-0 flex items-center text-sm sm:text-base">
                                        <i class="fas fa-user-graduate mr-2 text-light-teal"></i>
                                        {{ $docenteData['docente']->user->name ?? 'Docente' }}
                                    </h6>
                                    <span class="text-xs sm:text-sm text-deep-teal-600 bg-white px-2 py-1 rounded-full border border-deep-teal-200">
                                        Código: {{ $docenteData['docente']->codigo }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                @if(count($docenteData['horarios']) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-deep-teal-100">
                                            <thead class="bg-deep-teal-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Día</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Horario</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Materia</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Grupo</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Aula</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-deep-teal-50">
                                                @foreach(['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'] as $dia)
                                                    @if(isset($docenteData['horarios'][$dia]))
                                                        @foreach($docenteData['horarios'][$dia] as $horario)
                                                        <tr class="hover:bg-deep-teal-25 transition-colors">
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                    @if($dia == 'LUN') bg-blue-100 text-blue-800
                                                                    @elseif($dia == 'MAR') bg-green-100 text-green-800
                                                                    @elseif($dia == 'MIE') bg-cyan-100 text-cyan-800
                                                                    @elseif($dia == 'JUE') bg-amber-100 text-amber-800
                                                                    @elseif($dia == 'VIE') bg-rose-100 text-rose-800
                                                                    @else bg-purple-100 text-purple-800 @endif">
                                                                    @switch($dia)
                                                                        @case('LUN') Lun @break
                                                                        @case('MAR') Mar @break
                                                                        @case('MIE') Mié @break
                                                                        @case('JUE') Jue @break
                                                                        @case('VIE') Vie @break
                                                                        @case('SAB') Sáb @break
                                                                    @endswitch
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-1 bg-deep-teal-100 text-deep-teal-800 rounded-full text-xs font-medium">
                                                                    {{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2">
                                                                <div class="text-sm font-medium text-deep-teal-900">{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</div>
                                                                <div class="text-xs text-deep-teal-600">{{ $horario->grupoMateria->materia->nombre ?? '' }}</div>
                                                            </td>
                                                            <td class="px-3 py-2">
                                                                <span class="inline-flex items-center px-2 py-1 bg-[#3CA6A6] text-white rounded-full text-xs font-medium">
                                                                    {{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2 text-sm text-deep-teal-900">{{ $horario->aula->nombre ?? 'N/A' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-times text-deep-teal-500 text-xl"></i>
                                        </div>
                                        <p class="text-deep-teal-600 font-medium">No tiene horarios asignados esta semana</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-20 h-20 mx-auto mb-4 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users-slash text-deep-teal-500 text-2xl"></i>
                            </div>
                            <h5 class="text-deep-teal-800 font-bold text-lg mb-2">No se encontraron docentes con horarios</h5>
                            <p class="text-deep-teal-600">No hay docentes que cumplan con los criterios de búsqueda</p>
                        </div>
                    @endif
                </div>
            </div>

        @elseif($tipo_vista == 'aula')
            <!-- Vista por Aula -->
            <div class="bg-white rounded-2xl border border-deep-teal-100 shadow-lg overflow-hidden">
                <div class="gradient-bg px-4 py-4 sm:px-6">
                    <h5 class="text-lg font-bold text-[#F2E3D5] mb-0 flex items-center">
                        <i class="fas fa-door-open mr-2"></i>
                        Vista por Aula
                    </h5>
                </div>
                <div class="p-4 sm:p-6">
                    @if(count($datos['aulas']) > 0)
                        @foreach($datos['aulas'] as $aulaData)
                        <div class="bg-white rounded-xl border border-deep-teal-100 shadow-sm mb-6 overflow-hidden">
                            <div class="bg-deep-teal-25 px-4 py-3 border-b border-deep-teal-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <h6 class="font-bold text-deep-teal-800 mb-0 flex items-center text-sm sm:text-base">
                                        <i class="fas fa-building mr-2 text-light-teal"></i>
                                        {{ $aulaData['aula']->nombre }}
                                    </h6>
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center px-2 py-1 bg-[#3CA6A6] text-white text-xs rounded-full">
                                            {{ $aulaData['aula']->tipo }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 bg-deep-teal-200 text-deep-teal-800 text-xs rounded-full">
                                            Cap: {{ $aulaData['aula']->capacidad }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                @if(count($aulaData['horarios']) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-deep-teal-100">
                                            <thead class="bg-deep-teal-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Día</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Horario</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Materia</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Docente</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Grupo</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-deep-teal-50">
                                                @foreach(['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'] as $dia)
                                                    @if(isset($aulaData['horarios'][$dia]))
                                                        @foreach($aulaData['horarios'][$dia] as $horario)
                                                        <tr class="hover:bg-deep-teal-25 transition-colors">
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                    @if($dia == 'LUN') bg-blue-100 text-blue-800
                                                                    @elseif($dia == 'MAR') bg-green-100 text-green-800
                                                                    @elseif($dia == 'MIE') bg-cyan-100 text-cyan-800
                                                                    @elseif($dia == 'JUE') bg-amber-100 text-amber-800
                                                                    @elseif($dia == 'VIE') bg-rose-100 text-rose-800
                                                                    @else bg-purple-100 text-purple-800 @endif">
                                                                    @switch($dia)
                                                                        @case('LUN') Lun @break
                                                                        @case('MAR') Mar @break
                                                                        @case('MIE') Mié @break
                                                                        @case('JUE') Jue @break
                                                                        @case('VIE') Vie @break
                                                                        @case('SAB') Sáb @break
                                                                    @endswitch
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-1 bg-deep-teal-100 text-deep-teal-800 rounded-full text-xs font-medium">
                                                                    {{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2">
                                                                <div class="text-sm font-medium text-deep-teal-900">{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</div>
                                                                <div class="text-xs text-deep-teal-600">{{ $horario->grupoMateria->materia->nombre ?? '' }}</div>
                                                            </td>
                                                            <td class="px-3 py-2 text-sm text-deep-teal-900">{{ $horario->docente->user->name ?? 'N/A' }}</td>
                                                            <td class="px-3 py-2">
                                                                <span class="inline-flex items-center px-2 py-1 bg-[#3CA6A6] text-white rounded-full text-xs font-medium">
                                                                    {{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-door-closed text-deep-teal-500 text-xl"></i>
                                        </div>
                                        <p class="text-deep-teal-600 font-medium">El aula no tiene horarios asignados esta semana</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-20 h-20 mx-auto mb-4 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-building text-deep-teal-500 text-2xl"></i>
                            </div>
                            <h5 class="text-deep-teal-800 font-bold text-lg mb-2">No se encontraron aulas con horarios</h5>
                            <p class="text-deep-teal-600">No hay aulas que cumplan con los criterios de búsqueda</p>
                        </div>
                    @endif
                </div>
            </div>

        @elseif($tipo_vista == 'grupo')
            <!-- Vista por Grupo -->
            <div class="bg-white rounded-2xl border border-deep-teal-100 shadow-lg overflow-hidden">
                <div class="gradient-bg px-4 py-4 sm:px-6">
                    <h5 class="text-lg font-bold text-[#F2E3D5] mb-0 flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        Vista por Grupo
                    </h5>
                </div>
                <div class="p-4 sm:p-6">
                    @if(count($datos['grupos']) > 0)
                        @foreach($datos['grupos'] as $grupoData)
                        <div class="bg-white rounded-xl border border-deep-teal-100 shadow-sm mb-6 overflow-hidden">
                            <div class="bg-deep-teal-25 px-4 py-3 border-b border-deep-teal-100">
                                <h6 class="font-bold text-deep-teal-800 mb-0 flex items-center text-sm sm:text-base">
                                    <i class="fas fa-users mr-2 text-light-teal"></i>
                                    Grupo: {{ $grupoData['grupo']->nombre }}
                                </h6>
                            </div>
                            <div class="p-4">
                                @if(count($grupoData['horarios']) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-deep-teal-100">
                                            <thead class="bg-deep-teal-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Día</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Horario</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Materia</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Docente</th>
                                                    <th class="px-3 py-2 text-left text-xs font-bold text-deep-teal-800 uppercase">Aula</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-deep-teal-50">
                                                @foreach(['LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'] as $dia)
                                                    @if(isset($grupoData['horarios'][$dia]))
                                                        @foreach($grupoData['horarios'][$dia] as $horario)
                                                        <tr class="hover:bg-deep-teal-25 transition-colors">
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                    @if($dia == 'LUN') bg-blue-100 text-blue-800
                                                                    @elseif($dia == 'MAR') bg-green-100 text-green-800
                                                                    @elseif($dia == 'MIE') bg-cyan-100 text-cyan-800
                                                                    @elseif($dia == 'JUE') bg-amber-100 text-amber-800
                                                                    @elseif($dia == 'VIE') bg-rose-100 text-rose-800
                                                                    @else bg-purple-100 text-purple-800 @endif">
                                                                    @switch($dia)
                                                                        @case('LUN') Lun @break
                                                                        @case('MAR') Mar @break
                                                                        @case('MIE') Mié @break
                                                                        @case('JUE') Jue @break
                                                                        @case('VIE') Vie @break
                                                                        @case('SAB') Sáb @break
                                                                    @endswitch
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-1 bg-deep-teal-100 text-deep-teal-800 rounded-full text-xs font-medium">
                                                                    {{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2">
                                                                <div class="text-sm font-medium text-deep-teal-900">{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</div>
                                                                <div class="text-xs text-deep-teal-600">{{ $horario->grupoMateria->materia->nombre ?? '' }}</div>
                                                            </td>
                                                            <td class="px-3 py-2 text-sm text-deep-teal-900">{{ $horario->docente->user->name ?? 'N/A' }}</td>
                                                            <td class="px-3 py-2 text-sm text-deep-teal-900">{{ $horario->aula->nombre ?? 'N/A' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <div class="w-16 h-16 mx-auto mb-3 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-users-slash text-deep-teal-500 text-xl"></i>
                                        </div>
                                        <p class="text-deep-teal-600 font-medium">El grupo no tiene horarios asignados esta semana</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-20 h-20 mx-auto mb-4 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-deep-teal-500 text-2xl"></i>
                            </div>
                            <h5 class="text-deep-teal-800 font-bold text-lg mb-2">No se encontraron grupos con horarios</h5>
                            <p class="text-deep-teal-600">No hay grupos que cumplan con los criterios de búsqueda</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #012E40 0%, #024959 100%);
    }
    
    @media (max-width: 640px) {
        /* Mejoras para tablas en móviles */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Asegurar que los botones sean táctiles */
        button, a {
            min-height: 44px;
        }
    }
</style>
@endsection