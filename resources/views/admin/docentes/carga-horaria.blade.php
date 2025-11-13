@extends('layouts.app')

@section('title', 'Carga Horaria - ' . $docente->user->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-clock mr-3"></i>
                        Carga Horaria - {{ $docente->user->name }}
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Resumen de horas asignadas por materia y grupos
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('docentes.show', $docente->codigo) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Docente
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Resumen de Carga Horaria -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-medium">Total Horas/Semana</p>
                            <p class="text-3xl font-bold text-blue-800">{{ $totalHorasSemana }}h</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ min(($totalHorasSemana / 40) * 100, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">
                            {{ $totalHorasSemana }}/40 horas utilizadas
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-medium">Materias Asignadas</p>
                            <p class="text-3xl font-bold text-green-800">{{ count($cargaPorMateria) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-600 text-sm font-medium">Grupos Asignados</p>
                            <p class="text-3xl font-bold text-purple-800">{{ $gruposAsignados->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen por Materia - Ahora ocupa todo el ancho -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                <h4 class="text-lg font-bold text-green-800 mb-6 flex items-center">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Resumen de Carga Horaria por Materia
                </h4>
                
                @if(count($cargaPorMateria) > 0)
                    <div class="space-y-6">
                        @foreach($cargaPorMateria as $carga)
                        <div class="bg-white rounded-xl p-6 border border-green-200 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h5 class="font-bold text-green-900 text-lg">{{ $carga['materia']->nombre ?? 'Materia no disponible' }}</h5>
                                    <p class="text-sm text-green-600">{{ $carga['materia']->sigla ?? 'N/A' }} - {{ $carga['gestion']->semestre ?? 'Gestión no disponible' }}</p>
                                </div>
                                <span class="px-4 py-2 bg-green-100 text-green-800 text-lg font-bold rounded-full">
                                    {{ $carga['horas_semana'] }}h/semana
                                </span>
                            </div>
                            
                            <div class="text-sm text-green-800">
                                <p class="font-semibold mb-3">Grupos asignados: {{ count($carga['grupos']) }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($carga['grupos'] as $grupo)
                                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                            <div class="mb-3">
                                                <p class="font-medium text-green-900">Grupo: {{ $grupo->grupo->nombre ?? 'No asignado' }}</p>
                                                <p class="text-green-700">Aula: {{ $grupo->aula->nombre ?? 'No asignada' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                <p class="text-xs font-semibold text-green-800">Horarios:</p>
                                                @foreach($grupo->horarios as $horarioAsignado)
                                                    @if($horarioAsignado->horario)
                                                    <div class="flex items-center justify-between bg-white px-3 py-2 rounded border border-green-200">
                                                        <span class="text-green-700 font-medium">
                                                            {{ $horarioAsignado->horario->dia ?? 'Día no asignado' }}
                                                        </span>
                                                        <span class="text-green-600 text-sm">
                                                            {{ $horarioAsignado->horario->hora_inicio ? \Carbon\Carbon::parse($horarioAsignado->horario->hora_inicio)->format('H:i') : '--:--' }} - 
                                                            {{ $horarioAsignado->horario->hora_fin ? \Carbon\Carbon::parse($horarioAsignado->horario->hora_fin)->format('H:i') : '--:--' }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 mx-auto mb-4 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-orange-500 text-2xl"></i>
                        </div>
                        <p class="text-orange-700 font-medium text-lg">No hay carga horaria asignada</p>
                        <p class="text-orange-600 text-sm mt-2">El docente no tiene grupos ni horarios asignados</p>
                    </div>
                @endif
            </div>

            <!-- Información adicional del docente -->
            <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                <h4 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Información del Docente
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-blue-700"><strong>Código:</strong> {{ $docente->codigo }}</p>
                        <p class="text-sm text-blue-700"><strong>Email:</strong> {{ $docente->user->email }}</p>
                        <p class="text-sm text-blue-700"><strong>Teléfono:</strong> {{ $docente->telefono }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-700"><strong>Carreras:</strong> 
                            @if($docente->carreras->count() > 0)
                                {{ $docente->carreras->pluck('nombre')->implode(', ') }}
                            @else
                                No asignadas
                            @endif
                        </p>
                        <p class="text-sm text-blue-700"><strong>Contrato:</strong> {{ $docente->fecha_contrato }} a {{ $docente->fecha_final }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection