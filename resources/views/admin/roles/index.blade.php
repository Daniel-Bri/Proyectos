@extends('layouts.app')

@section('title', 'Gestión de Roles')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-5">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white">
                <i class="fas fa-user-shield mr-3"></i>
                Gestión de Roles
            </h2>
            <a href="{{ route('admin.roles.create') }}" 
               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold shadow hover:bg-blue-50 transition duration-200">
                <i class="fas fa-plus mr-2"></i> Nuevo Rol
            </a>
        </div>
    </div>

    <!-- Contenido -->
    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($roles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre del Rol
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Permisos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roles as $role)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $role->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ substr($role->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $role->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $role->guard_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">
                                    {{ $role->permissions->count() }} permisos
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.roles.show', $role->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 bg-blue-100 px-3 py-2 rounded-lg inline-flex items-center">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                   class="text-green-600 hover:text-green-900 bg-green-100 px-3 py-2 rounded-lg inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('¿Está seguro de eliminar este rol?')"
                                            class="text-red-600 hover:text-red-900 bg-red-100 px-3 py-2 rounded-lg inline-flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                </form>
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
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay roles registrados</h3>
                <p class="text-gray-500 mb-4">Comienza creando el primer rol del sistema.</p>
                <a href="{{ route('admin.roles.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Rol
                </a>
            </div>
        @endif
    </div>
</div>
@endsection