@extends('layouts.app')

@section('title', 'Crear Nueva Aula')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Crear Nueva Aula
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Complete el formulario para registrar una nueva aula en el sistema
                    </p>
                </div>
                <a href="{{ route('admin.aulas.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="p-6 sm:p-8">
            <form action="{{ route('admin.aulas.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-bold text-deep-teal-700 mb-2">
                            <i class="fas fa-hashtag mr-2 text-[#3CA6A6]"></i>
                            Código del Aula *
                        </label>
                        <input type="text" 
                               id="codigo" 
                               name="codigo" 
                               value="{{ old('codigo') }}"
                               required
                               class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20 transition-all duration-200 @error('codigo') border-rose-500 @enderror"
                               placeholder="Ej: AULA-001">
                        @error('codigo')
                            <p class="mt-1 text-sm text-rose-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-bold text-deep-teal-700 mb-2">
                            <i class="fas fa-door-open mr-2 text-[#3CA6A6]"></i>
                            Nombre del Aula *
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre') }}"
                               required
                               class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20 transition-all duration-200 @error('nombre') border-rose-500 @enderror"
                               placeholder="Ej: Laboratorio de Informática">
                        @error('nombre')
                            <p class="mt-1 text-sm text-rose-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Capacidad -->
                    <div>
                        <label for="capacidad" class="block text-sm font-bold text-deep-teal-700 mb-2">
                            <i class="fas fa-users mr-2 text-[#3CA6A6]"></i>
                            Capacidad *
                        </label>
                        <input type="number" 
                               id="capacidad" 
                               name="capacidad" 
                               value="{{ old('capacidad') }}"
                               required
                               min="1"
                               class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20 transition-all duration-200 @error('capacidad') border-rose-500 @enderror"
                               placeholder="Ej: 30">
                        @error('capacidad')
                            <p class="mt-1 text-sm text-rose-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label for="tipo" class="block text-sm font-bold text-deep-teal-700 mb-2">
                            <i class="fas fa-tag mr-2 text-[#3CA6A6]"></i>
                            Tipo de Aula *
                        </label>
                        <select id="tipo" 
                                name="tipo" 
                                required
                                class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20 transition-all duration-200 @error('tipo') border-rose-500 @enderror">
                            <option value="">Seleccione un tipo</option>
                            <option value="Teórica" {{ old('tipo') == 'Teórica' ? 'selected' : '' }}>Teórica</option>
                            <option value="Práctica" {{ old('tipo') == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                            <option value="Laboratorio" {{ old('tipo') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                            <option value="Multiusos" {{ old('tipo') == 'Multiusos' ? 'selected' : '' }}>Multiusos</option>
                        </select>
                        @error('tipo')
                            <p class="mt-1 text-sm text-rose-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Ubicación -->
                <div>
                    <label for="ubicacion" class="block text-sm font-bold text-deep-teal-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-[#3CA6A6]"></i>
                        Ubicación *
                    </label>
                    <input type="text" 
                           id="ubicacion" 
                           name="ubicacion" 
                           value="{{ old('ubicacion') }}"
                           required
                           class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20 transition-all duration-200 @error('ubicacion') border-rose-500 @enderror"
                           placeholder="Ej: Edificio Principal, Piso 2, Ala Norte">
                    @error('ubicacion')
                        <p class="mt-1 text-sm text-rose-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Equipamiento -->
                <div>
                    <label for="equipamiento" class="block text-sm font-bold text-deep-teal-700 mb-2">
                        <i class="fas fa-laptop mr-2 text-[#3CA6A6]"></i>
                        Equipamiento
                    </label>
                    <textarea id="equipamiento" 
                              name="equipamiento" 
                              rows="3"
                              class="w-full rounded-xl border-deep-teal-200 shadow-sm focus:border-[#3CA6A6] focus:ring focus:ring-[#3CA6A6] focus:ring-opacity-20 transition-all duration-200 @error('equipamiento') border-rose-500 @enderror"
                              placeholder="Describa el equipamiento disponible en el aula (proyectores, computadoras, etc.)">{{ old('equipamiento') }}</textarea>
                    @error('equipamiento')
                        <p class="mt-1 text-sm text-rose-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-bold text-deep-teal-700 mb-2">
                        <i class="fas fa-info-circle mr-2 text-[#3CA6A6]"></i>
                        Estado del Aula *
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center p-4 border-2 border-deep-teal-100 rounded-xl cursor-pointer hover:bg-deep-teal-25 transition-all duration-200 has-[:checked]:border-[#3CA6A6] has-[:checked]:bg-deep-teal-50">
                            <input type="radio" name="estado" value="Disponible" {{ old('estado') == 'Disponible' ? 'checked' : '' }} class="text-[#3CA6A6] focus:ring-[#3CA6A6] mr-3">
                            <div>
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="font-semibold text-deep-teal-800">Disponible</span>
                                </div>
                                <p class="text-sm text-deep-teal-600 mt-1">Aula lista para uso</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-deep-teal-100 rounded-xl cursor-pointer hover:bg-deep-teal-25 transition-all duration-200 has-[:checked]:border-yellow-400 has-[:checked]:bg-yellow-50">
                            <input type="radio" name="estado" value="En Mantenimiento" {{ old('estado') == 'En Mantenimiento' ? 'checked' : '' }} class="text-yellow-500 focus:ring-yellow-500 mr-3">
                            <div>
                                <div class="flex items-center">
                                    <i class="fas fa-tools text-yellow-500 mr-2"></i>
                                    <span class="font-semibold text-deep-teal-800">En Mantenimiento</span>
                                </div>
                                <p class="text-sm text-deep-teal-600 mt-1">Aula en reparación</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-deep-teal-100 rounded-xl cursor-pointer hover:bg-deep-teal-25 transition-all duration-200 has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50">
                            <input type="radio" name="estado" value="No Disponible" {{ old('estado') == 'No Disponible' ? 'checked' : '' }} class="text-rose-500 focus:ring-rose-500 mr-3">
                            <div>
                                <div class="flex items-center">
                                    <i class="fas fa-times-circle text-rose-500 mr-2"></i>
                                    <span class="font-semibold text-deep-teal-800">No Disponible</span>
                                </div>
                                <p class="text-sm text-deep-teal-600 mt-1">Aula fuera de servicio</p>
                            </div>
                        </label>
                    </div>
                    @error('estado')
                        <p class="mt-2 text-sm text-rose-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-deep-teal-100">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-1">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Aula
                    </button>
                    <a href="{{ route('admin.aulas.index') }}"
                       class="inline-flex items-center justify-center px-6 py-3 bg-deep-teal-200 hover:bg-deep-teal-300 text-deep-teal-700 font-bold rounded-xl transition-all duration-200 flex-1 text-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para mejorar la experiencia de usuario -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Focus en el primer campo
        document.getElementById('codigo').focus();
        
        // Validación en tiempo real para capacidad
        const capacidadInput = document.getElementById('capacidad');
        capacidadInput.addEventListener('input', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    });
</script>
@endsection