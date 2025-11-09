@extends('layouts.app')

@section('title', 'Mis Horarios Asignados - Docente')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-clock mr-3"></i>
                    Mis Horarios Asignados
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Gestiona y visualiza todos tus horarios académicos
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                {{-- BOTÓN ELIMINADO: Vista Semanal --}}
                {{-- <a href="{{ route('docente.horarios.semanal') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-calendar-week mr-2"></i>
                    Vista Semanal
                </a> --}}
                
                <a href="{{ route('docente.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Resto del contenido de la vista... -->
    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Aquí va el contenido de los horarios -->
        @if($horarios && $horarios->count() > 0)
            <!-- Lista de horarios -->
            <div class="space-y-4">
                @foreach($horarios as $horario)
                    <div class="bg-white rounded-xl p-4 border border-deep-teal-100 shadow-sm">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="font-bold text-deep-teal-800 text-lg">
                                        {{ $horario->horario->dia ?? 'N/A' }}
                                    </span>
                                    <span class="ml-4 text-deep-teal-600">
                                        {{ \Carbon\Carbon::parse($horario->horario->hora_inicio)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($horario->horario->hora_fin)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="font-semibold text-deep-teal-700">Materia</p>
                                        <p class="text-deep-teal-600">{{ $horario->grupoMateria->materia->nombre ?? 'N/A' }}</p>
                                        <p class="text-deep-teal-500 text-xs">{{ $horario->grupoMateria->materia->sigla ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-deep-teal-700">Grupo</p>
                                        <p class="text-deep-teal-600">{{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-deep-teal-700">Aula</p>
                                        <p class="text-deep-teal-600">{{ $horario->aula->nombre ?? 'N/A' }}</p>
                                        <p class="text-deep-teal-500 text-xs">
                                            @if($horario->horario)
                                                @php
                                                    $inicio = \Carbon\Carbon::parse($horario->horario->hora_inicio);
                                                    $fin = \Carbon\Carbon::parse($horario->horario->hora_fin);
                                                    $duracion = $inicio->diffInHours($fin) + ($inicio->diffInMinutes($fin) % 60) / 60;
                                                @endphp
                                                {{ number_format($duracion, 1) }} horas
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-4">
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-circle mr-1 text-green-500 text-xs"></i>
                                    Activo
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Resumen -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        @php
                            $totalHorarios = $horarios->count();
                            $diasDiferentes = $horarios->groupBy(function($item) {
                                return $item->horario->dia ?? 'N/A';
                            })->count();
                        @endphp
                        <p class="text-blue-800 font-medium">Resumen de horarios</p>
                        <p class="text-blue-600 text-sm">
                            Total {{ $totalHorarios }} horarios asignados | Días: {{ $diasDiferentes }} días diferentes
                        </p>
                    </div>
                </div>
            </div>

        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-orange-500 text-2xl"></i>
                </div>
                <p class="text-orange-700 font-medium text-lg">No hay horarios asignados</p>
                <p class="text-orange-600 text-sm mt-2">No tienes horarios asignados actualmente</p>
            </div>
        @endif
    </div>
</div>
@endsection