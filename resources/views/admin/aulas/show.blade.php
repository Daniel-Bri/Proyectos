@extends('layouts.app')

@section('title', 'Detalles del Aula')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-door-open mr-3"></i>
                        Detalles del Aula
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Información completa del aula: {{ $aula->nombre }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.aulas.edit', $aula) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white border border-transparent rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <a href="{{ route('admin.aulas.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Información Principal -->
                <div class="space-y-6">
                    <!-- Tarjeta de Información Básica -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <h4 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-3 text-[#3CA6A6]"></i>
                            Información Básica
                        </h4>
                        <dl class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-blue-100">
                                <dt class="text-sm font-medium text-deep-teal-700">Código:</dt>
                                <dd class="text-sm font-bold text-deep-teal-800 font-mono">{{ $aula->codigo }}</dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-blue-100">
                                <dt class="text-sm font-medium text-deep-teal-700">Nombre:</dt>
                                <dd class="text-sm font-bold text-deep-teal-800">{{ $aula->nombre }}</dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-blue-100">
                                <dt class="text-sm font-medium text-deep-teal-700">Tipo:</dt>
                                <dd class="text-sm font-bold text-deep-teal-800">{{ $aula->tipo }}</dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-blue-100">
                                <dt class="text-sm font-medium text-deep-teal-700">Capacidad:</dt>
                                <dd class="text-sm font-bold text-deep-teal-800">{{ $aula->capacidad }} personas</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Tarjeta de Estado -->
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-3 text-[#3CA6A6]"></i>
                            Estado del Aula
                        </h4>
                        <div class="text-center">
                            <span class="px-4 py-3 inline-flex items-center text-sm font-bold rounded-xl border shadow-sm text-lg
                                {{ $aula->estado == 'Disponible' ? 'bg-green-100 text-green-800 border-green-200' : 
                                   ($aula->estado == 'En Mantenimiento' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 
                                   'bg-rose-100 text-rose-800 border-rose-200') }}">
                                <i class="fas fa-{{ $aula->estado == 'Disponible' ? 'check-circle' : ($aula->estado == 'En Mantenimiento' ? 'tools' : 'times-circle') }} mr-2"></i>
                                {{ $aula->estado }}
                            </span>
                            <p class="text-sm text-deep-teal-600 mt-3">
                                @if($aula->estado == 'Disponible')
                                    El aula está lista para ser utilizada en actividades académicas.
                                @elseif($aula->estado == 'En Mantenimiento')
                                    El aula se encuentra en proceso de mantenimiento o reparación.
                                @else
                                    El aula no está disponible para uso académico.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="space-y-6">
                    <!-- Tarjeta de Ubicación -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-6 border border-emerald-100">
                        <h4 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-[#3CA6A6]"></i>
                            Ubicación
                        </h4>
                        <div class="flex items-start">
                            <i class="fas fa-building text-deep-teal-500 mt-1 mr-3"></i>
                            <p class="text-sm text-deep-teal-800 leading-relaxed">{{ $aula->ubicacion }}</p>
                        </div>
                    </div>

                    <!-- Tarjeta de Equipamiento -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                        <h4 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                            <i class="fas fa-laptop mr-3 text-[#3CA6A6]"></i>
                            Equipamiento
                        </h4>
                        @if($aula->equipamiento)
                            <div class="bg-white/80 rounded-lg p-4 border border-purple-200">
                                <p class="text-sm text-deep-teal-800 leading-relaxed">{{ $aula->equipamiento }}</p>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle text-deep-teal-400 text-2xl mb-2"></i>
                                <p class="text-sm text-deep-teal-600">No se ha registrado equipamiento para este aula.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tarjeta de Auditoría -->
                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-6 border border-orange-100">
                        <h4 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                            <i class="fas fa-history mr-3 text-[#3CA6A6]"></i>
                            Información de Auditoría
                        </h4>
                        <dl class="space-y-3">
                            <div class="flex justify-between items-center">
                                <dt class="text-sm font-medium text-deep-teal-700">Creado:</dt>
                                <dd class="text-sm text-deep-teal-800">{{ $aula->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm font-medium text-deep-teal-700">Actualizado:</dt>
                                <dd class="text-sm text-deep-teal-800">{{ $aula->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm font-medium text-deep-teal-700">Registro ID:</dt>
                                <dd class="text-sm font-mono text-deep-teal-800">#{{ $aula->id }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="mt-8 pt-6 border-t border-deep-teal-100">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('admin.aulas.edit', $aula) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Aula
                    </a>
                    <form action="{{ route('admin.aulas.destroy', $aula) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('¿Está seguro de eliminar esta aula? Esta acción no se puede deshacer.')"
                                class="inline-flex items-center justify-center px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar Aula
                        </button>
                    </form>
                    <a href="{{ route('admin.aulas.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-deep-teal-200 hover:bg-deep-teal-300 text-deep-teal-700 font-bold rounded-xl transition-all duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Ver Todas las Aulas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection