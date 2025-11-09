@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-user-edit mr-3"></i>
                        Editar Usuario: {{ $user->name }}
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Actualice la información del usuario en el sistema
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white border border-transparent rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <div class="bg-white rounded-2xl p-6 border border-deep-teal-100 shadow-lg">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                <i class="fas fa-user mr-2 text-[#3CA6A6]"></i>
                                Nombre Completo *
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
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
                        <div>
                            <label for="email" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                <i class="fas fa-envelope mr-2 text-[#3CA6A6]"></i>
                                Correo Electrónico *
                            </label>
                            <input type="email" 
                                   class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
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

                    <!-- Campos de Contraseña -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-lock mr-2 text-[#3CA6A6] text-lg"></i>
                            <h4 class="text-lg font-bold text-deep-teal-800">
                                Cambiar Contraseña
                            </h4>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 text-lg mr-3"></i>
                                <div>
                                    <p class="text-sm text-blue-600">
                                        Deje estos campos en blanco si no desea cambiar la contraseña.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nueva Contraseña -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                    <i class="fas fa-key mr-2 text-[#3CA6A6]"></i>
                                    Nueva Contraseña
                                </label>
                                <input type="password" 
                                       class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Ingrese nueva contraseña">
                                @error('password')
                                    <div class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-deep-teal-800 mb-2">
                                    <i class="fas fa-key mr-2 text-[#3CA6A6]"></i>
                                    Confirmar Contraseña
                                </label>
                                <input type="password" 
                                       class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl bg-deep-teal-25 placeholder-deep-teal-400 text-deep-teal-800 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirme la nueva contraseña">
                            </div>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-deep-teal-800 mb-4">
                            <i class="fas fa-user-tag mr-2 text-[#3CA6A6]"></i>
                            Roles del Usuario
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($roles as $role)
                                <div class="flex items-center p-4 border border-deep-teal-200 rounded-xl bg-deep-teal-25 hover:bg-deep-teal-50 transition-all duration-200">
                                    <input class="w-4 h-4 text-[#3CA6A6] bg-deep-teal-100 border-deep-teal-300 rounded focus:ring-[#3CA6A6] focus:ring-2" 
                                           type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->id }}" 
                                           id="role_{{ $role->id }}"
                                           {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="ml-3 text-sm font-medium text-deep-teal-800" for="role_{{ $role->id }}">
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

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-deep-teal-100">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-1">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Usuario
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-1">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de contraseñas coincidentes
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswords() {
        if (password.value && passwordConfirmation.value) {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('Las contraseñas no coinciden');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirmation.addEventListener('input', validatePasswords);
});
</script>
@endsection