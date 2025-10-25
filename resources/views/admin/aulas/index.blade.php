@extends('layouts.app')

@section('title', 'Gestión de Roles')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-white">
                    <i class="fas fa-user-shield mr-3"></i>
                    Gestión de Roles
                </h3>
                <p class="mt-2 text-blue-100 text-sm">
                    Administra los roles y permisos del sistema
                </p>
            </div>
            @can('crear-roles')
            <a href="{{ route('admin.roles.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-blue-600 hover:bg-blue-50 border border-transparent rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Rol
            </a>
            @endcan
        </div>
    </div>

    <div class="p-6 bg-gray-50">
        <!-- Filtros de búsqueda -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-6">
            <form action="{{ route('admin.roles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <input type="text" name="codigo" id="codigo" value="{{ request('codigo') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                        <i class="fas fa-undo mr-2"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>

        @if($roles->count() > 0)
            <!-- Desktop Table -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CÓDIGO</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NOMBRE</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">USUARIOS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PERMISOS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roles as $role)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600 font-medium">
                                #{{ $role->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ substr($role->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $role->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $role->guard_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $role->users_count }} usuarios
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    {{ $role->permissions_count }} permisos
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @can('ver-roles')
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                @endcan
                                @can('editar-roles')
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </a>
                                @endcan
                                @can('eliminar-roles')
                                @if(!in_array($role->name, ['admin', 'coordinador', 'docente']))
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('¿Está seguro de eliminar este rol?')"
                                            class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors duration-200">
                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay roles registrados</h3>
                <p class="text-gray-500 mb-4">No se han encontrado roles que coincidan con los criterios de búsqueda.</p>
                @can('crear-roles')
                <a href="{{ route('admin.roles.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Rol
                </a>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection