@extends('layouts.app')

@section('title', 'Editar Rol: ' . $role->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">
                        <i class="fas fa-edit mr-3"></i>
                        Editar Rol
                    </h3>
                    <p class="mt-1 text-blue-100 text-sm">
                        Modifica la información del rol {{ $role->name }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    <a href="{{ route('admin.roles.show', $role) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-400 rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Ver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Información Básica -->
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4">Información del Rol</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre del Rol <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                       placeholder="Ej: asistente, supervisor, etc." required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Información de solo lectura para roles del sistema -->
                            @if(in_array($role->name, ['admin', 'coordinador', 'docente']))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">
                                            Rol del Sistema
                                        </p>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            Este es un rol del sistema. Algunas características pueden estar limitadas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Permisos -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Asignar Permisos</h4>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">
                                    <span id="permisos-seleccionados">0</span> permisos seleccionados
                                </span>
                                <button type="button" onclick="seleccionarTodos()" 
                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-check-square mr-1"></i>Seleccionar Todos
                                </button>
                                <button type="button" onclick="deseleccionarTodos()" 
                                        class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    <i class="fas fa-square mr-1"></i>Limpiar
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4 max-h-96 overflow-y-auto p-2 border border-gray-200 rounded-lg bg-white">
                            @foreach($permisos as $grupo => $permisosGrupo)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <h5 class="font-semibold text-gray-800 capitalize">
                                        <i class="fas fa-folder mr-2 text-blue-500"></i>
                                        {{ $grupo }} ({{ count($permisosGrupo) }} permisos)
                                    </h5>
                                    <button type="button" 
                                            onclick="toggleGrupo('grupo-{{ $grupo }}')"
                                            class="text-xs text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div id="grupo-{{ $grupo }}" class="bg-white p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($permisosGrupo as $permiso)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="permisos[]" 
                                               value="{{ $permiso->id }}" 
                                               id="permiso-{{ $permiso->id }}"
                                               class="permiso-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               {{ in_array($permiso->id, $rolPermisos) ? 'checked' : '' }}
                                               onchange="actualizarContador()">
                                        <label for="permiso-{{ $permiso->id }}" class="ml-2 text-sm text-gray-700 flex items-center">
                                            {{ $permiso->name }}
                                            @if(in_array($permiso->id, $rolPermisos))
                                            <span class="ml-2 px-1 py-0.5 bg-green-100 text-green-800 text-xs rounded">Actual</span>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @error('permisos')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Resumen de Cambios -->
                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <h4 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Resumen de Cambios
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="text-center">
                                <div class="bg-white rounded-lg p-3 border border-green-200">
                                    <div class="text-lg font-bold text-green-600" id="total-permisos">
                                        {{ count($rolPermisos) }}
                                    </div>
                                    <div class="text-green-700 font-medium">Permisos Actuales</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <div class="text-lg font-bold text-blue-600" id="nuevos-permisos">0</div>
                                    <div class="text-blue-700 font-medium">Nuevos Permisos</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="text-lg font-bold text-purple-600" id="total-final">0</div>
                                    <div class="text-purple-700 font-medium">Total Final</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <div>
                            @if(in_array($role->name, ['admin', 'coordinador', 'docente']))
                            <p class="text-sm text-yellow-600 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Los roles del sistema tienen configuraciones protegidas.
                            </p>
                            @endif
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.roles.show', $role) }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar Rol
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarContador();
    
    // Colapsar todos los grupos inicialmente
    @foreach($permisos as $grupo => $permisosGrupo)
    toggleGrupo('grupo-{{ $grupo }}', false);
    @endforeach
});

function actualizarContador() {
    const checkboxes = document.querySelectorAll('.permiso-checkbox:checked');
    const totalSeleccionados = checkboxes.length;
    const permisosActuales = {{ count($rolPermisos) }};
    
    // Calcular nuevos permisos (seleccionados que no estaban antes)
    let nuevosPermisos = 0;
    checkboxes.forEach(checkbox => {
        const permisoId = parseInt(checkbox.value);
        if (!{{ json_encode($rolPermisos) }}.includes(permisoId)) {
            nuevosPermisos++;
        }
    });
    
    document.getElementById('permisos-seleccionados').textContent = totalSeleccionados;
    document.getElementById('nuevos-permisos').textContent = nuevosPermisos;
    document.getElementById('total-final').textContent = totalSeleccionados;
}

function seleccionarTodos() {
    const checkboxes = document.querySelectorAll('.permiso-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    actualizarContador();
}

function deseleccionarTodos() {
    const checkboxes = document.querySelectorAll('.permiso-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    actualizarContador();
}

function toggleGrupo(grupoId) {
    const grupo = document.getElementById(grupoId);
    const icono = grupo.previousElementSibling.querySelector('i.fa-chevron-down');
    
    if (grupo.style.display === 'none') {
        grupo.style.display = 'block';
        icono.className = 'fas fa-chevron-down';
    } else {
        grupo.style.display = 'none';
        icono.className = 'fas fa-chevron-right';
    }
}

// Validación antes de enviar el formulario
document.querySelector('form').addEventListener('submit', function(e) {
    const checkboxes = document.querySelectorAll('.permiso-checkbox:checked');
    
    if (checkboxes.length === 0) {
        if (!confirm('⚠️ Este rol no tendrá ningún permiso asignado. ¿Está seguro de continuar?')) {
            e.preventDefault();
            return false;
        }
    }
    
    const roleName = document.getElementById('name').value;
    if (roleName.trim() === '') {
        e.preventDefault();
        alert('Por favor, ingrese un nombre para el rol.');
        return false;
    }
    
    return true;
});
</script>

<style>
.permiso-checkbox:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

#grupo-gestionar,
#grupo-ver,
#grupo-crear,
#grupo-editar,
#grupo-eliminar {
    display: none;
}
</style>
@endsection