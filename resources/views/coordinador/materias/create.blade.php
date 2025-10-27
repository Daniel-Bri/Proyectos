@extends('layouts.app') {{-- ✅ Usar layout principal --}}

@section('title', 'Crear Nueva Materia - Coordinador')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-plus-circle mr-3"></i>
                        {{ isset($materia) ? 'Editar Materia' : 'Crear Nueva Materia' }}
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        {{ isset($materia) ? 'Modifica los datos académicos de la materia' : 'Registra una nueva materia en el sistema académico' }}
                    </p>
                </div>
                {{-- ✅ RUTA CORREGIDA --}}
                <a href="{{ route('coordinador.materias.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            {{-- ✅ RUTAS CORREGIDAS --}}
            <form action="{{ isset($materia) ? route('coordinador.materias.update', $materia->sigla) : route('coordinador.materias.store') }}" method="POST">
                @csrf
                @if(isset($materia))
                    @method('PUT')
                @endif

                {{-- ... resto del formulario igual ... --}}

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-deep-teal-100">
                    {{-- ✅ RUTA CORREGIDA --}}
                    <a href="{{ route('coordinador.materias.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        {{ isset($materia) ? 'Actualizar Materia' : 'Crear Materia' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection