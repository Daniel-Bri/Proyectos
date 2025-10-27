@extends('layouts.app') {{-- ✅ Usar layout principal --}}

@section('title', 'Asignar Grupo - Coordinador')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-users-cog mr-3"></i>
                        Asignar Grupo a Materia
                    </h3>
                    <p class="mt-2 text-deep-teal-200 text-sm">
                        Programa grupos y horarios para: <strong>{{ $materia->sigla }} - {{ $materia->nombre }}</strong>
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    {{-- ✅ RUTA CORREGIDA --}}
                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            {{-- ✅ RUTA CORREGIDA --}}
            <form action="{{ route('coordinador.materias.store-asignar-grupo', $materia->sigla) }}" method="POST" id="asignacionForm">
                @csrf

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-5">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4 class="text-lg font-bold text-rose-800">Corrige los siguientes errores</h4>
                        </div>
                        <ul class="list-disc list-inside text-rose-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Información de la Materia -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm mb-8">
                    <h4 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información de la Materia
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                {{ substr($materia->sigla, 0, 2) }}
                            </div>
                            <p class="font-bold text-blue-900">{{ $materia->sigla }}</p>
                            <p class="text-sm text-blue-700">Sigla</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                S{{ $materia->semestre }}
                            </div>
                            <p class="font-bold text-green-900">Semestre {{ $materia->semestre }}</p>
                            <p class="text-sm text-green-700">Nivel</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            {{-- ✅ CORREGIDO: Contar docentes de horarios --}}
                            @php
                                $docentesCount = 0;
                                $docentesUnicos = [];
                                foreach($materia->grupoMaterias as $grupoMateria) {
                                    foreach($grupoMateria->horarios as $horario) {
                                        if ($horario->docente && !in_array($horario->docente->codigo, $docentesUnicos)) {
                                            $docentesUnicos[] = $horario->docente->codigo;
                                            $docentesCount++;
                                        }
                                    }
                                }
                            @endphp
                            <p class="font-bold text-purple-900">{{ $docentesCount }}</p>
                            <p class="text-sm text-purple-700">Docentes en Horarios</p>
                        </div>
                    </div>
                </div>

                {{-- ... resto del formulario igual ... --}}

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-deep-teal-100">
                    {{-- ✅ RUTA CORREGIDA --}}
                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            id="btn-submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-calendar-check mr-2"></i>
                        Confirmar Programación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let horarioCount = 1;

// Cargar recursos al iniciar
$(document).ready(function() {
    cargarRecursos();
    actualizarResumen();
});

// Cargar horarios y aulas disponibles
function cargarRecursos() {
    // Cargar horarios
    $.get('{{ route("coordinador.materias.get-horarios") }}', function(horarios) {
        $('.horario-select').each(function() {
            const currentVal = $(this).val();
            $(this).empty().append('<option value="">Seleccione horario</option>');
            horarios.forEach(function(horario) {
                const option = `<option value="${horario.id}">
                    ${horario.dia} - ${horario.hora_inicio} a ${horario.hora_fin}
                </option>`;
                $(this).append(option);
            });
            if (currentVal) $(this).val(currentVal);
        });
        $('#horarios-count').text(horarios.length + ' disponibles');
    });

    // Cargar aulas
    $.get('{{ route("coordinador.materias.get-aulas") }}', function(aulas) {
        $('.aula-select').each(function() {
            const currentVal = $(this).val();
            $(this).empty().append('<option value="">Seleccione aula</option>');
            aulas.forEach(function(aula) {
                const option = `<option value="${aula.id}">
                    ${aula.nombre} (Cap: ${aula.capacidad})
                </option>`;
                $(this).append(option);
            });
            if (currentVal) $(this).val(currentVal);
        });
        $('#aulas-count').text(aulas.length + ' disponibles');
    });
}

// ... resto del JavaScript igual ...
</script>
@endpush