@extends('layouts.app')

@section('title', 'Detalles de Materia - Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-book mr-3"></i>
                        {{ $materia->sigla }} - {{ $materia->nombre }}
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Información completa y detallada de la materia
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.materias.edit', $materia->sigla) }}" 
                       class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <a href="{{ route('admin.materias.asignar-grupo', $materia->sigla) }}" 
                       class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-users mr-2"></i>
                        Asignar Grupo
                    </a>
                    <a href="{{ route('admin.materias.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Información Principal -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                    <h4 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información Principal
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">Sigla</dt>
                            <dd class="text-sm text-gray-900 font-bold text-lg">{{ $materia->sigla }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">Nombre</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $materia->nombre }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">Semestre</dt>
                            <dd class="text-sm text-gray-900 font-semibold">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                    Semestre {{ $materia->semestre }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Categorización -->
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-100 shadow-sm">
                    <h4 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
                        <i class="fas fa-tags mr-2"></i>
                        Categorización
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-emerald-700">Categoría</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $materia->categoria->nombre ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Estadísticas -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-sm">
                    <h4 class="text-lg font-bold text-purple-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Estadísticas
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-purple-700">Docentes en Horarios</dt>
                            <dd class="text-lg font-bold text-purple-900">
                                @php
                                    $docentesUnicos = [];
                                    foreach($materia->grupoMaterias as $grupoMateria) {
                                        foreach($grupoMateria->horarios as $horario) {
                                            if ($horario->docente && !in_array($horario->docente->codigo, $docentesUnicos)) {
                                                $docentesUnicos[] = $horario->docente->codigo;
                                            }
                                        }
                                    }
                                @endphp
                                {{ count($docentesUnicos) }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-purple-700">Grupos Activos</dt>
                            <dd class="text-lg font-bold text-purple-900">{{ $materia->grupoMaterias->count() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-purple-700">Horarios Totales</dt>
                            <dd class="text-lg font-bold text-purple-900">
                                {{ $materia->grupoMaterias->sum(function($grupo) { return $grupo->horarios->count(); }) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Docentes en Horarios -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100 shadow-sm mb-8">
                <h4 class="text-lg font-bold text-amber-800 mb-4 flex items-center">
                    <i class="fas fa-user-tie mr-2"></i>
                    Docentes en Horarios
                </h4>
                
                @if($materia->grupoMaterias->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
                                    <div class="bg-white rounded-xl p-4 border border-amber-200 shadow-sm hover:shadow-md transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                                {{ substr($horario->docente->nombre, 0, 1) }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-bold text-amber-900">{{ $horario->docente->nombre }}</p>
                                                <p class="text-sm text-amber-700">{{ $horario->docente->codigo }}</p>
                                                <p class="text-xs text-amber-600 mt-1">
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
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-clock text-amber-500 text-xl"></i>
                        </div>
                        <p class="text-amber-600 font-medium">No hay docentes asignados</p>
                        <p class="text-amber-500 text-sm">Los docentes se asignan cuando se crean horarios</p>
                    </div>
                @endif
            </div>

            <!-- Grupos y Horarios -->
            @if($materia->grupoMaterias->count() > 0)
            <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100 shadow-sm">
                <h4 class="text-lg font-bold text-cyan-800 mb-4 flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    Grupos y Horarios Asignados
                </h4>
                <div class="space-y-6">
                    @foreach($materia->grupoMaterias as $grupoMateria)
                    <div class="bg-white rounded-xl p-5 border border-cyan-200 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                            <div>
                                <h5 class="font-bold text-cyan-900 text-lg">
                                    Grupo: {{ $grupoMateria->grupo->nombre }}
                                </h5>
                                <p class="text-cyan-700 text-sm">
                                    Gestión: {{ $grupoMateria->gestion->gestion }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-cyan-100 text-cyan-800 rounded-full text-xs font-bold mt-2 sm:mt-0">
                                {{ $grupoMateria->horarios->count() }} horarios
                            </span>
                        </div>

                        @if($grupoMateria->horarios->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-cyan-100">
                                <thead class="bg-cyan-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Día</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Horario</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Docente</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Aula</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-cyan-50">
                                    @foreach($grupoMateria->horarios as $horario)
                                    <tr class="hover:bg-cyan-25 transition-colors duration-150">
                                        <td class="px-4 py-3 text-sm font-medium text-cyan-900">
                                            {{ $horario->horario->dia }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-cyan-800">
                                            {{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-cyan-800">
                                            {{ $horario->docente->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-cyan-800">
                                            {{ $horario->aula->nombre ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-cyan-600 text-sm text-center py-4">
                            No hay horarios asignados para este grupo
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8 border border-gray-200 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-200 to-blue-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-gray-500 text-2xl"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-700 mb-2">No hay grupos asignados</h4>
                <p class="text-gray-600 mb-4">Esta materia no tiene grupos asignados actualmente.</p>
                <a href="{{ route('admin.materias.asignar-grupo', $materia->sigla) }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-users mr-2"></i>
                    Asignar Primer Grupo
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection