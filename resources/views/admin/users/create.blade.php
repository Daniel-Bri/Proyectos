@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="container mx-auto px-3">
        <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
            <!-- Header -->
            <div class="gradient-bg px-4 py-5 sm:px-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                            <i class="fas fa-user-plus mr-2"></i>
                            Crear Nuevo Usuario
                        </h3>
                        <p class="mt-1 sm:mt-2 text-deep-teal-200 text-sm">
                            Complete la información para registrar un nuevo usuario en el sistema
                        </p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm w-full sm:w-auto justify-center">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        <span class="text-xs sm:text-sm">Volver</span>
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-deep-teal-100 shadow-lg">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
                            <!-- Nombre -->
                            <div class="md:col-span-2 lg:col-span-1">
                                <label for="name" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                    <i class="fas fa-user mr-2 text-[#3CA6A6]"></i>
                                    Nombre Completo *
                                </label>
                                <input type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Ingrese el nombre completo"
                                       required>
                                @error('name')
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2 lg:col-span-1">
                                <label for="email" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                    <i class="fas fa-envelope mr-2 text-[#3CA6A6]"></i>
                                    Correo Electrónico *
                                </label>
                                <input type="email" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="usuario@ejemplo.com"
                                       required>
                                @error('email')
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
                            <!-- Contraseña -->
                            <div class="md:col-span-2 lg:col-span-1">
                                <label for="password" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                    <i class="fas fa-lock mr-2 text-[#3CA6A6]"></i>
                                    Contraseña *
                                </label>
                                <input type="password" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Ingrese la contraseña"
                                       required>
                                @error('password')
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="md:col-span-2 lg:col-span-1">
                                <label for="password_confirmation" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                    <i class="fas fa-lock mr-2 text-[#3CA6A6]"></i>
                                    Confirmar Contraseña *
                                </label>
                                <input type="password" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-deep-teal-200 rounded-lg sm:rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirme la contraseña"
                                       required>
                            </div>
                        </div>



                        <!-- Roles -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-deep-teal-800 mb-3 sm:mb-4">
                                <i class="fas fa-user-tag mr-2 text-[#3CA6A6]"></i>
                                Roles del Usuario
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($roles as $role)
                                    <div class="flex items-center p-3 border border-deep-teal-200 rounded-lg bg-deep-teal-25 hover:bg-deep-teal-50 transition-all duration-200">
                                        <input class="w-4 h-4 text-[#3CA6A6] bg-deep-teal-100 border-deep-teal-300 rounded focus:ring-[#3CA6A6] focus:ring-2" 
                                               type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->id }}" 
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        <label class="ml-2 text-sm font-medium text-deep-teal-800 truncate" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div id="docente-fields" class="hidden bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-blue-200 mt-4">
    <h4 class="text-sm sm:text-lg font-bold text-blue-800 mb-4 flex items-center">
        <i class="fas fa-chalkboard-teacher mr-2"></i>
        Información del Docente
    </h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label for="codigo" class="block text-sm font-bold text-blue-700 mb-2">
                Código del Docente *
            </label>
            <input type="text" 
                   id="codigo" 
                   name="codigo" 
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-blue-200 rounded-lg sm:rounded-xl"
                   placeholder="DOC001"
                   value="{{ old('codigo') }}">
            @error('codigo')
                <div class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div>
            <label for="telefono" class="block text-sm font-bold text-blue-700 mb-2">
                Teléfono *
            </label>
            <input type="text" 
                   id="telefono" 
                   name="telefono" 
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-blue-200 rounded-lg sm:rounded-xl"
                   placeholder="76543210"
                   value="{{ old('telefono') }}">
            @error('telefono')
                <div class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div>
            <label for="sueldo" class="block text-sm font-bold text-blue-700 mb-2">
                Sueldo *
            </label>
            <input type="number" 
                   step="0.01" 
                   id="sueldo" 
                   name="sueldo" 
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-blue-200 rounded-lg sm:rounded-xl"
                   placeholder="7000"
                   value="{{ old('sueldo') }}">
            @error('sueldo')
                <div class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div>
            <label for="fecha_contrato" class="block text-sm font-bold text-blue-700 mb-2">
                Fecha Contrato *
            </label>
            <input type="date" 
                   id="fecha_contrato" 
                   name="fecha_contrato" 
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-blue-200 rounded-lg sm:rounded-xl"
                   value="{{ old('fecha_contrato') }}">
            @error('fecha_contrato')
                <div class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="md:col-span-2">
            <label for="fecha_final" class="block text-sm font-bold text-blue-700 mb-2">
                Fecha Final *
            </label>
            <input type="date" 
                   id="fecha_final" 
                   name="fecha_final" 
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-blue-200 rounded-lg sm:rounded-xl"
                   value="{{ old('fecha_final') }}">
            @error('fecha_final')
                <div class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
    const docenteFields = document.getElementById('docente-fields');
    
    function toggleDocenteFields() {
        const hasDocenteRole = Array.from(roleCheckboxes).some(checkbox => 
            checkbox.checked && checkbox.value === '{{ $roles->firstWhere('name', 'docente')?->id }}'
        );
        
        if (hasDocenteRole) {
            docenteFields.classList.remove('hidden');
            // Hacer requeridos los campos de docente
            docenteFields.querySelectorAll('input').forEach(input => {
                input.required = true;
            });
        } else {
            docenteFields.classList.add('hidden');
            // Quitar requerido
            docenteFields.querySelectorAll('input').forEach(input => {
                input.required = false;
            });
        }
    }
    
    roleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleDocenteFields);
    });
    
    // Ejecutar al cargar la página
    toggleDocenteFields();
});
</script>
                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-deep-teal-100">
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base flex-1">
                                <i class="fas fa-save mr-1 sm:mr-2"></i>
                                Crear Usuario
                            </button>
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base flex-1">
                                <i class="fas fa-times mr-1 sm:mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

.text-deep-teal-800 {
    color: rgba(1, 46, 64, 0.9);
}

.bg-deep-teal-50 {
    background-color: rgba(1, 46, 64, 0.05);
}

/* Mejoras para inputs en móvil */
@media (max-width: 640px) {
    input, select, textarea {
        font-size: 16px !important; /* Previene zoom en iOS */
    }
}
</style>
@endsection