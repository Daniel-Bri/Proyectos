@extends('layouts.app')

@section('title', 'Detalles de Bitácora')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">
                        📋 Detalles del Registro
                    </h3>
                    <p class="mt-1 text-indigo-100 text-sm">
                        Información completa de la acción registrada
                    </p>
                </div>
                <a href="{{ route('admin.bitacora.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Básica -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                    <h4 class="text-sm font-semibold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información Básica
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">Usuario</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $auditoria->user->name ?? 'Sistema' }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">Acción</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $auditoria->accion }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">Entidad</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $auditoria->entidad }}</dd>
                        </div>
                        @if($auditoria->entidad_id)
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-blue-700">ID Entidad</dt>
                            <dd class="text-sm text-gray-900 font-mono bg-white px-2 py-1 rounded border">{{ $auditoria->entidad_id }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                
                <!-- Información Técnica -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-5 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-cog mr-2"></i>
                        Información Técnica
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-gray-600">Dirección IP</dt>
                            <dd class="text-sm text-gray-900 font-mono bg-white px-2 py-1 rounded border">{{ $auditoria->ip }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-gray-600">Navegador/Sistema</dt>
                            <dd class="text-sm text-gray-900">{{ $auditoria->user_agent }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-gray-600">Fecha y Hora</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $auditoria->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Descripción Adicional -->
            @if($auditoria->descripcion)
            <div class="mt-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 border border-emerald-100">
                <h4 class="text-sm font-semibold text-emerald-800 mb-3 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Descripción Adicional
                </h4>
                <div class="bg-white/80 rounded-lg p-4 border border-emerald-200">
                    <p class="text-sm text-gray-800 leading-relaxed">{{ $auditoria->descripcion }}</p>
                </div>
            </div>
            @endif

            <!-- Información del Usuario -->
            @if($auditoria->user)
            <div class="mt-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-100">
                <h4 class="text-sm font-semibold text-purple-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    Información del Usuario
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            {{ substr($auditoria->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $auditoria->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $auditoria->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-start md:justify-end">
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Registrado el</p>
                            <p class="text-sm font-medium text-gray-900">{{ $auditoria->user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection