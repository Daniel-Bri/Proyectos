@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Coordinación de Ingeniería')

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
                            Gestión de Usuarios - Coordinación de Ingeniería
                        </h3>
                        <p class="mt-1 text-deep-teal-200 text-sm sm:text-base">
                            Administración de docentes y estudiantes de la carrera
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('coordinador.users.create') }}" 
                           class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-500 hover:bg-green-600 border border-transparent rounded-lg sm:rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto justify-center">
                            <i class="fas fa-plus mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                            <span class="text-xs sm:text-sm">Nuevo Docente</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 border border-deep-teal-100 shadow-lg mb-6">
            <form action="{{ route('coordinador.users.index') }}" method="GET">
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
                                    <a href="{{ route('coordinador.users.index') }}" class="text-deep-teal-400 hover:text-deep-teal-600 transition-colors">
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
                        <div class="flex flex-col items-end gap-1">
                            @if($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full border border-green-200">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full border border-yellow-200">
                                    Inactivo
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-deep-teal-600 text-xs font-medium mb-1">Rol</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 text-xs bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded border border-[#3CA6A6] border-opacity-30">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-3 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            ID: {{ $user->id }}
                        </span>
                        <div class="flex gap-1">
                            <a href="{{ route('coordinador.users.edit', $user) }}" 
                               class="inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="resetPassword({{ $user->id }})"
                                    class="inline-flex items-center px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-lock"></i>
                            </button>
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
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Usuario</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Rol</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-deep-teal-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-deep-teal-800 font-mono font-bold">
                                    {{ str_pad($user->id, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-deep-teal-800 font-medium">
                                    {{ $user->name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-deep-teal-600 font-mono">
                                    {{ explode('@', $user->email)[0] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @foreach($user->roles as $role)
                                        <span class="px-2 py-1 text-xs font-semibold bg-[#3CA6A6] bg-opacity-20 text-[#026773] rounded border border-[#3CA6A6] border-opacity-30">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($user->email_verified_at)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded bg-green-100 text-green-800 border border-green-200">
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-1">
                                        <a href="{{ route('coordinador.users.edit', $user) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold rounded transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                           title="Editar usuario">
                                            <i class="fas fa-edit mr-1"></i>
                                            Editar
                                        </a>
                                        <button onclick="resetPassword({{ $user->id }})"
                                                class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                                title="Restablecer contraseña">
                                            <i class="fas fa-lock mr-1"></i>
                                            Contraseña
                                        </button>
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
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-deep-teal-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-deep-teal-700 mb-2">No hay usuarios</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-sm mb-6 px-4">
                    @if($search ?? '')
                        No se encontraron usuarios para "{{ $search }}"
                    @else
                        No hay usuarios registrados en esta carrera.
                    @endif
                </p>
                <a href="{{ route('coordinador.users.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Docente
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal para resetear contraseña -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-deep-teal-800 mb-4">Restablecer Contraseña</h3>
        <form id="passwordForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-deep-teal-600 mb-2">Nueva Contraseña</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-deep-teal-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3CA6A6]" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-deep-teal-600 mb-2">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-deep-teal-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3CA6A6]" required>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closePasswordModal()" class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancelar</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
function resetPassword(userId) {
    const form = document.getElementById('passwordForm');
    form.action = `/coordinador/users/${userId}/password`;
    document.getElementById('passwordModal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}

// Cerrar modal al hacer click fuera
document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target.id === 'passwordModal') {
        closePasswordModal();
    }
});
</script>

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

.text-deep-teal-600 {
    color: rgba(1, 46, 64, 0.7);
}

.text-deep-teal-800 {
    color: rgba(1, 46, 64, 0.9);
}
</style>
@endsection