<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - Aulas Disponibles</title>
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
</head>
<body class="min-h-screen bg-gray-50">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 bg-gradient-to-r from-deep-teal-700 to-deep-teal-800 shadow-lg border-b border-deep-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-cream-200 rounded-lg grid place-items-center">
                        <svg class="w-5 h-5 text-deep-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Resultados del Reporte</h1>
                        <p class="text-xs text-cream-300">Aulas disponibles y ocupadas</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('coordinador.reportes.aulas.disponibles') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Nuevo Reporte
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- ALERTAS -->
            @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- RESULTADOS -->
            @if(isset($aulasDisponibles) && isset($aulasOcupadas))
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-deep-teal-600 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-cream-200">📊 Resultados del Reporte</h2>
                            <p class="text-cream-300 text-sm">
                                Generado el {{ now()->format('d/m/Y H:i') }}
                                @if(isset($filtros['dia']) && $filtros['dia'])
                                    | Día: {{ $filtros['dia'] }}
                                @endif
                                @if(isset($filtros['hora']) && $filtros['hora'])
                                    | Hora: {{ $filtros['hora'] }}
                                @endif
                            </p>
                        </div>
                        <div class="text-cream-200 text-sm mt-2 sm:mt-0">
                            <span class="bg-green-500 text-white px-2 py-1 rounded mr-2">
                                {{ count($aulasDisponibles) }} Disponibles
                            </span>
                            <span class="bg-red-500 text-white px-2 py-1 rounded">
                                {{ count($aulasOcupadas) }} Ocupadas
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- AULAS DISPONIBLES -->
                    <h3 class="text-lg font-semibold text-green-600 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Aulas Disponibles ({{ count($aulasDisponibles) }})
                    </h3>
                    
                    @if(count($aulasDisponibles) > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @foreach($aulasDisponibles as $aula)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-green-800 text-lg">{{ $aula->nombre }}</h4>
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Disponible</span>
                            </div>
                            <div class="space-y-1 text-sm text-green-700">
                                <div class="flex justify-between">
                                    <span>Tipo:</span>
                                    <span class="font-medium capitalize">{{ $aula->tipo }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Capacidad:</span>
                                    <span class="font-medium">{{ $aula->capacidad }} estudiantes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Estado:</span>
                                    <span class="font-medium text-green-600">✓ Libre</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <svg class="w-8 h-8 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p class="text-yellow-700">No hay aulas disponibles con los filtros aplicados</p>
                    </div>
                    @endif

                    <!-- AULAS OCUPADAS -->
                    <h3 class="text-lg font-semibold text-red-600 mb-4 flex items-center gap-2 mt-8">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Aulas Ocupadas ({{ count($aulasOcupadas) }})
                    </h3>
                    
                    @if(count($aulasOcupadas) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="text-left py-3 px-4 text-red-800 font-semibold">Aula</th>
                                    <th class="text-left py-3 px-4 text-red-800 font-semibold">Materia</th>
                                    <th class="text-left py-3 px-4 text-red-800 font-semibold">Docente</th>
                                    <th class="text-left py-3 px-4 text-red-800 font-semibold">Grupo</th>
                                    <th class="text-left py-3 px-4 text-red-800 font-semibold">Horario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aulasOcupadas as $ocupacion)
                                <tr class="border-b border-red-100 hover:bg-red-50 transition">
                                    <td class="py-3 px-4 text-red-700 font-medium">{{ $ocupacion->aula_nombre }}</td>
                                    <td class="py-3 px-4 text-red-700">
                                        {{ $ocupacion->materia_nombre }}
                                        @if(isset($ocupacion->materia_sigla))
                                        <br><span class="text-xs text-red-500">({{ $ocupacion->materia_sigla }})</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-red-700">{{ $ocupacion->docente_nombre }}</td>
                                    <td class="py-3 px-4 text-red-700">
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                                            {{ $ocupacion->grupo_nombre }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-red-700">
                                        <span class="font-medium">{{ $ocupacion->dia }}</span><br>
                                        {{ $ocupacion->hora_inicio }} - {{ $ocupacion->hora_fin }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-700">¡Todas las aulas están disponibles con los filtros aplicados!</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <svg class="w-12 h-12 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">No hay datos para mostrar</h3>
                <p class="text-yellow-700">Genere un reporte primero desde la página principal</p>
                <a href="{{ route('coordinador.reportes.aulas.disponibles') }}" 
                   class="inline-block mt-4 bg-deep-teal-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-deep-teal-700 transition">
                    Ir a Generar Reporte
                </a>
            </div>
            @endif

        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gradient-to-r from-deep-teal-700 to-deep-teal-800 border-t border-deep-teal-700 py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-cream-300 text-sm">
                <p>Sistema de Gestión de Horarios - {{ date('Y') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>