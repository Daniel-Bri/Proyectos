@extends('layouts.app')

@section('title', 'Editar Aula: ' . $aula->nombre)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-2xl rounded-2xl border border-[#3CA6A6] overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#012E40] to-[#026773] px-6 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-edit mr-3"></i>
                        Editar Aula
                    </h3>
                    <p class="mt-2 text-[#F2E3D5] text-sm">
                        Modifica la información del aula {{ $aula->nombre }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.aulas.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-[#F2E3D5]/20 hover:bg-[#F2E3D5]/30 text-[#F2E3D5] border border-[#F2E3D5]/30 rounded-xl font-semibold text-sm uppercase tracking-widest transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    <a href="{{ route('admin.aulas.show', $aula) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-[#3CA6A6] hover:bg-[#026773] text-white border border-[#3CA6A6] rounded-xl font-semibold text-sm uppercase tracking-widest transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-eye mr-2"></i>
                        Ver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 bg-[#F2E3D5]">
            <form action="{{ route('admin.aulas.update', $aula) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Información Básica -->
                    <div class="bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6]/20 rounded-xl p-6 border border-[#3CA6A6] shadow-sm">
                        <h4 class="text-lg font-semibold text-[#012E40] mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-[#026773]"></i>
                            Información del Aula
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="codigo" class="block text-sm font-medium text-[#012E40] mb-2">
                                    Código <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $aula->codigo) }}" 
                                       class="w-full border border-[#3CA6A6] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white @error('codigo') border-red-500 @enderror"
                                       placeholder="Ej: AUL-001, LAB-101" required>
                                @error('codigo')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="nombre" class="block text-sm font-medium text-[#012E40] mb-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $aula->nombre) }}" 
                                       class="w-full border border-[#3CA6A6] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white @error('nombre') border-red-500 @enderror"
                                       placeholder="Ej: Aula 24, Laboratorio de Computación" required>
                                @error('nombre')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="capacidad" class="block text-sm font-medium text-[#012E40] mb-2">
                                    Capacidad <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="capacidad" id="capacidad" value="{{ old('capacidad', $aula->capacidad) }}" 
                                       class="w-full border border-[#3CA6A6] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white @error('capacidad') border-red-500 @enderror"
                                       placeholder="Número de estudiantes" min="1" required>
                                @error('capacidad')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="tipo" class="block text-sm font-medium text-[#012E40] mb-2">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo" id="tipo" 
                                        class="w-full border border-[#3CA6A6] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white @error('tipo') border-red-500 @enderror" required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="aula" {{ old('tipo', $aula->tipo) == 'aula' ? 'selected' : '' }}>Aula</option>
                                    <option value="laboratorio" {{ old('tipo', $aula->tipo) == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                    <option value="biblioteca" {{ old('tipo', $aula->tipo) == 'biblioteca' ? 'selected' : '' }}>Biblioteca</option>
                                    <option value="auditorio" {{ old('tipo', $aula->tipo) == 'auditorio' ? 'selected' : '' }}>Auditorio</option>
                                    <option value="otros" {{ old('tipo', $aula->tipo) == 'otros' ? 'selected' : '' }}>Otros</option>
                                </select>
                                @error('tipo')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="estado" class="block text-sm font-medium text-[#012E40] mb-2">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado" id="estado" 
                                        class="w-full border border-[#3CA6A6] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#024959] focus:border-transparent transition-all duration-200 bg-white @error('estado') border-red-500 @enderror" required>
                                    <option value="">Seleccione un estado</option>
                                    <option value="Disponible" {{ old('estado', $aula->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="En Mantenimiento" {{ old('estado', $aula->estado) == 'En Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                                    <option value="No Disponible" {{ old('estado', $aula->estado) == 'No Disponible' ? 'selected' : '' }}>No Disponible</option>
                                </select>
                                @error('estado')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-[#3CA6A6]">
                        <a href="{{ route('admin.aulas.show', $aula) }}" 
                           class="px-8 py-3 border border-[#3CA6A6] text-[#012E40] rounded-xl hover:bg-[#3CA6A6] hover:text-white transition-all duration-200 transform hover:scale-105 shadow hover:shadow-md">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-[#012E40] text-white rounded-xl hover:bg-[#024959] transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Aula
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection