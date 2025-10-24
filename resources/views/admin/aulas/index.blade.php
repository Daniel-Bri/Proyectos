@extends('layouts.app')

@section('title', 'Gestión de Aulas')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-door-open mr-3"></i>
                    Gestión de Aulas
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra las aulas disponibles en el sistema
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.aulas.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Aula
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-deep-teal-25 border-b border-deep-teal-100 px-4 py-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-deep-teal-700 mb-1">Código</label>
                <input type="text" name="codigo" value="{{ request('codigo') }}" 
                       class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20">
            </div>
            <div>
                <label class="block text-sm font-medium text-deep-teal-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ request('nombre') }}" 
                       class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20">
            </div>
            <div>
                <label class="block text-sm font-medium text-deep-teal-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20">
                    <option value="">Todos</option>
                    <option value="Teórica" {{ request('tipo') == 'Teórica' ? 'selected' : '' }}>Teórica</option>
                    <option value="Práctica" {{ request('tipo') == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                    <option value="Laboratorio" {{ request('tipo') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                    <option value="Multiusos" {{ request('tipo') == 'Multiusos' ? 'selected' : '' }}>Multiusos</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-deep-teal-700 mb-1">Estado</label>
                <select name="estado" class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20">
                    <option value="">Todos</option>
                    <option value="Disponible" {{ request('estado') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="En Mantenimiento" {{ request('estado') == 'En Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                    <option value="No Disponible" {{ request('estado') == 'No Disponible' ? 'selected' : '' }}>No Disponible</option>
                </select>
            </div>
            <div class="lg:col-span-4 flex justify-end gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-[#026773] hover:bg-[#024959] text-white rounded-xl font-semibold text-sm transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
                <a href="{{ route('admin.aulas.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-deep-teal-200 hover:bg-deep-teal-300 text-deep-teal-700 rounded-xl font-semibold text-sm transition-all duration-200">
                    <i class="fas fa-redo mr-2"></i>
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        @if($aulas->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($aulas as $aula)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-xl flex items-center justify-center text-white text-lg font-bold shadow-md">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-lg">{{ $aula->nombre }}</p>
                                    <p class="text-sm text-deep-teal-600 font-mono">{{ $aula->codigo }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm
                            {{ $aula->estado == 'Disponible' ? 'bg-green-100 text-green-800 border-green-200' : 
                               ($aula->estado == 'En Mantenimiento' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 
                               'bg-rose-100 text-rose-800 border-rose-200') }}">
                            {{ $aula->estado }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Tipo</p>
                            <p class="font-bold text-deep-teal-800">{{ $aula->tipo }}</p>
                        </div>
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Capacidad</p>
                            <p class="font-bold text-deep-teal-800">{{ $aula->capacidad }} personas</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium">Ubicación</p>
                        <p class="text-sm text-deep-teal-800">{{ $aula->ubicacion }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.aulas.edit', $aula) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                            <form action="{{ route('admin.aulas.destroy', $aula) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Está seguro de eliminar esta aula?')"
                                        class="inline-flex items-center px-3 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-trash mr-1"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                        <a href="{{ route('admin.aulas.show', $aula) }}" 
                           class="inline-flex items-center px-3 py-2 bg-deep-teal-200 hover:bg-deep-teal-300 text-deep-teal-700 text-xs font-semibold rounded-xl transition-all duration-200">
                            <i class="fas fa-eye mr-1"></i>
                            Ver
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Código</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Capacidad</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Ubicación</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($aulas as $aula)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-bold text-deep-teal-800 font-mono">{{ $aula->codigo }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-lg flex items-center justify-center text-white mr-3 shadow-md">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div class="text-sm font-bold text-deep-teal-800">{{ $aula->nombre }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-deep-teal-100 text-deep-teal-800">
                                    {{ $aula->tipo }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $aula->capacidad }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-deep-teal-700">
                                {{ $aula->ubicacion }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border shadow-sm
                                    {{ $aula->estado == 'Disponible' ? 'bg-green-100 text-green-800 border-green-200' : 
                                       ($aula->estado == 'En Mantenimiento' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 
                                       'bg-rose-100 text-rose-800 border-rose-200') }}">
                                    <i class="fas fa-{{ $aula->estado == 'Disponible' ? 'check-circle' : ($aula->estado == 'En Mantenimiento' ? 'tools' : 'times-circle') }} mr-1"></i>
                                    {{ $aula->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.aulas.show', $aula) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-deep-teal-200 hover:bg-deep-teal-300 text-deep-teal-700 text-xs font-semibold rounded-xl transition-all duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.aulas.edit', $aula) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.aulas.destroy', $aula) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Está seguro de eliminar esta aula?')"
                                                class="inline-flex items-center px-3 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                            <i class="fas fa-trash mr-1"></i>
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
            <div class="mt-8 flex justify-center">
                <div class="bg-white px-6 py-4 rounded-2xl border border-deep-teal-100 shadow-lg">
                    {{ $aulas->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-door-open text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay aulas registradas</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Comienza agregando la primera aula al sistema.
                </p>
                <a href="{{ route('admin.aulas.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primera Aula
                </a>
            </div>
        @endif
    </div>
</div>
@endsection