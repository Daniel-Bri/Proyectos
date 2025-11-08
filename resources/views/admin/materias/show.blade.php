@extends('layouts.app')

@section('title', 'Detalles de Materia - Admin')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-4">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header Mobile Optimizado -->
        <div class="gradient-bg px-3 py-4 sm:px-6">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-book mr-2 sm:mr-3"></i>
                        {{ $materia->sigla }} - {{ \Illuminate\Support\Str::limit($materia->nombre, 35) }}
                    </h3>
                    <p class="mt-1 sm:mt-2 text-deep-teal-200 text-xs sm:text-sm">
                        Información completa de la materia
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 justify-center sm:justify-end">
                    <a href="{{ route('admin.materias.edit', $materia->sigla) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-amber-500 hover:bg-amber-600 border border-transparent rounded-lg sm:rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-1 sm:mr-2"></i>
                        Editar
                    </a>
                    
                    <!-- BOTÓN PARA ASIGNAR AULAS -->
                    <a href="{{ route('admin.materias.asignar-aulas', $materia->sigla) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-lg sm:rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-door-open mr-1 sm:mr-2"></i>
                        Asignar Aulas
                    </a>
                    
                    <a href="{{ route('admin.materias.index') }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <!-- Grid de Información Principal - Mobile Optimized -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Información Principal -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-blue-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-sm sm:text-base"></i>
                        Información Principal
                    </h4>
                    <dl class="space-y-3 sm:space-y-4">
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-blue-700">Sigla</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-bold sm:text-lg">{{ $materia->sigla }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-blue-700">Nombre</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-semibold">{{ $materia->nombre }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-blue-700">Semestre</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-semibold">
                                <span class="px-2 py-1 sm:px-3 sm:py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                    Semestre {{ $materia->semestre }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Categorización -->
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-emerald-100 shadow-sm">
                    <h4 class="text-base sm:text-lg font-bold text-emerald-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-tags mr-2 text-sm sm:text-base"></i>
                        Categorización
                    </h4>
                    <dl class="space-y-3 sm:space-y-4">
                        <div class="flex items-start">
                            <dt class="w-20 sm:w-32 flex-shrink-0 text-xs sm:text-sm font-medium text-emerald-700">Categoría</dt>
                            <dd class="text-xs sm:text-sm text-gray-900 font-semibold">{{ $materia->categoria->nombre ?? 'N/A' }}</dd>
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
                            $docentesUnicos = [];
                            $aulasUnicas = [];
                            foreach($materia->grupoMaterias as $grupoMateria) {
                                foreach($grupoMateria->horarios as $horario) {
                                    if ($horario->docente && !in_array($horario->docente->codigo, $docentesUnicos)) {
                                        $docentesUnicos[] = $horario->docente->codigo;
                                    }
                                    if ($horario->aula && !in_array($horario->aula->id, $aulasUnicas)) {
                                        $aulasUnicas[] = $horario->aula->id;
                                    }
                                }
                            }
                            $totalHorarios = $materia->grupoMaterias->sum(function($grupo) { 
                                return $grupo->horarios->count(); 
                            });
                        @endphp
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Docentes</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ count($docentesUnicos) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Grupos</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ $materia->grupoMaterias->count() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Horarios</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ $totalHorarios }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs sm:text-sm font-medium text-purple-700">Aulas</dt>
                            <dd class="text-sm sm:text-lg font-bold text-purple-900">{{ count($aulasUnicas) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Estado de Grupos - Mobile Optimized -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-amber-100 shadow-sm mb-6 sm:mb-8">
                <h4 class="text-base sm:text-lg font-bold text-amber-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-door-open mr-2 text-sm sm:text-base"></i>
                    Estado de Aulas
                </h4>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                            {{ $materia->grupoMaterias->count() }}
                        </div>
                        <p class="font-bold text-green-900 text-xs sm:text-sm">Grupos</p>
                        <p class="text-green-700 text-xs">Asignados</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                            {{ $totalHorarios }}
                        </div>
                        <p class="font-bold text-blue-900 text-xs sm:text-sm">Sesiones</p>
                        <p class="text-blue-700 text-xs">Programadas</p>
                    </div>
                    
                    <div class="text-center col-span-2 sm:col-span-1">
                        <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                            {{ count($aulasUnicas) }}
                        </div>
                        <p class="font-bold text-purple-900 text-xs sm:text-sm">Aulas</p>
                        <p class="text-purple-700 text-xs">Utilizadas</p>
                    </div>
                </div>
                
                @if($materia->grupoMaterias->count() == 0)
                <div class="mt-4 sm:mt-6 text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-door-open text-amber-500 text-lg sm:text-xl"></i>
                    </div>
                    <p class="text-amber-600 font-medium text-sm sm:text-base">No hay grupos asignados</p>
                    <p class="text-amber-500 text-xs sm:text-sm mb-3 sm:mb-4">Primero asigna grupos a esta materia</p>
                    <a href="#" 
                       class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-xs sm:text-sm">
                        <i class="fas fa-users mr-1 sm:mr-2"></i>
                        Asignar Grupos
                    </a>
                </div>
                @endif
            </div>

            <!-- Docentes en Horarios - Mobile Optimized -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-amber-100 shadow-sm mb-6 sm:mb-8">
                <h4 class="text-base sm:text-lg font-bold text-amber-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-user-tie mr-2 text-sm sm:text-base"></i>
                    Docentes Asignados
                </h4>
                
                @if($materia->grupoMaterias->count() > 0 && count($docentesUnicos) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @php
                            $docentesMostrados = [];
                        @endphp
                        @foreach($materia->grupoMaterias as $grupoMateria)
                            @foreach($grupoMateria->horarios as $horario)
                                @if($horario->docente && !in_array($horario->docente->codigo, $docentesMostrados))
                                    @php
                                        $docentesMostrados[] = $horario->docente->codigo;
                                        $totalHorariosDocente = 0;
                                        foreach($materia->grupoMaterias as $gm) {
                                            $totalHorariosDocente += $gm->horarios->where('codigo_docente', $horario->docente->codigo)->count();
                                        }
                                    @endphp
                                    <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 border border-amber-200 shadow-sm hover:shadow-md transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-12 sm:h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-lg mr-3 sm:mr-4">
                                                {{ substr($horario->docente->nombre, 0, 1) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-amber-900 text-xs sm:text-sm truncate">{{ $horario->docente->nombre }}</p>
                                                <p class="text-amber-700 text-xs">{{ $horario->docente->codigo }}</p>
                                                <p class="text-amber-600 text-xs mt-1">
                                                    {{ $totalHorariosDocente }} horario(s)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 sm:py-8">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-clock text-amber-500 text-lg sm:text-xl"></i>
                        </div>
                        <p class="text-amber-600 font-medium text-sm sm:text-base">No hay docentes asignados</p>
                        <p class="text-amber-500 text-xs sm:text-sm">Los docentes se asignan con las aulas</p>
                    </div>
                @endif
            </div>

            <!-- Grupos y Aulas Asignadas -->
            @if($materia->grupoMaterias->count() > 0)
            <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-cyan-100 shadow-sm">
                <h4 class="text-base sm:text-lg font-bold text-cyan-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-door-open mr-2 text-sm sm:text-base"></i>
                    Grupos y Aulas Asignadas
                </h4>
                <div class="space-y-4 sm:space-y-6">
                    @foreach($materia->grupoMaterias as $grupoMateria)
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-5 border border-cyan-200 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 sm:mb-4">
                            <div class="mb-2 sm:mb-0">
                                <h5 class="font-bold text-cyan-900 text-sm sm:text-lg flex items-center">
                                    <i class="fas fa-users mr-2 text-cyan-600 text-sm sm:text-base"></i>
                                    Grupo: {{ $grupoMateria->grupo->nombre }}
                                </h5>
                                <p class="text-cyan-700 text-xs sm:text-sm">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $grupoMateria->gestion->gestion }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 sm:px-3 sm:py-1 bg-cyan-100 text-cyan-800 rounded-full text-xs font-bold">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $grupoMateria->horarios->count() }}
                                </span>
                                <span class="px-2 py-1 sm:px-3 sm:py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold">
                                    <i class="fas fa-door-open mr-1"></i>
                                    {{ $grupoMateria->horarios->unique('id_aula')->count() }}
                                </span>
                            </div>
                        </div>

                        @if($grupoMateria->horarios->count() > 0)
                        <div class="overflow-x-auto -mx-2 sm:mx-0">
                            <table class="min-w-full divide-y divide-cyan-100 text-xs sm:text-sm">
                                <thead class="bg-cyan-50">
                                    <tr>
                                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Día</th>
                                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Horario</th>
                                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Aula</th>
                                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left font-bold text-cyan-800 uppercase">Cap.</th>
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
                                            {{ $horario->aula->capacidad ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-3 sm:py-4 bg-cyan-25 rounded-lg">
                            <p class="text-cyan-600 text-xs sm:text-sm">
                                <i class="fas fa-door-open mr-1"></i>
                                No hay aulas asignadas
                            </p>
                            <a href="{{ route('admin.materias.asignar-aulas', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-1 sm:px-4 sm:py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-xs font-bold rounded-lg mt-2 transition-all duration-200">
                                <i class="fas fa-plus mr-1"></i>
                                Asignar Aulas
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-gray-200 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 bg-gradient-to-br from-gray-200 to-blue-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-door-open text-gray-500 text-xl sm:text-2xl"></i>
                </div>
                <h4 class="text-lg sm:text-xl font-bold text-gray-700 mb-2">No hay grupos asignados</h4>
                <p class="text-gray-600 text-sm sm:text-base mb-4">Esta materia no tiene grupos asignados actualmente.</p>
                <a href="#" 
                   class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-xs sm:text-sm">
                    <i class="fas fa-users mr-1 sm:mr-2"></i>
                    Asignar Grupos Primero
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection