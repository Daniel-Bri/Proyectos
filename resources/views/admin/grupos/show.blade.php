@extends('layouts.app')

@section('title', 'Detalles del Grupo - Admin')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-4">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header Mobile Optimizado -->
        <div class="gradient-bg px-3 py-4 sm:px-6">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-users mr-2 sm:mr-3"></i>
                        Grupo: {{ $grupo->nombre }}
                    </h3>
                    <p class="mt-1 sm:mt-2 text-deep-teal-200 text-xs sm:text-sm">
                        Información completa del grupo académico
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 justify-center sm:justify-end">
                    <a href="{{ route('admin.grupos.edit', $grupo->id) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-amber-500 hover:bg-amber-600 border border-transparent rounded-lg sm:rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-1 sm:mr-2"></i>
                        Editar
                    </a>
                    
                    <a href="{{ route('admin.grupos.asignar-materias', $grupo->id) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-lg sm:rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-book mr-1 sm:mr-2"></i>
                        Asignar Materias
                    </a>
                    
                    <a href="{{ route('admin.grupos.index') }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <!-- Grid de Información Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Información Principal -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-blue-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-sm sm:text-base"></i>
                        Información Principal
                    </h4>
                    <dl class="space-y-3 sm:space-y-4">
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-blue-700">ID</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-bold sm:text-lg">{{ $grupo->id }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-blue-700">Nombre</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-semibold">{{ $grupo->nombre }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-blue-700">Gestión</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-semibold">{{ $grupo->gestion }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Estadísticas -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-purple-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-purple-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-sm sm:text-base"></i>
                        Estadísticas
                    </h4>
                    <dl class="space-y-3 sm:space-y-4">
                        @php
                            $totalHorarios = $grupo->grupoMaterias->sum(function($grupoMateria) { 
                                return $grupoMateria->horarios->count(); 
                            });
                            $docentesUnicos = [];
                            $aulasUnicas = [];
                            foreach($grupo->grupoMaterias as $grupoMateria) {
                                foreach($grupoMateria->horarios as $horario) {
                                    if ($horario->docente && !in_array($horario->docente->codigo, $docentesUnicos)) {
                                        $docentesUnicos[] = $horario->docente->codigo;
                                    }
                                    if ($horario->aula && !in_array($horario->aula->id, $aulasUnicas)) {
                                        $aulasUnicas[] = $horario->aula->id;
                                    }
                                }
                            }
                        @endphp
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Materias</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ $grupo->grupoMaterias->count() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Horarios</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ $totalHorarios }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Docentes</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ count($docentesUnicos) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Aulas</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ count($aulasUnicas) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Materias Asignadas -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-amber-100 shadow-sm mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-bold text-amber-800 flex items-center">
                        <i class="fas fa-book mr-2 text-sm sm:text-base"></i>
                        Materias Asignadas
                    </h4>
                    <a href="{{ route('admin.grupos.asignar-materias', $grupo->id) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 mt-2 sm:mt-0">
                        <i class="fas fa-plus mr-1 sm:mr-2"></i>
                        Asignar Materias
                    </a>
                </div>

                @if($grupo->grupoMaterias->count() > 0)
                    <div class="overflow-x-auto -mx-2 sm:mx-0">
                        <table class="min-w-full divide-y divide-amber-100 text-xs sm:text-sm">
                            <thead class="bg-amber-50">
                                <tr>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-amber-800 uppercase">Sigla</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-amber-800 uppercase">Materia</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-amber-800 uppercase">Semestre</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-amber-800 uppercase">Horarios</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-amber-800 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-50">
                                @foreach($grupo->grupoMaterias as $grupoMateria)
                                <tr class="hover:bg-amber-25 transition-colors duration-150">
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 font-medium text-amber-900">
                                        {{ $grupoMateria->materia->sigla }}
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-amber-800 font-semibold">
                                        {{ \Illuminate\Support\Str::limit($grupoMateria->materia->nombre, 30) }}
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-amber-800">
                                        S{{ $grupoMateria->materia->semestre }}
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-amber-800">
                                        <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">
                                            {{ $grupoMateria->horarios->count() }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3">
                                        <form action="{{ route('admin.grupos.remover-materia', ['idGrupo' => $grupo->id, 'siglaMateria' => $grupoMateria->materia->sigla]) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Está seguro de remover esta materia del grupo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-all duration-200">
                                                <i class="fas fa-times mr-1"></i>
                                                Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-amber-50 rounded-xl">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-book-open text-amber-500 text-lg sm:text-xl"></i>
                        </div>
                        <p class="text-amber-600 font-medium text-sm sm:text-base">No hay materias asignadas</p>
                        <p class="text-amber-500 text-xs sm:text-sm">Asigne materias para comenzar a configurar horarios</p>
                    </div>
                @endif
            </div>

            <!-- Horarios del Grupo -->
            @if($grupo->grupoMaterias->count() > 0)
            <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-cyan-100 shadow-sm">
                <h4 class="text-base sm:text-lg font-bold text-cyan-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-sm sm:text-base"></i>
                    Horarios del Grupo
                </h4>
                
                @php
                    $tieneHorarios = false;
                    foreach($grupo->grupoMaterias as $grupoMateria) {
                        if($grupoMateria->horarios->count() > 0) {
                            $tieneHorarios = true;
                            break;
                        }
                    }
                @endphp

                @if($tieneHorarios)
                    <div class="space-y-4 sm:space-y-6">
                        @foreach($grupo->grupoMaterias as $grupoMateria)
                            @if($grupoMateria->horarios->count() > 0)
                            <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-5 border border-cyan-200 shadow-sm">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 sm:mb-4">
                                    <div class="mb-2 sm:mb-0">
                                        <h5 class="font-bold text-cyan-900 text-sm sm:text-lg flex items-center">
                                            <i class="fas fa-book mr-2 text-cyan-600 text-sm sm:text-base"></i>
                                            {{ $grupoMateria->materia->sigla }} - {{ \Illuminate\Support\Str::limit($grupoMateria->materia->nombre, 25) }}
                                        </h5>
                                    </div>
                                    <span class="px-2 py-1 sm:px-3 sm:py-1 bg-cyan-100 text-cyan-800 rounded-full text-xs font-bold">
                                        {{ $grupoMateria->horarios->count() }} horario(s)
                                    </span>
                                </div>

                                <div class="overflow-x-auto -mx-2 sm:mx-0">
                                    <table class="min-w-full divide-y divide-cyan-100 text-xs sm:text-sm">
                                        <thead class="bg-cyan-50">
                                            <tr>
                                                <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Día</th>
                                                <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Horario</th>
                                                <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Aula</th>
                                                <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Docente</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-cyan-50">
                                            @foreach($grupoMateria->horarios as $horario)
                                            <tr class="hover:bg-cyan-25 transition-colors duration-150">
                                                <td class="px-2 py-2 sm:px-4 sm:py-3 font-medium text-cyan-900">
                                                    {{ \Illuminate\Support\Str::limit($horario->horario->dia, 3) }}
                                                </td>
                                                <td class="px-2 py-2 sm:px-4 sm:py-3 text-cyan-800">
                                                    {{ substr($horario->horario->hora_inicio, 0, 5) }}<br class="sm:hidden">
                                                    <span class="hidden sm:inline">-</span>
                                                    {{ substr($horario->horario->hora_fin, 0, 5) }}
                                                </td>
                                                <td class="px-2 py-2 sm:px-4 sm:py-3 text-cyan-800 font-semibold">
                                                    {{ $horario->aula->nombre ?? 'N/A' }}
                                                </td>
                                                <td class="px-2 py-2 sm:px-4 sm:py-3 text-cyan-800">
                                                    {{ $horario->docente->nombre ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-cyan-50 rounded-xl">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 bg-cyan-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-cyan-500 text-lg sm:text-xl"></i>
                        </div>
                        <p class="text-cyan-600 font-medium text-sm sm:text-base">No hay horarios asignados</p>
                        <p class="text-cyan-500 text-xs sm:text-sm">Configure los horarios desde la gestión de materias</p>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection