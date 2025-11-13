@extends('layouts.app')

@section('title', 'Reporte de Asistencia por Docente/Grupo')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    Reporte de Asistencia
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Genera reportes PDF/Excel de asistencia por docente o grupo
                </p>
            </div>
            <!-- Botón Volver al Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-[#F2E3D5] hover:bg-[#e6d7c9] text-deep-teal-800 font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Formulario de Reportes -->
        <div class="bg-white rounded-2xl border border-deep-teal-100 shadow-lg p-4 sm:p-6 mb-6">
            {{-- FORMULARIO PRINCIPAL PARA VISTA PREVIA (GET) --}}
            <form action="{{ route('admin.reportes.asistencia.vista-previa') }}" method="GET" id="formReporte">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <!-- Columna 1: Configuración Básica -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Tipo de Reporte -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-chart-bar mr-2"></i>Tipo de Reporte *
                            </label>
                            <select name="tipo_reporte" id="tipo_reporte" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="docente" {{ old('tipo_reporte', request('tipo_reporte')) == 'docente' ? 'selected' : '' }}>👨‍🏫 Por Docente</option>
                                <option value="grupo" {{ old('tipo_reporte', request('tipo_reporte')) == 'grupo' ? 'selected' : '' }}>👥 Por Grupo</option>
                            </select>
                        </div>

                        <!-- Gestión Académica -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-calendar mr-2"></i>Gestión Académica *
                            </label>
                            <select name="id_gestion" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base" required>
                                <option value="">Seleccione una gestión</option>
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}" 
                                        {{ ($gestionActiva && $gestionActiva->id == $gestion->id) || old('id_gestion', request('id_gestion')) == $gestion->id ? 'selected' : '' }}>
                                        {{ $gestion->gestion }}
                                        @if($gestion->estado == 'curso')
                                            (En Curso)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rango de Fechas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                    <i class="fas fa-calendar-day mr-2"></i>Fecha Inicio *
                                </label>
                                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', request('fecha_inicio')) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base" required>
                            </div>
                            <div class="space-y-2">
                                <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                    <i class="fas fa-calendar-day mr-2"></i>Fecha Fin *
                                </label>
                                <input type="date" name="fecha_fin" value="{{ old('fecha_fin', request('fecha_fin')) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base" required>
                            </div>
                        </div>
                    </div>

                    <!-- Columna 2: Filtros Específicos -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Filtro por Docente (solo visible para tipo docente) -->
                        <div id="filtro-docente" class="space-y-2 hidden">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>Filtrar por Docente *
                            </label>
                            <select name="id_docente" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="">Seleccione un docente</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente['codigo'] }}" {{ old('id_docente', request('id_docente')) == $docente['codigo'] ? 'selected' : '' }}>
                                        {{ $docente['nombre'] }} ({{ $docente['codigo'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Grupo (solo visible para tipo grupo) -->
                        <div id="filtro-grupo" class="space-y-2 hidden">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-users mr-2"></i>Filtrar por Grupo *
                            </label>
                            <select name="id_grupo" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="">Seleccione un grupo</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}" {{ old('id_grupo', request('id_grupo')) == $grupo->id ? 'selected' : '' }}>
                                        {{ $grupo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Materia -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-book mr-2"></i>Filtrar por Materia
                            </label>
                            <select name="id_materia" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="">Todas las materias</option>
                                @foreach($materias as $materia)
                                    <option value="{{ $materia->sigla }}" {{ old('id_materia', request('id_materia')) == $materia->sigla ? 'selected' : '' }}>
                                        {{ $materia->nombre }} ({{ $materia->sigla }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Estado de Asistencia -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-user-check mr-2"></i>Estado de Asistencia
                            </label>
                            <select name="estado_asistencia" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="todos" {{ old('estado_asistencia', request('estado_asistencia')) == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                                <option value="presente" {{ old('estado_asistencia', request('estado_asistencia')) == 'presente' ? 'selected' : '' }}>✅ Presente</option>
                                <option value="tardanza" {{ old('estado_asistencia', request('estado_asistencia')) == 'tardanza' ? 'selected' : '' }}>⏰ Tardanza</option>
                                <option value="ausente" {{ old('estado_asistencia', request('estado_asistencia')) == 'ausente' ? 'selected' : '' }}>❌ Ausente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-deep-teal-100">
                    {{-- Botón Vista Previa (usa el formulario principal GET) --}}
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-eye mr-2"></i>
                        Vista Previa
                    </button>

                    {{-- Botones de Exportación (formularios POST separados) --}}
                    <button type="button" onclick="exportarReporte('pdf')"
                            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-file-pdf mr-2"></i>
                        <span class="hidden xs:inline">Exportar</span> PDF
                    </button>

                    <button type="button" onclick="exportarReporte('csv')"
                            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-file-csv mr-2"></i>
                        <span class="hidden xs:inline">Exportar</span> CSV
                    </button>

                    <button type="button" onclick="exportarReporte('xlsx')"
                            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-file-excel mr-2"></i>
                        <span class="hidden xs:inline">Exportar</span> XLSX
                    </button>

                    <button type="reset" 
                            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                        <i class="fas fa-redo mr-2"></i>
                        Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #012E40 0%, #024959 100%);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoReporte = document.getElementById('tipo_reporte');
        const filtroDocente = document.getElementById('filtro-docente');
        const filtroGrupo = document.getElementById('filtro-grupo');

        function actualizarFiltros() {
            const valor = tipoReporte.value;
            
            // Ocultar todos los filtros primero
            filtroDocente.classList.add('hidden');
            filtroGrupo.classList.add('hidden');
            
            // Mostrar el filtro correspondiente
            if (valor === 'docente') {
                filtroDocente.classList.remove('hidden');
                // Hacer requerido el campo docente
                filtroDocente.querySelector('select').required = true;
                filtroGrupo.querySelector('select').required = false;
            } else if (valor === 'grupo') {
                filtroGrupo.classList.remove('hidden');
                // Hacer requerido el campo grupo
                filtroGrupo.querySelector('select').required = true;
                filtroDocente.querySelector('select').required = false;
            } else {
                // Ninguno requerido si no hay selección
                filtroDocente.querySelector('select').required = false;
                filtroGrupo.querySelector('select').required = false;
            }
        }

        // Escuchar cambios en el tipo de reporte
        tipoReporte.addEventListener('change', actualizarFiltros);
        
        // Ejecutar al cargar la página
        actualizarFiltros();

        // Establecer fechas por defecto (últimos 30 días)
        const hoy = new Date().toISOString().split('T')[0];
        const hace30Dias = new Date();
        hace30Dias.setDate(hace30Dias.getDate() - 30);
        const fechaHace30Dias = hace30Dias.toISOString().split('T')[0];

        // Solo establecer valores si no hay valores previos
        const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
        const fechaFin = document.querySelector('input[name="fecha_fin"]');
        
        if (!fechaInicio.value) {
            fechaInicio.value = fechaHace30Dias;
        }
        if (!fechaFin.value) {
            fechaFin.value = hoy;
        }

        // Validación del formulario
        const form = document.getElementById('formReporte');
        form.addEventListener('submit', function(e) {
            const tipo = form.querySelector('select[name="tipo_reporte"]');
            const gestion = form.querySelector('select[name="id_gestion"]');
            const fechaInicio = form.querySelector('input[name="fecha_inicio"]');
            const fechaFin = form.querySelector('input[name="fecha_fin"]');
            
            if (!tipo.value || !gestion.value || !fechaInicio.value || !fechaFin.value) {
                e.preventDefault();
                alert('Por favor complete todos los campos obligatorios (*)');
                return false;
            }

            if (new Date(fechaFin.value) < new Date(fechaInicio.value)) {
                e.preventDefault();
                alert('La fecha fin debe ser mayor o igual a la fecha inicio');
                return false;
            }
        });
    });

    // Función para exportar reportes (POST)
    function exportarReporte(tipo) {
        const form = document.getElementById('formReporte');
        
        // Crear un formulario temporal para la exportación
        const tempForm = document.createElement('form');
        tempForm.method = 'POST';
        tempForm.action = '{{ route("admin.reportes.asistencia.generar") }}';
        tempForm.style.display = 'none';
        
        // Agregar token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        tempForm.appendChild(csrfToken);
        
        // Agregar todos los campos del formulario original
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name && input.value) {
                const newInput = document.createElement('input');
                newInput.type = 'hidden';
                newInput.name = input.name;
                newInput.value = input.value;
                tempForm.appendChild(newInput);
            }
        });
        
        // Agregar el tipo de exportación
        const exportInput = document.createElement('input');
        exportInput.type = 'hidden';
        exportInput.name = 'exportar_' + tipo;
        exportInput.value = '1';
        tempForm.appendChild(exportInput);
        
        // Agregar al documento y enviar
        document.body.appendChild(tempForm);
        tempForm.submit();
    }
</script>
@endsection