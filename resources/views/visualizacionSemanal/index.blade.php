<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización Semanal de Horarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        accent: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-secondary-900 text-white">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 bg-secondary-900/95 backdrop-blur border-b border-secondary-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-accent-500 rounded-lg grid place-items-center">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10m-12 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold">Horarios Semanales</h1>
                        <p class="text-xs text-secondary-400">Visualización por docente y grupo-materia</p>
                    </div>
                </div>

                <a href="{{ url('/dashboard') }}"
                   class="inline-flex items-center gap-2 bg-accent-500 text-white px-3 py-2 rounded-lg font-medium hover:bg-accent-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- DEBUG TEMPORAL - ELIMINAR DESPUÉS -->
    <div class="bg-yellow-500 text-black p-4 mx-4 mt-4 rounded-lg">
        <h3 class="font-bold">DEBUG INFO:</h3>
        <p>Docente seleccionado: <strong>{{ $docenteId ?? 'Ninguno' }}</strong></p>
        <p>Total días con datos: <strong>{{ count($horariosFormateados) }}</strong></p>
        <p>Estructura de datos:</p>
        <pre class="text-xs bg-black text-green-400 p-2 rounded mt-2 overflow-auto">
    @foreach ($horariosFormateados as $dia => $info)
    {{ $dia }}: {{ count($info['horarios']) }} horarios
    @endforeach
        </pre>
    </div>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- FILTROS PRINCIPALES -->
            <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-4 sm:p-6">
                <h2 class="text-lg font-semibold mb-4 text-white">Filtros de Búsqueda</h2>
                <form method="GET" class="grid lg:grid-cols-3 gap-4">
                    <!-- FILTRO POR DOCENTE -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-secondary-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Buscar por Docente
                        </label>
                        <select name="docente_id" class="w-full bg-secondary-700 text-white rounded-lg border border-secondary-600 p-3 focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                            <option value="">-- Seleccionar docente --</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente['codigo'] }}" {{ $docenteId == $docente['codigo'] ? 'selected' : '' }}>
                                    {{ $docente['codigo'] }} - {{ $docente['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- FILTRO POR MATERIA -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-secondary-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Buscar por Materia
                        </label>
                        <select name="materia_id" class="w-full bg-secondary-700 text-white rounded-lg border border-secondary-600 p-3 focus:ring-2 focus:ring-accent-500 focus:border-transparent">
                            <option value="">-- Seleccionar materia --</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->sigla }}" {{ $materiaId == $materia->sigla ? 'selected' : '' }}>
                                    {{ $materia->sigla }} - {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- BOTÓN DE BÚSQUEDA -->
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-accent-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-accent-600 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar Horarios
                        </button>
                    </div>
                </form>
            </div>

            <!-- CONTROLES DE SEMANA -->
            <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-white mb-1">Semana Actual</h2>
                        <p class="text-secondary-300 text-sm">
                            {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaInicio->copy()->endOfWeek()->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="?fecha_inicio={{ $fechaInicio->copy()->subWeek()->format('Y-m-d') }}&docente_id={{ $docenteId }}&materia_id={{ $materiaId }}"
                           class="bg-secondary-700 text-white px-3 py-2 rounded-lg hover:bg-secondary-600 transition flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Anterior
                        </a>
                        <a href="?fecha_inicio={{ now()->format('Y-m-d') }}&docente_id={{ $docenteId }}&materia_id={{ $materiaId }}"
                           class="bg-accent-500 text-white px-4 py-2 rounded-lg hover:bg-accent-600 transition text-sm">
                            Semana Actual
                        </a>
                        <a href="?fecha_inicio={{ $fechaInicio->copy()->addWeek()->format('Y-m-d') }}&docente_id={{ $docenteId }}&materia_id={{ $materiaId }}"
                           class="bg-secondary-700 text-white px-3 py-2 rounded-lg hover:bg-secondary-600 transition flex items-center gap-2 text-sm">
                            Siguiente
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TARJETAS DE HORARIOS - VISTA RESPONSIVE -->
            <div class="space-y-4">
                @forelse ($horariosFormateados as $dia => $info)
                    @if(count($info['horarios']) > 0)
                    <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-4 sm:p-6">
                        <!-- ENCABEZADO DEL DÍA -->
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-secondary-600">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-500 rounded-lg grid place-items-center">
                                    <span class="text-white font-semibold text-sm">
                                        {{ substr($dia, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $dia }}</h3>
                                    <p class="text-secondary-400 text-sm">{{ \Carbon\Carbon::parse($info['fecha'])->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <span class="bg-secondary-700 text-secondary-300 px-2 py-1 rounded text-sm">
                                {{ count($info['horarios']) }} clase(s)
                            </span>
                        </div>

                        <!-- TARJETAS DE CLASES -->
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach ($info['horarios'] as $horario)
                            <div class="bg-gradient-to-br from-secondary-700 to-secondary-800 border border-secondary-600 rounded-lg p-4 hover:border-primary-500/50 transition-all duration-200">
                                <!-- HORARIO -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="bg-primary-500 text-white px-2 py-1 rounded text-sm font-medium">
                                        {{ $horario['hora_inicio'] }} - {{ $horario['hora_fin'] }}
                                    </span>
                                    <span class="text-xs text-secondary-400 bg-secondary-600 px-2 py-1 rounded">
                                        {{ $horario['duracion'] }}h
                                    </span>
                                </div>

                                <!-- MATERIA -->
                                <div class="mb-2">
                                    <span class="text-xs text-secondary-400 block mb-1">Materia</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $horario['color'] }}"></div>
                                        <span class="text-white font-medium text-sm">{{ $horario['materia'] }}</span>
                                    </div>
                                </div>

                                <!-- GRUPO -->
                                <div class="mb-2">
                                    <span class="text-xs text-secondary-400 block mb-1">Grupo</span>
                                    <span class="text-white text-sm bg-secondary-600 px-2 py-1 rounded">{{ $horario['grupo'] }}</span>
                                </div>

                                <!-- AULA -->
                                <div class="mb-3">
                                    <span class="text-xs text-secondary-400 block mb-1">Aula</span>
                                    <span class="text-white text-sm">{{ $horario['aula'] }}</span>
                                </div>

                                <!-- ACCIÓN -->
                                <div class="flex justify-between items-center pt-2 border-t border-secondary-600">
                                    <span class="text-xs text-secondary-400">{{ $horario['docente'] }}</span>
                                    <a href="{{ route('visualizacion-semana.show', $horario['id']) }}"
                                       class="text-primary-400 hover:text-primary-300 transition text-xs flex items-center gap-1">
                                        Detalles
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @empty
                    <!-- ESTADO VACÍO -->
                    <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-8 text-center">
                        <svg class="w-16 h-16 text-secondary-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white mb-2">No se encontraron horarios</h3>
                        <p class="text-secondary-400 mb-4">
                            @if($docenteId || $materiaId)
                                No hay horarios para los filtros aplicados. Intenta con otros criterios de búsqueda.
                            @else
                                No hay horarios registrados para esta semana.
                            @endif
                        </p>
                        @if($docenteId || $materiaId)
                            <a href="?fecha_inicio={{ $fechaInicio->format('Y-m-d') }}"
                               class="inline-flex items-center gap-2 bg-primary-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpiar filtros
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- RESUMEN ESTADÍSTICAS -->
            @php
                $totalClases = 0;
                foreach ($horariosFormateados as $info) {
                    $totalClases += count($info['horarios']);
                }
            @endphp
            @if($totalClases > 0)
            <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Resumen Semanal</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-secondary-700 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-accent-400">{{ $totalClases }}</div>
                        <div class="text-sm text-secondary-300">Total Clases</div>
                    </div>
                    <div class="bg-secondary-700 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary-400">
                            @php
                                $diasConClases = 0;
                                foreach ($horariosFormateados as $info) {
                                    if (count($info['horarios']) > 0) {
                                        $diasConClases++;
                                    }
                                }
                                echo $diasConClases;
                            @endphp
                        </div>
                        <div class="text-sm text-secondary-300">Días con Clases</div>
                    </div>
                    <div class="bg-secondary-700 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-400">
                            @php
                                $horasTotales = 0;
                                foreach ($horariosFormateados as $info) {
                                    foreach ($info['horarios'] as $horario) {
                                        $horasTotales += $horario['duracion'];
                                    }
                                }
                                echo $horasTotales;
                            @endphp
                        </div>
                        <div class="text-sm text-secondary-300">Horas Totales</div>
                    </div>
                    <div class="bg-secondary-700 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-400">
                            @php
                                $materiasUnicas = [];
                                foreach ($horariosFormateados as $info) {
                                    foreach ($info['horarios'] as $horario) {
                                        $materiasUnicas[$horario['materia']] = true;
                                    }
                                }
                                echo count($materiasUnicas);
                            @endphp
                        </div>
                        <div class="text-sm text-secondary-300">Materias Diferentes</div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-secondary-900 border-t border-secondary-800 py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-secondary-400 text-sm">
                <p>Sistema de Gestión de Horarios - {{ date('Y') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>