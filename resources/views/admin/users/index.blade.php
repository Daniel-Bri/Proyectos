@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-3 py-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-deep-teal-200 overflow-hidden mb-6">
            <div class="gradient-bg px-4 py-5 sm:px-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5] truncate">
                            <i class="fas fa-users mr-2"></i>
                            Gestión de Usuarios
                        </h3>
                        <p class="mt-1 text-deep-teal-200 text-sm sm:text-base">
                            Administra los usuarios del sistema y sus permisos
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-flex items-center px-3 sm:px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-lg sm:rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto justify-center">
                            <i class="fas fa-plus mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                            <span class="text-xs sm:text-sm">Nuevo Usuario</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-lg sm:rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 sm:mr-3 text-sm sm:text-xl"></i>
                        <span class="font-semibold text-sm sm:text-base">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-white hover:text-green-100 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-3 rounded-lg sm:rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2 sm:mr-3 text-sm sm:text-xl"></i>
                        <span class="font-semibold text-sm sm:text-base">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="text-white hover:text-red-100 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Barra de búsqueda -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 border border-deep-teal-100 shadow-lg mb-6">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-deep-teal-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   class="block w-full pl-10 pr-10 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200"
                                   placeholder="Buscar por nombre o email..." 
                                   value="{{ $search ?? '' }}">
                            @if($search ?? '')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <a href="{{ route('admin.users.index') }}" class="text-deep-teal-400 hover:text-deep-teal-600 transition-colors">
                                        <i class="fas fa-times text-sm"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-[#026773] hover:bg-[#024959] text-white font-semibold rounded-lg sm:rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-search mr-1 sm:mr-2"></i>
                        <span class="sm:block">Buscar</span>
                    </button>
                </div>
            </form>
        </div>

        @if($users->count() > 0)
            <!-- Mobile Cards -->
            <div class="block lg:hidden space-y-4">
                @foreach($users as $user)
                <div class="bg-white border border-deep-teal-100 rounded-xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-deep-teal-800 text-sm truncate">{{ $user->name }}</p>
                                <p class="text-xs text-deep-teal-600 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-deep-teal-600 text-xs font-medium mb-1">Roles</p>
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->roles as $role)
                                <span class="px-2 py-1 text-xs bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded border border-[#3CA6A6] border-opacity-30 truncate max-w-[120px]">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Sin roles</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-3 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $user->created_at->format('d/m/Y') }}
                        </span>
                        <div class="flex gap-1">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="inline-flex items-center px-2 py-1 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- Botón eliminar para móvil -->
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Está seguro de eliminar este usuario? Se eliminarán también los datos relacionados (docente, etc.). Esta acción no se puede deshacer.')"
                                        class="inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-2xl border border-deep-teal-100 shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-deep-teal-100">
                        <thead class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                            <tr>
                                <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Usuario</th>
                                <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Roles</th>
                                <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Registro</th>
                                <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-deep-teal-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                                <td class="px-4 py-3 sm:px-6 sm:py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-lg mr-3 sm:mr-4 shadow-md">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-deep-teal-800 truncate max-w-[150px]">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-xs sm:text-sm text-deep-teal-600">
                                                ID: {{ $user->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-sm text-deep-teal-800 font-medium truncate max-w-[200px]">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-3 sm:px-6 sm:py-5">
                                    <div class="flex flex-wrap gap-1 max-w-[200px]">
                                        @forelse($user->roles as $role)
                                            <span class="px-2 py-1 text-xs font-semibold bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded border border-[#3CA6A6] border-opacity-30 truncate">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded">Sin roles</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-sm text-deep-teal-700 font-medium">
                                    <i class="far fa-calendar mr-1 text-deep-teal-500"></i>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="inline-flex items-center px-2 sm:px-4 py-1 sm:py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                           title="Ver detalles">
                                            <i class="fas fa-eye mr-1 sm:mr-2 text-xs"></i>
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="inline-flex items-center px-2 sm:px-4 py-1 sm:py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                           title="Editar usuario">
                                            <i class="fas fa-edit mr-1 sm:mr-2 text-xs"></i>
                                            <span class="hidden sm:inline">Editar</span>
                                        </a>
                                        
                                        <!-- Botón eliminar para desktop -->
                                        @if(auth()->check() && auth()->user()->hasRole('admin'))
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('¿Está seguro de eliminar este usuario? Se eliminarán también los datos relacionados (docente, etc.). Esta acción no se puede deshacer.')"
                                                    class="inline-flex items-center px-2 sm:px-4 py-1 sm:py-2 bg-red-500 hover:bg-red-600 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                                    title="Eliminar usuario">
                                                <i class="fas fa-trash mr-1 sm:mr-2 text-xs"></i>
                                                <span class="hidden sm:inline">Eliminar</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginación -->
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-deep-teal-600 font-medium text-center sm:text-left">
                    Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} usuarios
                </div>
                <div class="bg-white px-4 py-3 rounded-xl border border-deep-teal-100 shadow-lg">
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12 sm:py-16">
                <div class="w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-4 sm:mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-deep-teal-500 text-3xl sm:text-5xl"></i>
                </div>
                <h3 class="text-lg sm:text-2xl font-bold text-deep-teal-700 mb-2 sm:mb-3">No hay usuarios</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-sm sm:text-lg mb-6 sm:mb-8 px-4">
                    @if($search ?? '')
                        No se encontraron usuarios para "{{ $search }}"
                    @else
                        No hay usuarios registrados en el sistema.
                    @endif
                </p>
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                    <i class="fas fa-plus mr-1 sm:mr-2"></i>
                    Crear Primer Usuario
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

/* Mejoras para la paginación en móvil */
@media (max-width: 640px) {
    .pagination {
        @apply flex flex-wrap justify-center gap-2;
    }
    
    .pagination li {
        @apply inline-block;
    }
    
    .pagination .page-link {
        @apply px-3 py-2 text-sm border border-deep-teal-200 rounded-lg bg-white text-deep-teal-800 hover:bg-deep-teal-50;
    }
    
    .pagination .active .page-link {
        @apply bg-[#3CA6A6] text-white border-[#3CA6A6];
    }
}
</style>
@endsection