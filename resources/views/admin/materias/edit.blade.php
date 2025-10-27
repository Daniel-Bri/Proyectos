@extends('layouts.app')

@section('title', 'Editar Materia - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-4">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header Mobile Optimizado -->
        <div class="gradient-bg px-3 py-4 sm:px-6">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-edit mr-2 sm:mr-3"></i>
                        Editar: {{ $materia->sigla }}
                    </h3>
                    <p class="mt-1 sm:mt-2 text-deep-teal-200 text-xs sm:text-sm">
                        Modifica los datos académicos
                    </p>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <a href="{{ route('admin.materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <form action="{{ route('admin.materias.update', $materia->sigla) }}" method="POST" id="materiaForm">
                @csrf
                @method('PUT')

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

                <!-- Información Actual - Mobile Optimized -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-purple-100 shadow-sm mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-bold text-purple-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-sm sm:text-base"></i>
                        Información Actual
                    </h4>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-4 text-xs sm:text-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="font-medium text-purple-700 text-xs sm:text-sm">Carrera:</span>
                            <span class="font-bold text-purple-900 ml-0 sm:ml-2 text-sm sm:text-base truncate">
                                {{ $materia->carrera->nombre ?? 'No asignada' }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="font-medium text-purple-700 text-xs sm:text-sm">Categoría:</span>
                            <span class="font-bold text-purple-900 ml-0 sm:ml-2 text-sm sm:text-base truncate">
                                {{ $materia->categoria->nombre ?? 'No asignada' }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="font-medium text-purple-700 text-xs sm:text-sm">Semestre:</span>
                            <span class="font-bold text-purple-900 ml-0 sm:ml-2 text-sm sm:text-base">
                                Semestre {{ $materia->semestre }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="font-medium text-purple-700 text-xs sm:text-sm">Grupos:</span>
                            <span class="font-bold text-purple-900 ml-0 sm:ml-2 text-sm sm:text-base">
                                {{ $materia->grupoMaterias->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Grid Responsive -->
                <div class="grid grid-cols-1 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <!-- Información Académica - Mobile Optimized -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-blue-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-graduation-cap mr-2 text-sm sm:text-base"></i>
                            Información Académica
                        </h4>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label for="sigla" class="block text-sm font-medium text-blue-700 mb-1 sm:mb-2">
                                    Sigla *
                                </label>
                                <input type="text" 
                                       name="sigla" 
                                       id="sigla"
                                       value="{{ old('sigla', $materia->sigla) }}"
                                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-blue-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                       placeholder="Ej: MAT101"
                                       required
                                       maxlength="10"
                                       pattern="[A-Za-z0-9]+"
                                       title="Solo letras y números">
                                <p class="text-xs text-blue-600 mt-1 leading-tight">Máx. 10 caracteres, solo letras y números</p>
                            </div>

                            <div>
                                <label for="nombre" class="block text-sm font-medium text-blue-700 mb-1 sm:mb-2">
                                    Nombre Completo *
                                </label>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre"
                                       value="{{ old('nombre', $materia->nombre) }}"
                                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-blue-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                       placeholder="Nombre completo de la materia"
                                       required
                                       maxlength="255">
                                <p class="text-xs text-blue-600 mt-1">Nombre completo de la materia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Académica - Mobile Optimized -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-emerald-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-emerald-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-cogs mr-2 text-sm sm:text-base"></i>
                            Configuración
                        </h4>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label for="semestre" class="block text-sm font-medium text-emerald-700 mb-1 sm:mb-2">
                                    Semestre *
                                </label>
                                <select name="semestre" 
                                        id="semestre"
                                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-emerald-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                        required>
                                    <option value="">Seleccione semestre</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" 
                                            {{ old('semestre', $materia->semestre) == $i ? 'selected' : '' }}>
                                            Semestre {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="id_categoria" class="block text-sm font-medium text-emerald-700 mb-1 sm:mb-2">
                                    Categoría *
                                </label>
                                <select name="id_categoria" 
                                        id="id_categoria"
                                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-emerald-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                        required>
                                    <option value="">Seleccione categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" 
                                            {{ old('id_categoria', $materia->id_categoria) == $categoria->id ? 'selected' : '' }}>
                                            {{ \Illuminate\Support\Str::limit($categoria->nombre, 25) }}
                                            @if(old('id_categoria', $materia->id_categoria) == $categoria->id)
                                                (Actual)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Botones de Acción - Mobile Optimized -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end pt-4 sm:pt-6 border-t border-deep-teal-100">
                    <a href="{{ route('admin.materias.show', $materia->sigla) }}" 
                       class="order-2 sm:order-1 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="order-1 sm:order-2 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base mb-2 sm:mb-0">
                        <i class="fas fa-save mr-1 sm:mr-2"></i>
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// El mismo script optimizado para móvil del create.blade.php
</script>
@endpush
@endsection