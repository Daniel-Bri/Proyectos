@extends('layouts.app')

@section('title', 'Detalles de Bitácora')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-[#3CA6A6] overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#012E40] to-[#026773] px-6 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Detalles del Registro
                    </h3>
                    <p class="mt-2 text-[#F2E3D5] text-sm">
                        Información completa de la acción registrada
                    </p>
                </div>
                <a href="{{ route('admin.bitacora.index') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-[#F2E3D5]/20 hover:bg-[#F2E3D5]/30 text-[#F2E3D5] border border-[#F2E3D5]/30 rounded-xl font-semibold text-sm uppercase tracking-widest transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
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
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">Usuario</dt>
                            <dd class="text-sm text-[#024959] font-semibold">{{ $auditoria->user->name ?? 'Sistema' }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">Acción</dt>
                            <dd class="text-sm text-[#024959] font-semibold">{{ $auditoria->accion }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">Entidad</dt>
                            <dd class="text-sm text-[#024959] font-semibold">{{ $auditoria->entidad }}</dd>
                        </div>
                        @if($auditoria->entidad_id)
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">ID Entidad</dt>
                            <dd class="text-sm text-[#012E40] font-mono bg-white px-3 py-2 rounded-lg border border-[#3CA6A6]">{{ $auditoria->entidad_id }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                
                <!-- Información Técnica -->
                <div class="bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                    <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                        <i class="fas fa-cog mr-2 text-[#026773]"></i>
                        Información Técnica
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">Dirección IP</dt>
                            <dd class="text-sm text-[#012E40] font-mono bg-white px-3 py-2 rounded-lg border border-[#3CA6A6]">{{ $auditoria->ip }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">Navegador/Sistema</dt>
                            <dd class="text-sm text-[#024959]">{{ $auditoria->user_agent }}</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 flex-shrink-0 text-sm font-medium text-[#012E40]">Fecha y Hora</dt>
                            <dd class="text-sm text-[#024959] font-semibold">{{ $auditoria->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Descripción Adicional -->
            @if($auditoria->descripcion)
            <div class="mt-6 bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-[#026773]"></i>
                    Descripción Adicional
                </h4>
                <div class="bg-white rounded-lg p-4 border border-[#3CA6A6]">
                    <p class="text-sm text-[#024959] leading-relaxed">{{ $auditoria->descripcion }}</p>
                </div>
            </div>
            @endif

            <!-- Información del Usuario -->
            @if($auditoria->user)
            <div class="mt-6 bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-[#026773]"></i>
                    Información del Usuario
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#012E40] to-[#026773] rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                            {{ substr($auditoria->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-[#012E40] text-lg">{{ $auditoria->user->name }}</p>
                            <p class="text-sm text-[#024959]">{{ $auditoria->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-start md:justify-end">
                        <div class="text-right">
                            <p class="text-xs text-[#3CA6A6]">Registrado el</p>
                            <p class="text-sm font-medium text-[#012E40]">{{ $auditoria->user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection