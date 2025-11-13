@extends('layouts.app')

@section('title', 'Reporte de Horarios Semanales')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-calendar-week mr-3"></i>
                    Reporte de Horarios Semanales
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Genera reportes PDF de horarios académicos por semana específica
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
            <form action="{{ route('admin.reportes.horarios-semanales.generar') }}" method="POST" id="formReporte">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <!-- Columna 1: Configuración Básica -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Tipo de Vista -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-eye mr-2"></i>Tipo de Vista *
                            </label>
                            <select name="tipo_vista" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base" required>
                                <option value="">Seleccione una vista</option>
                                <option value="docente">👨‍🏫 Vista por Docente</option>
                                <option value="aula">🏫 Vista por Aula</option>
                                <option value="grupo">👥 Vista por Grupo</option>
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
                                        {{ $gestionActiva && $gestionActiva->id == $gestion->id ? 'selected' : '' }}>
                                        {{ $gestion->gestion }}
                                        @if($gestion->estado == 'curso')
                                            (En Curso)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Columna 2: Semana y Horarios -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Semana -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-calendar-week mr-2"></i>Semana del Año *
                            </label>
                            <select name="semana" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base" required>
                                <option value="">Seleccione una semana</option>
                                @foreach($semanas as $semana)
                                    <option value="{{ $semana['numero'] }}" 
                                        {{ $semana['numero'] == date('W') ? 'selected' : '' }}>
                                        Semana {{ $semana['numero'] }} ({{ $semana['rango'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rango de Horas -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-clock mr-2"></i>Rango de Horas
                            </label>
                            <select name="rango_horas" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="todo">🌞 Todo el día</option>
                                <option value="manana">☀️ Mañana (06:00 - 12:00)</option>
                                <option value="tarde">🌇 Tarde (12:00 - 18:00)</option>
                                <option value="noche">🌙 Noche (18:00 - 22:00)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Filtros Específicos -->
                <div class="bg-deep-teal-25 rounded-2xl p-4 sm:p-6 border border-deep-teal-100 mb-6">
                    <h4 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filtros Específicos
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Filtro por Docente -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>Filtrar por Docente
                            </label>
                            <select name="id_docente" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="">Todos los docentes</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente['codigo'] }}">
                                        {{ $docente['nombre'] }} ({{ $docente['codigo'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Aula -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-door-open mr-2"></i>Filtrar por Aula
                            </label>
                            <select name="id_aula" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="">Todas las aulas</option>
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}">
                                        {{ $aula->nombre }} - {{ $aula->tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Grupo -->
                        <div class="space-y-2">
                            <label class="font-weight-bold text-deep-teal-800 text-sm sm:text-base">
                                <i class="fas fa-users mr-2"></i>Filtrar por Grupo
                            </label>
                            <select name="id_grupo" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 bg-white text-sm sm:text-base">
                                <option value="">Todos los grupos</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">
                                        {{ $grupo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
<!-- Botones de Acción -->
<div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-deep-teal-100">
    <button type="submit" name="exportar_pdf" value="1" 
            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
        <i class="fas fa-file-pdf mr-2"></i>
        <span class="hidden xs:inline">Exportar</span> PDF
    </button>
    <button type="submit" name="exportar_csv" value="1" 
            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
        <i class="fas fa-file-csv mr-2"></i>
        <span class="hidden xs:inline">Exportar</span> CSV
    </button>
    <button type="submit" name="exportar_xlsx" value="1" 
            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
        <i class="fas fa-file-excel mr-2"></i>
        <span class="hidden xs:inline">Exportar</span> XLSX
    </button>
    <button type="submit"
            class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
        <i class="fas fa-eye mr-2"></i>
        Vista Previa
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
    @media (max-width: 480px) {
        .gradient-bg {
            background: linear-gradient(135deg, #012E40 0%, #024959 100%);
        }
        
        /* Mejoras específicas para móviles muy pequeños */
        .text-xs {
            font-size: 0.75rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 640px) {
        /* Asegurar que los selects sean legibles en móviles */
        select {
            font-size: 16px; /* Previene el zoom en iOS */
        }
        
        /* Mejor espaciado para botones en móviles */
        .flex-col .flex-1 {
            min-height: 44px; /* Tamaño mínimo táctil */
        }
    }
</style>

<script>
    // Mejorar la experiencia en móviles
    document.addEventListener('DOMContentLoaded', function() {
        // Prevenir zoom en inputs en iOS
        document.querySelectorAll('select, input').forEach(element => {
            element.addEventListener('focus', function() {
                this.style.fontSize = '16px';
            });
            
            element.addEventListener('blur', function() {
                this.style.fontSize = '';
            });
        });
        
        // Validación básica del formulario
        const form = document.getElementById('formReporte');
        form.addEventListener('submit', function(e) {
            const tipoVista = form.querySelector('select[name="tipo_vista"]');
            const gestion = form.querySelector('select[name="id_gestion"]');
            const semana = form.querySelector('select[name="semana"]');
            
            if (!tipoVista.value || !gestion.value || !semana.value) {
                e.preventDefault();
                alert('Por favor complete todos los campos obligatorios (*)');
                return false;
            }
        });
        
        // Efecto visual para botones en móviles
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('touchstart', function() {
                this.style.transform = 'translateY(1px)';
            });
            
            button.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
    });
</script>
@endsection