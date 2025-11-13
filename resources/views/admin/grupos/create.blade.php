@extends('layouts.app')

@section('title', 'Crear Nuevo Grupo - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-4">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header Mobile Optimizado -->
        <div class="gradient-bg px-3 py-4 sm:px-6">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-plus-circle mr-2 sm:mr-3"></i>
                        Crear Nuevo Grupo
                    </h3>
                    <p class="mt-1 sm:mt-2 text-deep-teal-200 text-xs sm:text-sm">
                        Completa el formulario para registrar un nuevo grupo académico
                    </p>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <a href="{{ route('admin.grupos.index') }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <form action="{{ route('admin.grupos.store') }}" method="POST" id="grupoForm">
                @csrf

                @if($errors->any())
                    <div class="mb-4 sm:mb-6 bg-rose-50 border border-rose-200 rounded-xl sm:rounded-2xl p-3 sm:p-5">
                        <div class="flex items-center mb-2 sm:mb-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-xs sm:text-base"></i>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-rose-800">Errores por corregir</h4>
                        </div>
                        <ul class="list-disc list-inside text-rose-700 space-y-1 text-xs sm:text-sm">
                            @foreach($errors->all() as $error)
                                <li class="break-words">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Grid Responsive -->
                <div class="grid grid-cols-1 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <!-- Información Básica -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-blue-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-sm sm:text-base"></i>
                            Información Básica
                        </h4>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-blue-700 mb-1 sm:mb-2">
                                    Nombre del Grupo *
                                </label>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre"
                                       value="{{ old('nombre') }}"
                                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-blue-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                       placeholder="Ej: G01, Grupo A"
                                       required
                                       maxlength="50">
                                <p class="text-xs text-blue-600 mt-1">Nombre único para identificar el grupo</p>
                            </div>

                            <div>
                                <label for="gestion" class="block text-sm font-medium text-blue-700 mb-1 sm:mb-2">
                                    Gestión *
                                </label>
                                <input type="text" 
                                       name="gestion" 
                                       id="gestion"
                                       value="{{ old('gestion') }}"
                                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-blue-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                       placeholder="Ej: 2024-1, 2024-I"
                                       required
                                       maxlength="50">
                                <p class="text-xs text-blue-600 mt-1">Período académico del grupo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validación de Formulario -->
                <div id="form-validation" class="hidden mb-4 sm:mb-6 bg-amber-50 border border-amber-200 rounded-xl sm:rounded-2xl p-3 sm:p-5">
                    <div class="flex items-center mb-2 sm:mb-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-amber-500 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3 flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-xs sm:text-base"></i>
                        </div>
                        <h4 class="text-base sm:text-lg font-bold text-amber-800">Verifica los campos</h4>
                    </div>
                    <ul class="list-disc list-inside text-amber-700 space-y-1 text-xs sm:text-sm" id="validation-errors">
                    </ul>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end pt-4 sm:pt-6 border-t border-deep-teal-100">
                    <a href="{{ route('admin.grupos.index') }}" 
                       class="order-2 sm:order-1 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="order-1 sm:order-2 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base mb-2 sm:mb-0">
                        <i class="fas fa-save mr-1 sm:mr-2"></i>
                        Crear Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('grupoForm');
    const validationDiv = document.getElementById('form-validation');
    const validationErrors = document.getElementById('validation-errors');

    form.addEventListener('submit', function(e) {
        let errors = [];
        validationErrors.innerHTML = '';

        // Validar campos requeridos
        const nombre = document.getElementById('nombre').value.trim();
        const gestion = document.getElementById('gestion').value.trim();

        if (!nombre) {
            errors.push('El nombre del grupo es requerido');
        }

        if (!gestion) {
            errors.push('La gestión es requerida');
        }

        if (errors.length > 0) {
            e.preventDefault();
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                li.className = 'break-words';
                validationErrors.appendChild(li);
            });
            validationDiv.classList.remove('hidden');
            
            // Scroll suave a la validación en móvil
            validationDiv.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
    });

    // Limpiar validación al cambiar campos
    form.querySelectorAll('input').forEach(field => {
        field.addEventListener('change', function() {
            validationDiv.classList.add('hidden');
        });
        
        // Mejorar touch en móvil
        field.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        field.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush
@endsection