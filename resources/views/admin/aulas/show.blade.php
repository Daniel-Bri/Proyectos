@extends('layouts.app')

@section('title', 'Detalles del Aula: ' . $aula->nombre)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-[#3CA6A6] overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#012E40] to-[#026773] px-6 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-building mr-3"></i>
                        Detalles del Aula
                    </h3>
                    <p class="mt-2 text-[#F2E3D5] text-sm">
                        Información completa del aula {{ $aula->nombre }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.aulas.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-[#F2E3D5]/20 hover:bg-[#F2E3D5]/30 text-[#F2E3D5] border border-[#F2E3D5]/30 rounded-xl font-semibold text-sm uppercase tracking-widest transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    @can('editar-aulas')
                    <a href="{{ route('admin.aulas.edit', $aula) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-[#3CA6A6] hover:bg-[#026773] text-white border border-[#3CA6A6] rounded-xl font-semibold text-sm uppercase tracking-widest transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-6 bg-[#F2E3D5]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Básica -->
                <div class="bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                    <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-[#026773]"></i>
                        Información Básica
                    </h4>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-[#012E40]">Nombre</dt>
                            <dd class="mt-1 text-lg text-[#024959]">{{ $aula->nombre }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#012E40]">Tipo</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $aula->tipo == 'laboratorio' ? 'bg-[#026773] text-white' : 
                                       ($aula->tipo == 'biblioteca' ? 'bg-[#3CA6A6] text-[#012E40]' : 
                                       ($aula->tipo == 'auditorio' ? 'bg-[#024959] text-white' : 
                                       'bg-[#012E40] text-white')) }}">
                                    <i class="fas 
                                        {{ $aula->tipo == 'laboratorio' ? 'fa-flask' : 
                                           ($aula->tipo == 'biblioteca' ? 'fa-book' : 
                                           ($aula->tipo == 'auditorio' ? 'fa-theater-masks' : 'fa-chalkboard')) }} 
                                        mr-2"></i>
                                    {{ ucfirst($aula->tipo) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Capacidad y Estado -->
                <div class="bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                    <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-[#026773]"></i>
                        Capacidad y Estado
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="bg-white rounded-xl p-4 border border-[#3CA6A6] shadow-sm">
                                <div class="text-3xl font-bold text-[#012E40]">{{ $aula->capacidad }}</div>
                                <div class="text-sm font-medium text-[#024959] mt-1">Capacidad</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="bg-white rounded-xl p-4 border border-[#3CA6A6] shadow-sm">
                                <div class="text-lg font-bold 
                                    {{ $aula->estado == 'Disponible' ? 'text-green-600' : 
                                       ($aula->estado == 'En Mantenimiento' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $aula->estado }}
                                </div>
                                <div class="text-sm font-medium text-[#024959] mt-1">Estado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- Información Adicional -->
            <div class="mt-6 bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-[#026773]"></i>
                    Información Adicional
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-sm font-semibold text-[#012E40] mb-2">Fecha de Creación</h5>
                        <p class="text-[#024959]">{{ $aula->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-[#012E40] mb-2">Última Actualización</h5>
                        <p class="text-[#024959]">{{ $aula->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Horarios Asignados (si existen) -->
            @if($aula->grupoMateriaHorarios && $aula->grupoMateriaHorarios->count() > 0)
            <div class="mt-6 bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-[#026773]"></i>
                    Horarios Asignados ({{ $aula->grupoMateriaHorarios->count() }})
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($aula->grupoMateriaHorarios as $horario)
                    <div class="bg-white border border-[#3CA6A6] rounded-xl p-4 hover:shadow-md transition-all duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-[#012E40]">
                                {{ $horario->grupoMateria->materia->nombre ?? 'Materia no asignada' }}
                            </span>
                            <span class="text-xs bg-[#3CA6A6] text-white px-2 py-1 rounded-full">
                                {{ $horario->horario->dia ?? 'Sin día' }}
                            </span>
                        </div>
                        <div class="text-sm text-[#024959]">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $horario->horario->hora_inicio ?? '' }} - {{ $horario->horario->hora_fin ?? '' }}
                        </div>
                        <div class="text-xs text-[#3CA6A6] mt-2">
                            Grupo: {{ $horario->grupoMateria->grupo->nombre ?? 'No asignado' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mt-6 bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-[#026773]"></i>
                    Horarios Asignados
                </h4>
                <div class="text-center py-6">
                    <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center border border-[#3CA6A6]">
                        <i class="fas fa-clock text-[#3CA6A6] text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[#012E40] mb-2">Sin horarios asignados</h3>
                    <p class="text-[#024959]">Esta aula no tiene horarios asignados actualmente.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection