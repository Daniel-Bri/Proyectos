@extends('layouts.app')

@section('title', 'Bitácora del Sistema')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    Bitácora del Sistema
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Registro de actividades y auditoría del sistema
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.bitacora.exportar') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-file-export mr-2"></i>
                    Exportar
                </a>
                <form action="{{ route('admin.bitacora.limpiar') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('¿Está seguro de limpiar registros antiguos?')"
                            class="inline-flex items-center px-4 py-2 bg-[#024959] hover:bg-[#012E40] border border-transparent rounded-xl font-semibold text-xs text-[#F2E3D5] uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-broom mr-2"></i>
                        Limpiar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        @if($auditorias->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($auditorias as $auditoria)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ substr($auditoria->user->name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-sm">{{ $auditoria->user->name ?? 'Sistema' }}</p>
                                    <p class="text-xs text-deep-teal-600">{{ $auditoria->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm
                            {{ $auditoria->accion == 'Inicio de sesión' ? 'bg-green-100 text-green-800 border-green-200' : 
                               ($auditoria->accion == 'Cierre de sesión' ? 'bg-rose-100 text-rose-800 border-rose-200' : 
                               'bg-[#3CA6A6] bg-opacity-20 text-[#026773] border-[#3CA6A6] border-opacity-30') }}">
                            {{ $auditoria->accion }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Entidad</p>
                            <p class="font-bold text-deep-teal-800">{{ $auditoria->entidad }}</p>
                        </div>
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">IP</p>
                            <p class="font-mono text-deep-teal-800 text-xs bg-deep-teal-50 px-2 py-1 rounded-lg">{{ $auditoria->ip }}</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="far fa-clock mr-1"></i>
                            {{ $auditoria->created_at->format('d/m/Y H:i') }}
                        </span>
                        <a href="{{ route('admin.bitacora.show', $auditoria->id) }}" 
                           class="inline-flex items-center px-3 py-2 bg-[#026773] hover:bg-[#024959] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Detalles
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-2xl border border-deep-teal-100 shadow-lg">
                <table class="min-w-full divide-y divide-deep-teal-100">
                    <thead class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acción</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Entidad</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">IP</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($auditorias as $auditoria)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        {{ substr($auditoria->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800">
                                            {{ $auditoria->user->name ?? 'Sistema' }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600">
                                            {{ $auditoria->user->email ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-xl border shadow-sm
                                    {{ $auditoria->accion == 'Inicio de sesión' ? 'bg-green-100 text-green-800 border-green-200' : 
                                       ($auditoria->accion == 'Cierre de sesión' ? 'bg-rose-100 text-rose-800 border-rose-200' : 
                                       'bg-[#3CA6A6] bg-opacity-20 text-[#026773] border-[#3CA6A6] border-opacity-30') }}">
                                    <i class="fas fa-{{ $auditoria->accion == 'Inicio de sesión' ? 'sign-in-alt' : ($auditoria->accion == 'Cierre de sesión' ? 'sign-out-alt' : 'exchange-alt') }} mr-2"></i>
                                    {{ $auditoria->accion }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $auditoria->entidad }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-deep-teal-700 font-mono bg-deep-teal-25 px-3 py-2 rounded-lg">
                                {{ $auditoria->ip }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-deep-teal-700 font-medium">
                                <i class="far fa-clock mr-2 text-deep-teal-500"></i>
                                {{ $auditoria->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.bitacora.show', $auditoria->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-search mr-2"></i>
                                    Detalles
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                <div class="bg-white px-6 py-4 rounded-2xl border border-deep-teal-100 shadow-lg">
                    {{ $auditorias->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-clipboard-list text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay registros</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg">
                    No se han encontrado registros en la bitácora del sistema.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection