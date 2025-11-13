<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsualización - Asignación Automática</title>
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
    <header class="sticky top-0 z-20 bg-gradient-to-r from-deep-teal-700 to-deep-teal-800 shadow-lg border-b border-deep-teal-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-cream-200 rounded-lg grid place-items-center">
                        <svg class="w-5 h-5 text-deep-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Previsualización de Horarios</h1>
                        <p class="text-xs text-cream-300">Revise y confirme los horarios generados</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('coordinador.asignacion-automatica.index') }}"
                       class="inline-flex items-center gap-2 bg-deep-teal-600 text-cream-200 px-3 py-2 rounded-lg font-medium hover:bg-deep-teal-500 transition shadow-md border border-deep-teal-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- RESUMEN -->
            <div class="bg-gradient-to-r from-deep-teal-700 to-deep-teal-800 rounded-xl p-6 shadow-lg mb-6">
                <div class="grid md:grid-cols-4 gap-4 text-cream-200">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $resumen['total_asignaciones'] ?? 0 }}</div>
                        <div class="text-sm text-cream-300">Total Asignaciones</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $resumen['docentes_asignados'] ?? 0 }}</div>
                        <div class="text-sm text-cream-300">Docentes Asignados</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $resumen['aulas_utilizadas'] ?? 0 }}</div>
                        <div class="text-sm text-cream-300">Aulas Utilizadas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $resumen['conflictos'] ?? 0 }}</div>
                        <div class="text-sm text-cream-300">Conflictos Detectados</div>
                    </div>
                </div>
            </div>

            <!-- HORARIOS GENERADOS -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="bg-deep-teal-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-cream-200">Horarios Generados</h2>
                    <p class="text-cream-300 text-sm">Revise las asignaciones antes de confirmar</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-deep-teal-50">
                            <tr>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Día</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Horario</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Materia</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Docente</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Grupo</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Aula</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold text-sm">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horariosGenerados as $horario)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-gray-700 text-sm">
                                    <span class="bg-deep-teal-100 text-deep-teal-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ $horario['dia'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 text-sm">
                                    {{ $horario['hora_inicio'] }} - {{ $horario['hora_fin'] }}
                                </td>
                                <td class="py-3 px-4 text-gray-700 text-sm">
                                    {{ $horario['materia_nombre'] }}
                                    <span class="text-xs text-gray-500 block">({{ $horario['materia_sigla'] }})</span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 text-sm">
                                    {{ $horario['docente_nombre'] }}
                                    <span class="text-xs text-gray-500 block">({{ $horario['docente_codigo'] }})</span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 text-sm">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                        {{ $horario['grupo_nombre'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 text-sm">
                                    {{ $horario['aula_nombre'] }}
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-medium 
                                        {{ $horario['conflicto'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $horario['conflicto'] ? 'Conflicto' : 'OK' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                    No se generaron horarios para previsualizar
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CONFLICTOS DETECTADOS -->
            @if($conflictos && count($conflictos) > 0)
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-800">Conflictos Detectados</h3>
                </div>
                <ul class="space-y-2">
                    @foreach($conflictos as $conflicto)
                    <li class="text-red-700 text-sm">• {{ $conflicto }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- ACCIONES -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('coordinador.asignacion-automatica.index') }}"
                   class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition shadow-md text-center">
                    Cancelar y Volver
                </a>
                
                <form action="{{ route('coordinador.asignacion-automatica.confirmar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_gestion" value="{{ $idGestion }}">
                    <input type="hidden" name="tipo_asignacion" value="{{ $tipoAsignacion }}">
                    <input type="hidden" name="max_horas_docente" value="{{ $maxHorasDocente }}">
                    
                    <button type="submit" 
                            class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirmar Asignación
                    </button>
                </form>
            </div>

        </section>
    </main>
</body>
</html>