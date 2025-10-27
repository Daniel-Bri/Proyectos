@extends('layouts.app')

@section('title', 'Gestión de Roles y Permisos')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-user-shield mr-3"></i>
                    Gestión de Roles y Permisos
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra los roles del sistema y sus permisos
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.roles.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Rol
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-white hover:text-green-100 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-3 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="text-white hover:text-red-100 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if($roles->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($roles as $role)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md">
                                {{ substr($role->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-deep-teal-800">{{ $role->name }}</p>
                                <p class="text-sm text-deep-teal-600">ID: {{ $role->id }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-2 py-1 text-xs font-semibold bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded-full border border-[#3CA6A6] border-opacity-30">
                                {{ $role->permissions->count() }} permisos
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Guard</p>
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg">{{ $role->guard_name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $role->created_at->format('d/m/Y') }}
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.roles.show', $role->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-1"></i>
                            </a>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Está seguro de eliminar este rol?')"
                                        class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </form>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Permisos</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($roles as $role)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-deep-teal-800">
                                #{{ $role->id }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        {{ substr($role->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800">
                                            {{ $role->name }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600">
                                            {{ $role->guard_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-2 inline-flex text-xs leading-5 font-bold rounded-xl bg-[#3CA6A6] bg-opacity-20 text-[#026773] border border-[#3CA6A6] border-opacity-30">
                                    {{ $role->permissions->count() }} permisos
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.roles.show', $role->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Está seguro de eliminar este rol?')"
                                                class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
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
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-deep-teal-600 font-medium">
                    Mostrando {{ $roles->firstItem() }} a {{ $roles->lastItem() }} de {{ $roles->total() }} roles
                </div>
                <div class="bg-white px-6 py-3 rounded-2xl border border-deep-teal-100 shadow-lg">
                    {{ $roles->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-shield text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay roles</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-8">
                    No hay roles registrados en el sistema.
                </p>
                <a href="{{ route('admin.roles.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Rol
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.gradient-bg {
    background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
}

.bg-deep-teal-25 {
    background-color: rgba(1, 46, 64, 0.025);
}

.border-deep-teal-100 {
    border-color: rgba(1, 46, 64, 0.1);
}

.border-deep-teal-200 {
    border-color: rgba(1, 46, 64, 0.2);
}

.text-deep-teal-200 {
    color: rgba(242, 227, 213, 0.8);
}

.text-deep-teal-400 {
    color: rgba(1, 46, 64, 0.4);
}

.text-deep-teal-500 {
    color: rgba(1, 46, 64, 0.6);
}

.text-deep-teal-600 {
    color: rgba(1, 46, 64, 0.7);
}

.text-deep-teal-700 {
    color: rgba(1, 46, 64, 0.8);
}

.text-deep-teal-800 {
    color: rgba(1, 46, 64, 0.9);
}

.bg-deep-teal-50 {
    background-color: rgba(1, 46, 64, 0.05);
}

.bg-deep-teal-100 {
    background-color: rgba(1, 46, 64, 0.1);
}

.bg-deep-teal-200 {
    background-color: rgba(1, 46, 64, 0.2);
}
</style>
@endsection