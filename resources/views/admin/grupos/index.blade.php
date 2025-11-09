@extends('layouts.app')

@section('title', 'Gestión de Grupos - Admin')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-users mr-3"></i>
                    Gestión de Grupos
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra todos los grupos académicos del sistema
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.grupos.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Grupo
                </a>
                <a href="{{ route('admin.grupos.export') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#024959] hover:bg-[#012E40] border border-transparent rounded-xl font-semibold text-xs text-[#F2E3D5] uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar Excel
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        @if($grupos->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($grupos as $grupo)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ substr($grupo->nombre, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-sm">{{ $grupo->nombre }}</p>
                                    <p class="text-xs text-deep-teal-600">Gestión: {{ $grupo->gestion }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm bg-green-100 text-green-800 border-green-200">
                            Activo
                        </span>
                    </div>
                    
                    <div class="text-sm mb-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-deep-teal-600">Materias:</span>
                            <span class="font-bold text-deep-teal-800">{{ $grupo->grupoMaterias->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-deep-teal-600">Creado:</span>
                            <span class="font-bold text-deep-teal-800">{{ $grupo->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $grupo->created_at->format('d/m/Y') }}
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.grupos.show', $grupo->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                Ver
                            </a>
                            <a href="{{ route('admin.grupos.edit', $grupo->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-2xl border border-deep-teal-100 shadow-lg">
                <table class="min-w-full divide-y divide-deep-teal-100">
                    <thead class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Gestión</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Materias</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($grupos as $grupo)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        {{ substr($grupo->nombre, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800">
                                            {{ $grupo->nombre }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600">
                                            ID: {{ $grupo->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $grupo->gestion }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg 
                                    {{ $grupo->grupoMaterias->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $grupo->grupoMaterias->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.grupos.show', $grupo->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.grupos.edit', $grupo->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar
                                    </a>
                                    <a href="{{ route('admin.grupos.asignar-materias', $grupo->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-book mr-2"></i>
                                        Materias
                                    </a>
                                    <form action="{{ route('admin.grupos.destroy', $grupo->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Está seguro de eliminar este grupo?')"
                                                class="inline-flex items-center px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                            <i class="fas fa-trash mr-2"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $grupos->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay grupos registrados</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Comienza agregando el primer grupo al sistema académico.
                </p>
                <a href="{{ route('admin.grupos.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Grupo
                </a>
            </div>
        @endif
    </div>
</div>
@endsection