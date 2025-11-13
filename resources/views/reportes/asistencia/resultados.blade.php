@extends('layouts.app')

@section('title', 'Resultados - Reporte de Asistencia')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    Resultados del Reporte de Asistencia
                </h3>
                <p class="mt-2 text-deep-teal-200 text-xs sm:text-sm">
                    Período: {{ $filtros['fecha_inicio'] }} - {{ $filtros['fecha_fin'] }}
                </p>
            </div>
            <div class="text-right text-deep-teal-200">
                <p class="text-xs sm:text-sm"><strong>Gestión:</strong> {{ $gestion->gestion ?? 'N/A' }}</p>
                <p class="text-xs sm:text-sm"><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Panel de Estadísticas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl sm:rounded-2xl p-2 sm:p-4 text-center shadow-sm">
                <div class="w-6 h-6 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 bg-blue-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-clipboard-list text-xs sm:text-base"></i>
                </div>
                <div class="text-lg sm:text-2xl font-bold text-blue-800">{{ $estadisticas['total_asistencias'] ?? 0 }}</div>
                <div class="text-xs text-blue-600 font-medium">Total Registros</div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl sm:rounded-2xl p-2 sm:p-4 text-center shadow-sm">
                <div class="w-6 h-6 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 bg-green-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-check-circle text-xs sm:text-base"></i>
                </div>
                <div class="text-lg sm:text-2xl font-bold text-green-800">{{ $estadisticas['presentes'] ?? 0 }}</div>
                <div class="text-xs text-green-600 font-medium">Presentes ({{ $estadisticas['porcentaje_presentes'] ?? 0 }}%)</div>
            </div>
            
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl sm:rounded-2xl p-2 sm:p-4 text-center shadow-sm">
                <div class="w-6 h-6 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 bg-amber-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-clock text-xs sm:text-base"></i>
                </div>
                <div class="text-lg sm:text-2xl font-bold text-amber-800">{{ $estadisticas['tardanzas'] ?? 0 }}</div>
                <div class="text-xs text-amber-600 font-medium">Tardanzas ({{ $estadisticas['porcentaje_tardanzas'] ?? 0 }}%)</div>
            </div>
            
            <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl sm:rounded-2xl p-2 sm:p-4 text-center shadow-sm">
                <div class="w-6 h-6 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 bg-red-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-times-circle text-xs sm:text-base"></i>
                </div>
                <div class="text-lg sm:text-2xl font-bold text-red-800">{{ $estadisticas['ausentes'] ?? 0 }}</div>
                <div class="text-xs text-red-600 font-medium">Ausentes</div>
            </div>
        </div>

        <!-- Información de Filtros -->
        <div class="bg-deep-teal-25 rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-deep-teal-100 shadow-sm mb-4 sm:mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                <div class="flex-1">
                    <h4 class="text-base sm:text-lg font-bold text-deep-teal-800 mb-2 sm:mb-3 flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filtros Aplicados
                    </h4>
                    <div class="flex flex-wrap gap-1 sm:gap-2">
                        <span class="inline-flex items-center px-2 py-1 bg-[#3CA6A6] text-white text-xs font-medium rounded-full">
                            <i class="fas fa-chart-bar mr-1"></i>
                            Tipo: {{ ucfirst($tipo_reporte) }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded-full">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $filtros['fecha_inicio'] }} a {{ $filtros['fecha_fin'] }}
                        </span>
                        @if(isset($filtros['id_docente']) && $filtros['id_docente'])
                            <span class="inline-flex items-center px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full">
                                <i class="fas fa-user mr-1"></i>
                                Docente Específico
                            </span>
                        @endif
                        @if(isset($filtros['id_grupo']) && $filtros['id_grupo'])
                            <span class="inline-flex items-center px-2 py-1 bg-amber-500 text-white text-xs font-medium rounded-full">
                                <i class="fas fa-users mr-1"></i>
                                Grupo Específico
                            </span>
                        @endif
                        @if(isset($filtros['id_materia']) && $filtros['id_materia'])
                            <span class="inline-flex items-center px-2 py-1 bg-purple-500 text-white text-xs font-medium rounded-full">
                                <i class="fas fa-book mr-1"></i>
                                Materia Específica
                            </span>
                        @endif
                        @if(isset($filtros['estado_asistencia']) && $filtros['estado_asistencia'] != 'todos')
                            <span class="inline-flex items-center px-2 py-1 bg-indigo-500 text-white text-xs font-medium rounded-full">
                                <i class="fas fa-user-check mr-1"></i>
                                Estado: {{ ucfirst($filtros['estado_asistencia']) }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col xs:flex-row gap-2">
                    <form action="{{ route('admin.reportes.asistencia.generar') }}" method="POST" class="w-full xs:w-auto">
                        @csrf
                        @foreach($filtros as $key => $value)
                            @if($value && !in_array($key, ['exportar_pdf', 'exportar_csv', 'exportar_xlsx']))
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <div class="flex flex-col xs:flex-row gap-2">
                            <button type="submit" name="exportar_pdf" value="1" 
                                    class="w-full xs:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow hover:shadow-lg transform hover:-translate-y-0.5 text-xs sm:text-sm">
                                <i class="fas fa-file-pdf mr-1 sm:mr-2"></i>
                                PDF
                            </button>
                            <button type="submit" name="exportar_csv" value="1" 
                                    class="w-full xs:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow hover:shadow-lg transform hover:-translate-y-0.5 text-xs sm:text-sm">
                                <i class="fas fa-file-csv mr-1 sm:mr-2"></i>
                                CSV
                            </button>
                            <button type="submit" name="exportar_xlsx" value="1" 
                                    class="w-full xs:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow hover:shadow-lg transform hover:-translate-y-0.5 text-xs sm:text-sm">
                                <i class="fas fa-file-excel mr-1 sm:mr-2"></i>
                                XLSX
                            </button>
                        </div>
                    </form>
                    <a href="{{ route('admin.reportes.asistencia.index') }}" 
                       class="w-full xs:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow hover:shadow-lg transform hover:-translate-y-0.5 text-xs sm:text-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Mensaje cuando no hay datos -->
        @if(empty($datos) || (($tipo_reporte == 'docente' && empty($datos['docentes'])) || ($tipo_reporte == 'grupo' && empty($datos['grupos']))))
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-deep-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-deep-teal-500 text-2xl"></i>
                </div>
                <h5 class="text-deep-teal-800 font-bold text-lg mb-2">No se encontraron registros</h5>
                <p class="text-deep-teal-600">No hay asistencias que cumplan con los criterios de búsqueda</p>
            </div>
        @else
            <!-- Contenido según el tipo de reporte -->
            @if($tipo_reporte == 'docente')
                <!-- Reporte por Docente -->
                <div class="bg-white rounded-xl sm:rounded-2xl border border-deep-teal-100 shadow-lg overflow-hidden">
                    <div class="gradient-bg px-3 sm:px-6 py-3 sm:py-4">
                        <h5 class="text-base sm:text-lg font-bold text-[#F2E3D5] mb-0 flex items-center">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            Reporte por Docente
                        </h5>
                    </div>
                    <div class="p-3 sm:p-6">
                        @if(isset($datos['docentes']) && count($datos['docentes']) > 0)
                            @foreach($datos['docentes'] as $docenteData)
                            <div class="bg-white rounded-lg sm:rounded-xl border border-deep-teal-100 shadow-sm mb-4 sm:mb-6 overflow-hidden">
                                <div class="bg-deep-teal-25 px-3 sm:px-4 py-2 sm:py-3 border-b border-deep-teal-100">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-2">
                                        <h6 class="font-bold text-deep-teal-800 mb-0 flex items-center text-sm sm:text-base">
                                            <i class="fas fa-user-graduate mr-2 text-light-teal"></i>
                                            {{ $docenteData['docente']->user->name ?? 'Docente' }}
                                        </h6>
                                        <span class="text-xs text-deep-teal-600 bg-white px-2 py-1 rounded-full border border-deep-teal-200">
                                            Código: {{ $docenteData['docente']->codigo ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3 sm:p-4">
                                    @if(isset($docenteData['asistencias']) && count($docenteData['asistencias']) > 0)
                                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                                            <table class="min-w-full divide-y divide-deep-teal-100 text-xs sm:text-sm">
                                                <thead class="bg-deep-teal-50">
                                                    <tr>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Fecha</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Hora</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Materia</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Grupo</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-deep-teal-50">
                                                    @foreach($docenteData['asistencias'] as $fecha => $asistenciasDia)
                                                        @foreach($asistenciasDia as $asistencia)
                                                        <tr class="hover:bg-deep-teal-25 transition-colors">
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2 whitespace-nowrap text-deep-teal-900">
                                                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-deep-teal-100 text-deep-teal-800 rounded-full text-xs font-medium">
                                                                    {{ $asistencia->hora_registro ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2">
                                                                <div class="font-medium text-deep-teal-900">{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->sigla ?? 'N/A' }}</div>
                                                                <div class="text-deep-teal-600 truncate max-w-[120px] sm:max-w-none">{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? '' }}</div>
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2">
                                                                <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-[#3CA6A6] text-white rounded-full text-xs font-medium">
                                                                    {{ $asistencia->grupoMateriaHorario->grupoMateria->grupo->nombre ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2">
                                                                @if(($asistencia->estado ?? '') == 'presente')
                                                                    <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-check mr-1"></i>Presente
                                                                    </span>
                                                                @elseif(($asistencia->estado ?? '') == 'tardanza')
                                                                    <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-clock mr-1"></i>Tardanza
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-times mr-1"></i>Ausente
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4 sm:py-6">
                                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-clipboard-list text-deep-teal-500 text-lg sm:text-xl"></i>
                                            </div>
                                            <p class="text-deep-teal-600 font-medium text-sm">No tiene asistencias registradas en el período seleccionado</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users-slash text-deep-teal-500 text-xl sm:text-2xl"></i>
                                </div>
                                <h5 class="text-deep-teal-800 font-bold text-base sm:text-lg mb-2">No se encontraron docentes con asistencias</h5>
                                <p class="text-deep-teal-600 text-sm">No hay docentes que cumplan con los criterios de búsqueda</p>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($tipo_reporte == 'grupo')
                <!-- Reporte por Grupo -->
                <div class="bg-white rounded-xl sm:rounded-2xl border border-deep-teal-100 shadow-lg overflow-hidden">
                    <div class="gradient-bg px-3 sm:px-6 py-3 sm:py-4">
                        <h5 class="text-base sm:text-lg font-bold text-[#F2E3D5] mb-0 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Reporte por Grupo
                        </h5>
                    </div>
                    <div class="p-3 sm:p-6">
                        @if(isset($datos['grupos']) && count($datos['grupos']) > 0)
                            @foreach($datos['grupos'] as $grupoData)
                            <div class="bg-white rounded-lg sm:rounded-xl border border-deep-teal-100 shadow-sm mb-4 sm:mb-6 overflow-hidden">
                                <div class="bg-deep-teal-25 px-3 sm:px-4 py-2 sm:py-3 border-b border-deep-teal-100">
                                    <h6 class="font-bold text-deep-teal-800 mb-0 flex items-center text-sm sm:text-base">
                                        <i class="fas fa-users mr-2 text-light-teal"></i>
                                        Grupo: {{ $grupoData['grupo']->nombre ?? 'N/A' }}
                                    </h6>
                                </div>
                                <div class="p-3 sm:p-4">
                                    @if(isset($grupoData['asistencias']) && count($grupoData['asistencias']) > 0)
                                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                                            <table class="min-w-full divide-y divide-deep-teal-100 text-xs sm:text-sm">
                                                <thead class="bg-deep-teal-50">
                                                    <tr>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Fecha</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Hora</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Materia</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Docente</th>
                                                        <th class="px-2 py-1 sm:px-3 sm:py-2 text-left font-bold text-deep-teal-800 uppercase">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-deep-teal-50">
                                                    @foreach($grupoData['asistencias'] as $fecha => $asistenciasDia)
                                                        @foreach($asistenciasDia as $asistencia)
                                                        <tr class="hover:bg-deep-teal-25 transition-colors">
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2 whitespace-nowrap text-deep-teal-900">
                                                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-deep-teal-100 text-deep-teal-800 rounded-full text-xs font-medium">
                                                                    {{ $asistencia->hora_registro ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2">
                                                                <div class="font-medium text-deep-teal-900">{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->sigla ?? 'N/A' }}</div>
                                                                <div class="text-deep-teal-600 truncate max-w-[120px] sm:max-w-none">{{ $asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? '' }}</div>
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2 text-deep-teal-900 truncate max-w-[100px] sm:max-w-none">
                                                                {{ $asistencia->grupoMateriaHorario->docente->user->name ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-2 py-1 sm:px-3 sm:py-2">
                                                                @if(($asistencia->estado ?? '') == 'presente')
                                                                    <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-check mr-1"></i>Presente
                                                                    </span>
                                                                @elseif(($asistencia->estado ?? '') == 'tardanza')
                                                                    <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-clock mr-1"></i>Tardanza
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-1 sm:px-2 py-0.5 sm:py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-times mr-1"></i>Ausente
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4 sm:py-6">
                                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-clipboard-list text-deep-teal-500 text-lg sm:text-xl"></i>
                                            </div>
                                            <p class="text-deep-teal-600 font-medium text-sm">El grupo no tiene asistencias registradas en el período seleccionado</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 bg-deep-teal-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-deep-teal-500 text-xl sm:text-2xl"></i>
                                </div>
                                <h5 class="text-deep-teal-800 font-bold text-base sm:text-lg mb-2">No se encontraron grupos con asistencias</h5>
                                <p class="text-deep-teal-600 text-sm">No hay grupos que cumplan con los criterios de búsqueda</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #012E40 0%, #024959 100%);
    }
</style>
@endsection