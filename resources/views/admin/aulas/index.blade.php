@extends('layouts.app')

@section('title', 'Gestión de Aulas')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-[#3CA6A6] overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#012E40] to-[#026773] px-6 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-white">
                    <i class="fas fa-building mr-3"></i>
                    Gestión de Aulas
                </h3>
                <p class="mt-2 text-[#F2E3D5] text-sm">
                    Administra las aulas y espacios académicos
                </p>
            </div>
            <a href="{{ route('admin.aulas.create') }}" 
               class="inline-flex items-center px-5 py-3 bg-[#F2E3D5] text-[#012E40] hover:bg-[#3CA6A6] hover:text-white border border-transparent rounded-xl font-semibold text-sm uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Nueva Aula
            </a>
        </div>
    </div>

    <div class="p-6 bg-[#F2E3D5]">
        <!-- Filtros de búsqueda -->
        <div class="bg-white rounded-xl p-5 border border-[#3CA6A6] shadow-sm mb-6">
            <form action="{{ route('admin.aulas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-[#012E40] mb-2">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" 
                           class="w-full border border-[#3CA6A6] rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white"
                           placeholder="Buscar por nombre...">
                </div>
                <div>
                    <label for="tipo" class="block text-sm font-medium text-[#012E40] mb-2">Tipo</label>
                    <select name="tipo" id="tipo" class="w-full border border-[#3CA6A6] rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white">
                        <option value="">Todos los tipos</option>
                        <option value="aula" {{ request('tipo') == 'aula' ? 'selected' : '' }}>Aula</option>
                        <option value="laboratorio" {{ request('tipo') == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                        <option value="biblioteca" {{ request('tipo') == 'biblioteca' ? 'selected' : '' }}>Biblioteca</option>
                        <option value="auditorio" {{ request('tipo') == 'auditorio' ? 'selected' : '' }}>Auditorio</option>
                        <option value="otros" {{ request('tipo') == 'otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>
                <div>
                    <label for="capacidad" class="block text-sm font-medium text-[#012E40] mb-2">Capacidad Mínima</label>
                    <input type="number" name="capacidad" id="capacidad" value="{{ request('capacidad') }}"
                           class="w-full border border-[#3CA6A6] rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white"
                           placeholder="Capacidad mínima...">
                </div>
                <div class="flex items-end space-x-3">
                    <button type="submit" class="bg-[#024959] text-white px-6 py-3 rounded-lg hover:bg-[#012E40] transition-all duration-200 shadow hover:shadow-md flex items-center">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </button>
                    <a href="{{ route('admin.aulas.index') }}" class="bg-[#3CA6A6] text-white px-6 py-3 rounded-lg hover:bg-[#026773] transition-all duration-200 shadow hover:shadow-md flex items-center">
                        <i class="fas fa-undo mr-2"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>

        @if($aulas->count() > 0)
            <!-- Desktop Table -->
            <div class="bg-white rounded-xl border border-[#3CA6A6] shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#3CA6A6]">
                        <thead class="bg-[#012E40]">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#F2E3D5] uppercase tracking-wider">NOMBRE</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#F2E3D5] uppercase tracking-wider">TIPO</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#F2E3D5] uppercase tracking-wider">CAPACIDAD</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#F2E3D5] uppercase tracking-wider">ESTADO</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#F2E3D5] uppercase tracking-wider">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#3CA6A6]">
                            @foreach($aulas as $aula)
                            <tr class="hover:bg-[#F2E3D5] transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-[#012E40] to-[#026773] rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                                            <i class="fas fa-door-open"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-[#012E40]">
                                                {{ $aula->nombre }}
                                            </div>
                                            <div class="text-xs text-[#024959]">
                                                {{ $aula->codigo }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $aula->tipo == 'laboratorio' ? 'bg-[#026773] text-white' : 
                                           ($aula->tipo == 'biblioteca' ? 'bg-[#3CA6A6] text-[#012E40]' : 
                                           ($aula->tipo == 'auditorio' ? 'bg-[#024959] text-white' : 
                                           'bg-[#012E40] text-white')) }}">
                                        <i class="fas 
                                            {{ $aula->tipo == 'laboratorio' ? 'fa-flask' : 
                                               ($aula->tipo == 'biblioteca' ? 'fa-book' : 
                                               ($aula->tipo == 'auditorio' ? 'fa-theater-masks' : 'fa-chalkboard')) }} 
                                            mr-1"></i>
                                        {{ ucfirst($aula->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-[#3CA6A6] mr-2"></i>
                                        <span class="text-sm font-medium text-[#012E40]">{{ $aula->capacidad }}</span>
                                        <span class="text-xs text-[#024959] ml-1">estudiantes</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $aula->estado == 'Disponible' ? 'bg-green-100 text-green-800' : 
                                           ($aula->estado == 'En Mantenimiento' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        <i class="fas 
                                            {{ $aula->estado == 'Disponible' ? 'fa-check-circle' : 
                                               ($aula->estado == 'En Mantenimiento' ? 'fa-tools' : 'fa-times-circle') }} 
                                            mr-1"></i>
                                        {{ $aula->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.aulas.show', $aula) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm rounded-lg transition-all duration-200 transform hover:scale-105 shadow hover:shadow-md">
                                        <i class="fas fa-eye mr-2"></i>Ver
                                    </a>
                                    <a href="{{ route('admin.aulas.edit', $aula) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#024959] hover:bg-[#012E40] text-white text-sm rounded-lg transition-all duration-200 transform hover:scale-105 shadow hover:shadow-md">
                                        <i class="fas fa-edit mr-2"></i>Editar
                                    </a>
                                    <form action="{{ route('admin.aulas.destroy', $aula) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Está seguro de eliminar esta aula?')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-all duration-200 transform hover:scale-105 shadow hover:shadow-md">
                                            <i class="fas fa-trash mr-2"></i>Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $aulas->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-white rounded-full flex items-center justify-center shadow-inner border border-[#3CA6A6]">
                    <i class="fas fa-building text-[#3CA6A6] text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-[#012E40] mb-3">No hay aulas registradas</h3>
                <p class="text-[#024959] mb-6 max-w-md mx-auto">No se han encontrado aulas que coincidan con los criterios de búsqueda.</p>
                <a href="{{ route('admin.aulas.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#012E40] hover:bg-[#024959] text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primera Aula
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Botón flotante para crear aula (mobile) -->
<div class="fixed bottom-6 right-6 md:hidden">
    <a href="{{ route('admin.aulas.create') }}" 
       class="w-14 h-14 bg-[#012E40] hover:bg-[#024959] text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200">
        <i class="fas fa-plus text-lg"></i>
    </a>
</div>
@endsection