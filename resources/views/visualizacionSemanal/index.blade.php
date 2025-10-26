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
                        'deep-teal': {
                            25: '#f0f7f7',
                            50: '#e0f0f0',
                            100: '#c4e4e4',
                            200: '#9dd1d1',
                            300: '#6fb6b6',
                            400: '#3ca6a6',
                            500: '#026773',
                            600: '#024954',
                            700: '#012e36',
                            800: '#01242a',
                            900: '#011a1f',
                        },
                        'cream': {
                            50: '#fdf8f4',
                            100: '#faf1ea',
                            200: '#f2e3d5',
                            300: '#e8d5c4',
                            400: '#ddc7b3',
                            500: '#d4baa2',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
        }
        .gradient-header {
            background: linear-gradient(135deg, #012E40 0%, #024959 100%);
        }
        .gradient-card {
            background: linear-gradient(135deg, #012E40 0%, #024959 100%);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 gradient-header shadow-lg border-b border-deep-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-cream-200 rounded-lg grid place-items-center">
                        <svg class="w-5 h-5 text-deep-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10m-12 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Horarios Semanales</h1>
                        <p class="text-xs text-cream-300">Visualización por docente, grupo y materia</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Botón para vista calendario -->
                    <a href="{{ route('visualizacion-semana.calendario') }}?codigo_docente={{ request('codigo_docente') }}&materia_id={{ request('materia_id') }}&grupo_id={{ request('grupo_id') }}"
                        class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-4 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        Vista Calendario
                    </a>
                    
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- FILTROS PRINCIPALES -->
            <div class="gradient-card rounded-xl p-4 sm:p-6 shadow-lg">
                <h2 class="text-lg font-semibold mb-4 text-cream-200">Filtros de Búsqueda</h2>
                <form method="GET" class="grid lg:grid-cols-4 gap-4">
                    <!-- FILTRO POR CÓDIGO DE DOCENTE -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-cream-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            Código de Docente
                        </label>
                        <input type="text" 
                               name="codigo_docente" 
                               value="{{ request('codigo_docente') }}"
                               placeholder="Ej: DOC001"
                               class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent placeholder-cream-400">
                    </div>

                    <!-- FILTRO POR MATERIA -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-cream-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Buscar por Materia
                        </label>
                        <select name="materia_id" class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                            <option value="" class="bg-deep-teal-700">-- Seleccionar materia --</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->sigla }}" {{ request('materia_id') == $materia->sigla ? 'selected' : '' }} class="bg-deep-teal-700">
                                    {{ $materia->sigla }} - {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- FILTRO POR GRUPO -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-cream-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Buscar por Grupo
                        </label>
                        <select name="grupo_id" class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                            <option value="" class="bg-deep-teal-700">-- Seleccionar grupo --</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo['id'] }}" {{ request('grupo_id') == $grupo['id'] ? 'selected' : '' }} class="bg-deep-teal-700">
                                    {{ $grupo['codigo'] }} - {{ $grupo['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- BOTÓN DE BÚSQUEDA -->
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="w-full bg-cream-200 text-deep-teal-700 px-6 py-3 rounded-lg font-medium hover:bg-cream-300 transition shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                        <a href="?"
                           class="bg-deep-teal-600 text-cream-200 px-4 py-3 rounded-lg font-medium hover:bg-deep-teal-500 transition shadow-md flex items-center justify-center border border-deep-teal-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    </div>
                </form>
            </div>

            <!-- INDICADORES DE FILTROS ACTIVOS -->
            @if(request('codigo_docente') || request('materia_id') || request('grupo_id'))
            <div class="bg-deep-teal-500/80 backdrop-blur border border-deep-teal-400 rounded-xl p-4 shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-cream-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="text-cream-200 font-medium">Filtros aplicados:</span>
                        <div class="flex flex-wrap gap-2">
                            @if(request('codigo_docente'))
                                <span class="bg-cream-200 text-deep-teal-700 px-2 py-1 rounded text-sm font-medium">
                                    Docente: {{ request('codigo_docente') }}
                                </span>
                            @endif
                            @if(request('materia_id'))
                                <span class="bg-3ca6a6 text-cream-200 px-2 py-1 rounded text-sm font-medium" style="background-color: #3CA6A6;">
                                    Materia: {{ request('materia_id') }}
                                </span>
                            @endif
                            @if(request('grupo_id'))
                                <span class="bg-cream-300 text-deep-teal-700 px-2 py-1 rounded text-sm font-medium">
                                    Grupo: {{ request('grupo_id') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="?"
                       class="inline-flex items-center gap-2 text-cream-200 hover:text-cream-300 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpiar filtros
                    </a>
                </div>
            </div>
            @endif

            <!-- TARJETAS DE HORARIOS - VISTA RESPONSIVE -->
            <div class="space-y-4">
                @forelse ($horariosFormateados as $dia => $info)
                    @if(count($info['horarios']) > 0)
                    <div class="gradient-card rounded-xl p-4 sm:p-6 shadow-lg">
                        <!-- ENCABEZADO DEL DÍA -->
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-deep-teal-400">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cream-200 rounded-lg grid place-items-center shadow-md">
                                    <span class="text-deep-teal-700 font-semibold text-sm">
                                        {{ substr($dia, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-cream-200">{{ $dia }}</h3>
                                    <p class="text-cream-300 text-sm">Horario recurrente del semestre</p>
                                </div>
                            </div>
                            <span class="bg-deep-teal-600 text-cream-200 px-2 py-1 rounded text-sm font-medium border border-deep-teal-400">
                                {{ count($info['horarios']) }} clase(s)
                            </span>
                        </div>

                        <!-- TARJETAS DE CLASES -->
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach ($info['horarios'] as $horario)
                            <div class="bg-gradient-to-br from-deep-teal-600 to-deep-teal-700 border border-deep-teal-400 rounded-lg p-4 hover:border-cream-200 transition-all duration-200 shadow-md">
                                <!-- HORARIO -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="bg-cream-200 text-deep-teal-700 px-2 py-1 rounded text-sm font-medium shadow-sm">
                                        {{ $horario['hora_inicio'] }} - {{ $horario['hora_fin'] }}
                                    </span>
                                    <span class="text-xs text-cream-300 bg-deep-teal-500 px-2 py-1 rounded border border-deep-teal-400">
                                        {{ $horario['duracion'] }}h
                                    </span>
                                </div>

                                <!-- MATERIA -->
                                <div class="mb-2">
                                    <span class="text-xs text-cream-300 block mb-1">Materia</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $horario['color'] }}"></div>
                                        <span class="text-cream-200 font-medium text-sm">{{ $horario['materia'] }}</span>
                                    </div>
                                </div>

                                <!-- GRUPO -->
                                <div class="mb-2">
                                    <span class="text-xs text-cream-300 block mb-1">Grupo</span>
                                    <span class="text-cream-200 text-sm bg-deep-teal-500 px-2 py-1 rounded border border-deep-teal-400">{{ $horario['grupo'] }}</span>
                                </div>

                                <!-- DOCENTE -->
                                <div class="mb-2">
                                    <span class="text-xs text-cream-300 block mb-1">Docente</span>
                                    <span class="text-cream-200 text-sm">{{ $horario['docente'] }}</span>
                                </div>

                                <!-- AULA -->
                                <div class="mb-3">
                                    <span class="text-xs text-cream-300 block mb-1">Aula</span>
                                    <span class="text-cream-200 text-sm">{{ $horario['aula'] }}</span>
                                </div>

                                <!-- ACCIÓN -->
                                <div class="flex justify-between items-center pt-2 border-t border-deep-teal-400">
                                    <span class="text-xs text-cream-300">{{ $horario['codigo_docente'] }}</span>
                                    <a href="{{ route('visualizacion-semana.show', $horario['id']) }}"
                                       class="text-cream-200 hover:text-cream-300 transition text-xs flex items-center gap-1 font-medium">
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
                    <div class="gradient-card rounded-xl p-8 text-center shadow-lg">
                        <svg class="w-16 h-16 text-cream-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-cream-200 mb-2">No se encontraron horarios</h3>
                        <p class="text-cream-300 mb-4">
                            @if(request('codigo_docente') || request('materia_id') || request('grupo_id'))
                                No hay horarios para los filtros aplicados. Intenta con otros criterios de búsqueda.
                            @else
                                No hay horarios registrados para esta semana.
                            @endif
                        </p>
                        @if(request('codigo_docente') || request('materia_id') || request('grupo_id'))
                            <a href="?"
                               class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-4 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
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
            <div class="gradient-card rounded-xl p-4 sm:p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-cream-200 mb-4">Resumen Semanal</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-deep-teal-600 rounded-lg p-4 text-center border border-deep-teal-400 shadow-sm">
                        <div class="text-2xl font-bold text-cream-200">{{ $totalClases }}</div>
                        <div class="text-sm text-cream-300">Total Clases</div>
                    </div>
                    <div class="bg-deep-teal-600 rounded-lg p-4 text-center border border-deep-teal-400 shadow-sm">
                        <div class="text-2xl font-bold text-cream-200">
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
                        <div class="text-sm text-cream-300">Días con Clases</div>
                    </div>
                    <div class="bg-deep-teal-600 rounded-lg p-4 text-center border border-deep-teal-400 shadow-sm">
                        <div class="text-2xl font-bold text-cream-200">
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
                        <div class="text-sm text-cream-300">Horas Totales</div>
                    </div>
                    <div class="bg-deep-teal-600 rounded-lg p-4 text-center border border-deep-teal-400 shadow-sm">
                        <div class="text-2xl font-bold text-cream-200">
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
                        <div class="text-sm text-cream-300">Materias Diferentes</div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="gradient-header border-t border-deep-teal-700 py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-cream-300 text-sm">
                <p>Sistema de Gestión de Horarios - {{ date('Y') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>