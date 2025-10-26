@extends('layouts.app')

@section('title', 'Detalles del Docente')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-user-graduate mr-3"></i>
                        Detalles del Docente: {{ $docente->user->name }}
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Información completa del docente
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('docentes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    @can('admin')
                    <a href="{{ route('docentes.edit', $docente->codigo) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white border border-transparent rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Personal -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                    <h4 class="text-lg font-bold text-blue-800 mb-6 flex items-center">
                        <i class="fas fa-user-circle mr-3"></i>
                        Información Personal
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-blue-100">
                            <dt class="text-sm font-medium text-blue-700">Código</dt>
                            <dd class="text-sm font-bold text-blue-900">{{ $docente->codigo }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-blue-100">
                            <dt class="text-sm font-medium text-blue-700">Nombre</dt>
                            <dd class="text-sm font-bold text-blue-900">{{ $docente->user->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-blue-100">
                            <dt class="text-sm font-medium text-blue-700">Email</dt>
                            <dd class="text-sm font-bold text-blue-900">{{ $docente->user->email }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-sm font-medium text-blue-700">Teléfono</dt>
                            <dd class="text-sm font-bold text-blue-900">{{ $docente->telefono }}</dd>
                        </div>
                    </dl>
                </div>
                
                <!-- Información Laboral -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                    <h4 class="text-lg font-bold text-green-800 mb-6 flex items-center">
                        <i class="fas fa-briefcase mr-3"></i>
                        Información Laboral
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-green-100">
                            <dt class="text-sm font-medium text-green-700">Sueldo</dt>
                            <dd class="text-sm font-bold text-green-900">${{ number_format($docente->sueldo, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-green-100">
                            <dt class="text-sm font-medium text-green-700">Fecha de Contrato</dt>
                            <dd class="text-sm font-bold text-green-900">
                                {{ \Carbon\Carbon::parse($docente->fecha_contrato)->format('d/m/Y') }}
                            </dd>   
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-sm font-medium text-green-700">Fecha Final</dt>
                            <dd class="text-sm font-bold text-green-900">
                                {{ \Carbon\Carbon::parse($docente->fecha_final)->format('d/m/Y') }}
                            </dd>   
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Carreras -->
            <div class="mt-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 shadow-sm">
                <h4 class="text-lg font-bold text-purple-800 mb-6 flex items-center">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Carreras Asignadas
                </h4>
                @if($docente->carreras->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($docente->carreras as $carrera)
                            <div class="bg-white rounded-xl p-4 border border-purple-200 shadow-sm hover:shadow-md transition-all duration-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ substr($carrera->nombre, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-purple-900 text-sm">{{ $carrera->nombre }}</p>
                                        <p class="text-xs text-purple-600">Carrera</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-orange-500 text-xl"></i>
                        </div>
                        <p class="text-orange-700 font-medium">Sin carreras asignadas😊</p>
                        <p class="text-orange-600 text-sm mt-1">Este docente no tiene carreras asignadas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection