@extends('layouts.app')

@section('title', 'Asignar Aulas - Coordinador')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-4">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header Mobile Optimizado -->
        <div class="gradient-bg px-3 py-4 sm:px-6">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-door-open mr-2 sm:mr-3"></i>
                        Asignar Aulas a Grupo
                    </h3>
                    <p class="mt-1 sm:mt-2 text-deep-teal-200 text-xs sm:text-sm">
                        Asigna aulas específicas para: <strong>{{ $materia->sigla }} - {{ $materia->nombre }}</strong>
                    </p>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            @if(!$gruposMateria || $gruposMateria->count() == 0)
                <!-- Estado cuando no hay grupos -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-6 bg-amber-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-amber-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-700 mb-3">No hay grupos asignados</h3>
                    <p class="text-amber-600 mb-6">Primero debe asignar grupos a esta materia antes de poder asignar aulas.</p>
                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                       class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a la materia
                    </a>
                </div>
            @else
            <form action="{{ route('coordinador.materias.store-asignar-aulas', $materia->sigla) }}" method="POST" id="asignacionForm">
                @csrf

                @if($errors->any())
                    <div class="mb-4 sm:mb-6 bg-rose-50 border border-rose-200 rounded-xl sm:rounded-2xl p-3 sm:p-5">
                        <div class="flex items-center mb-2 sm:mb-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-xs sm:text-base"></i>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-rose-800">Corrige los siguientes errores</h4>
                        </div>
                        <ul class="list-disc list-inside text-rose-700 space-y-1 text-xs sm:text-sm">
                            @foreach($errors->all() as $error)
                                <li class="break-words">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Información de la Materia -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm mb-6 sm:mb-8">
                    <h4 class="text-base sm:text-lg font-bold text-blue-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-sm sm:text-base"></i>
                        Información de la Materia
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                {{ substr($materia->sigla, 0, 2) }}
                            </div>
                            <p class="font-bold text-blue-900 text-xs sm:text-sm">{{ $materia->sigla }}</p>
                            <p class="text-blue-700 text-xs">Sigla</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                S{{ $materia->semestre }}
                            </div>
                            <p class="font-bold text-green-900 text-xs sm:text-sm">Semestre {{ $materia->semestre }}</p>
                            <p class="text-green-700 text-xs">Nivel</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="font-bold text-purple-900 text-xs sm:text-sm">{{ $gruposMateria->count() }}</p>
                            <p class="text-purple-700 text-xs">Grupos</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <p class="font-bold text-orange-900 text-xs sm:text-sm">{{ $aulas->count() }}</p>
                            <p class="text-orange-700 text-xs">Aulas Disp.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
                    <!-- Selección de Grupo -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-emerald-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-emerald-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-users mr-2 text-sm sm:text-base"></i>
                            Selección de Grupo
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="id_grupo_materia" class="block text-sm font-medium text-emerald-700 mb-2">
                                    Grupo Existente *
                                </label>
                                <select name="id_grupo_materia" 
                                        id="id_grupo_materia"
                                        class="grupo-select w-full px-3 py-2 sm:px-4 sm:py-3 border border-emerald-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white shadow-sm text-sm sm:text-base"
                                        required>
                                    <option value="">Seleccione un grupo</option>
                                    @foreach($gruposMateria as $grupoMateria)
                                        <option value="{{ $grupoMateria->id }}">
                                            {{ $grupoMateria->grupo->nombre }} - {{ $grupoMateria->gestion->gestion }}
                                            ({{ $grupoMateria->horarios->count() }} horarios)
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-emerald-600 mt-1">Seleccione el grupo al que asignará aulas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recursos Disponibles -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-amber-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-amber-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-tools mr-2 text-sm sm:text-base"></i>
                            Recursos Disponibles
                        </h4>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg sm:rounded-xl border border-amber-200">
                                <div>
                                    <p class="font-medium text-amber-900 text-xs sm:text-sm">Aulas Totales</p>
                                    <p class="text-amber-700 text-xs">{{ $aulas->count() }} espacios</p>
                                </div>
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-door-open text-sm sm:text-base"></i>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-white rounded-lg sm:rounded-xl border border-amber-200">
                                <div>
                                    <p class="font-medium text-amber-900 text-xs sm:text-sm">Horarios</p>
                                    <p class="text-amber-700 text-xs">{{ $horarios->count() }} franjas</p>
                                </div>
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-clock text-sm sm:text-base"></i>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-white rounded-lg sm:rounded-xl border border-amber-200">
                                <div>
                                    <p class="font-medium text-amber-900 text-xs sm:text-sm">Docentes</p>
                                    <p class="text-amber-700 text-xs">{{ $docentes->count() }} disponibles</p>
                                </div>
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user-tie text-sm sm:text-base"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programación de Horarios y Aulas -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-purple-100 shadow-sm mb-6 sm:mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6">
                        <h4 class="text-base sm:text-lg font-bold text-purple-800 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-sm sm:text-base"></i>
                            Asignación de Aulas
                        </h4>
                    </div>

                    <p class="text-purple-600 text-xs sm:text-sm mb-4 sm:mb-6">
                        Asigne aulas específicas para cada horario del grupo seleccionado.
                    </p>

                    <div id="horarios-container" class="space-y-4">
                        <!-- Los horarios se cargarán dinámicamente cuando se seleccione un grupo -->
                        <div id="no-grupo-seleccionado" class="text-center py-8 bg-purple-50 rounded-xl">
                            <i class="fas fa-clock text-purple-400 text-3xl mb-3"></i>
                            <p class="text-purple-600 font-medium">Seleccione un grupo para ver sus horarios</p>
                        </div>
                    </div>

                    <!-- Estado de la Programación -->
                    <div id="estado-programacion" class="mt-4 sm:mt-6 p-3 sm:p-4 bg-white rounded-lg sm:rounded-xl border border-purple-200 hidden">
                        <div class="flex items-center">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                                <i class="fas fa-check text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-800 text-xs sm:text-sm" id="estado-texto">Asignación lista</p>
                                <p class="text-green-600 text-xs" id="estado-descripcion">Puede confirmar la asignación de aulas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end pt-4 sm:pt-6 border-t border-deep-teal-100">
                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                       class="order-2 sm:order-1 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-xs sm:text-sm">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            id="btn-submit"
                            class="order-1 sm:order-2 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-xs sm:text-sm mb-2 sm:mb-0 opacity-50 cursor-not-allowed"
                            disabled>
                        <i class="fas fa-door-open mr-1 sm:mr-2"></i>
                        Confirmar Asignación
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@if($gruposMateria && $gruposMateria->count() > 0)
<!-- Script corregido -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 INICIANDO SISTEMA DE ASIGNACIÓN DE AULAS');
    
    const grupoSelect = document.getElementById('id_grupo_materia');
    const btnSubmit = document.getElementById('btn-submit');
    const estadoProgramacion = document.getElementById('estado-programacion');
    const horariosContainer = document.getElementById('horarios-container');
    const noGrupoSeleccionado = document.getElementById('no-grupo-seleccionado');

    // Datos de horarios por grupo
    const horariosPorGrupo = {
        @foreach($gruposMateria as $grupoMateria)
        '{{ $grupoMateria->id }}': [
            @foreach($grupoMateria->horarios as $horario)
            {
                id_horario_grupo: '{{ $horario->id }}',
                id_horario: '{{ $horario->id_horario }}',
                codigo_docente: '{{ $horario->codigo_docente }}',
                dia: '{{ $horario->horario->dia }}',
                hora_inicio: '{{ $horario->horario->hora_inicio }}',
                hora_fin: '{{ $horario->horario->hora_fin }}',
                aula_actual: '{{ $horario->aula->nombre ?? "Sin aula" }}',
                id_aula_actual: '{{ $horario->id_aula }}'
            },
            @endforeach
        ],
        @endforeach
    };

    // Función para cargar horarios del grupo seleccionado
    function cargarHorariosGrupo(grupoId) {
        console.log('Cargando horarios para grupo:', grupoId);
        
        const horarios = horariosPorGrupo[grupoId] || [];
        
        if (horarios.length === 0) {
            horariosContainer.innerHTML = `
                <div class="text-center py-8 bg-amber-50 rounded-xl">
                    <i class="fas fa-exclamation-triangle text-amber-400 text-3xl mb-3"></i>
                    <p class="text-amber-600 font-medium">Este grupo no tiene horarios asignados</p>
                    <p class="text-amber-500 text-sm">Primero asigne horarios al grupo</p>
                </div>
            `;
            return;
        }

        let html = '';
        horarios.forEach((horario, index) => {
            html += `
                <div class="horario-item bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-purple-200 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 sm:mb-4">
                        <h5 class="text-sm sm:text-md font-bold text-purple-900 flex items-center">
                            <i class="fas fa-clock mr-2 text-purple-600 text-sm sm:text-base"></i>
                            Sesión #${index + 1}
                        </h5>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium mt-2 sm:mt-0">
                            ${horario.dia} - ${horario.hora_inicio.substring(0, 5)} a ${horario.hora_fin.substring(0, 5)}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-purple-700 mb-2">
                                Aula Actual
                            </label>
                            <div class="px-3 py-2 bg-gray-100 rounded-lg text-gray-700 text-sm">
                                ${horario.aula_actual}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-purple-700 mb-2">
                                Docente Asignado
                            </label>
                            <div class="px-3 py-2 bg-blue-50 rounded-lg text-blue-700 text-sm">
                                ${horario.codigo_docente}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-purple-700 mb-2">
                                Nueva Aula *
                            </label>
                            <select name="horarios[${index}][id_aula]" 
                                    class="aula-select w-full px-2 py-2 sm:px-3 sm:py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-xs sm:text-sm"
                                    required>
                                <option value="">Seleccione aula</option>
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}" data-capacidad="{{ $aula->capacidad }}">
                                        {{ $aula->nombre }} (Cap: {{ $aula->capacidad }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="horarios[${index}][id_horario_grupo]" value="${horario.id_horario_grupo}">
                            <input type="hidden" name="horarios[${index}][id_horario]" value="${horario.id_horario}">
                        </div>
                    </div>
                </div>
            `;
        });

        horariosContainer.innerHTML = html;
        
        // Agregar event listeners a los nuevos selects
        document.querySelectorAll('.aula-select').forEach(select => {
            select.addEventListener('change', actualizarBoton);
        });
        
        actualizarBoton();
    }

    // Función para verificar si el formulario está completo
    function verificarFormulario() {
        const grupoSeleccionado = grupoSelect && grupoSelect.value !== '';
        
        if (!grupoSeleccionado) {
            return false;
        }

        // Verificar que todos los selects de aula tengan valor
        const aulaSelects = document.querySelectorAll('.aula-select');
        let todasAulasSeleccionadas = true;
        
        if (aulaSelects.length === 0) {
            return false; // No hay horarios para este grupo
        }

        aulaSelects.forEach(select => {
            if (select.value === '') {
                todasAulasSeleccionadas = false;
            }
        });

        return todasAulasSeleccionadas;
    }

    // Función para actualizar el estado del botón
    function actualizarBoton() {
        const formularioCompleto = verificarFormulario();
        
        if (btnSubmit) {
            btnSubmit.disabled = !formularioCompleto;
            
            if (formularioCompleto) {
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                if (estadoProgramacion) estadoProgramacion.classList.remove('hidden');
                console.log('✅ BOTÓN HABILITADO - Formulario válido');
            } else {
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                if (estadoProgramacion) estadoProgramacion.classList.add('hidden');
                console.log('❌ BOTÓN DESHABILITADO - Formulario incompleto');
            }
        }
    }

    // Event listener para cambio de grupo
    if (grupoSelect) {
        grupoSelect.addEventListener('change', function() {
            const grupoId = this.value;
            
            if (grupoId) {
                if (noGrupoSeleccionado) {
                    noGrupoSeleccionado.style.display = 'none';
                }
                cargarHorariosGrupo(grupoId);
            } else {
                if (noGrupoSeleccionado) {
                    noGrupoSeleccionado.style.display = 'block';
                }
                horariosContainer.innerHTML = '';
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                if (estadoProgramacion) estadoProgramacion.classList.add('hidden');
            }
        });
    }

    // Validación de envío
    const form = document.getElementById('asignacionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!verificarFormulario()) {
                e.preventDefault();
                alert('Por favor, complete todos los campos requeridos antes de enviar.');
            }
        });
    }
});
</script>
@endif
@endsection