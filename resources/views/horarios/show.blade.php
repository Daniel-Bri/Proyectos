<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Horario</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Detalles del Horario</h1>
                        <p class="text-xs text-cream-300">Información completa de la asignación</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('coordinador.horarios.edit', $horarioAsignado->id) }}"
                       class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-600 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('coordinador.horarios.index') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-4 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- INFORMACIÓN PRINCIPAL -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-6 text-cream-200 border-b border-deep-teal-400 pb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Información del Horario
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- HORARIO -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-cream-200 border-b border-deep-teal-400 pb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Horario
                        </h3>
                        
                        <div class="flex items-center justify-between p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                            <div>
                                <div class="text-cream-300 text-sm">Día</div>
                                <div class="text-cream-200 font-semibold text-lg">
                                    @php
                                        $dias = [
                                            'LUN' => 'Lunes',
                                            'MAR' => 'Martes', 
                                            'MIE' => 'Miércoles',
                                            'JUE' => 'Jueves',
                                            'VIE' => 'Viernes',
                                            'SAB' => 'Sábado'
                                        ];
                                    @endphp
                                    {{ $dias[$horarioAsignado->horario->dia] ?? $horarioAsignado->horario->dia }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-cream-300 text-sm">Horas</div>
                                <div class="text-cream-200 font-semibold text-lg">
                                    {{ \Carbon\Carbon::parse($horarioAsignado->horario->hora_inicio)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($horarioAsignado->horario->hora_fin)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ESTADO -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-cream-200 border-b border-deep-teal-400 pb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Estado
                        </h3>
                        
                        <div class="p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                            <div class="flex items-center gap-3">
                                @if($horarioAsignado->estado_aula == 'ocupado')
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    <div>
                                        <div class="text-cream-300 text-sm">Estado del Aula</div>
                                        <div class="text-cream-200 font-semibold">Ocupada</div>
                                    </div>
                                @else
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div>
                                        <div class="text-cream-300 text-sm">Estado del Aula</div>
                                        <div class="text-cream-200 font-semibold">Disponible</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN ACADÉMICA -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-6 text-cream-200 border-b border-deep-teal-400 pb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    </svg>
                    Información Académica
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- DOCENTE -->
                    <div class="text-center p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                        <svg class="w-8 h-8 text-cream-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div class="text-cream-300 text-sm">Docente</div>
                        <div class="text-cream-200 font-semibold truncate">
                            {{ $horarioAsignado->docente->user->name ?? 'No asignado' }}
                        </div>
                        <div class="text-cream-400 text-xs mt-1">
                            {{ $horarioAsignado->docente->codigo ?? 'Sin código' }}
                        </div>
                    </div>

                    <!-- MATERIA -->
                    <div class="text-center p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                        <svg class="w-8 h-8 text-cream-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div class="text-cream-300 text-sm">Materia</div>
                        <div class="text-cream-200 font-semibold truncate">
                            {{ $horarioAsignado->grupoMateria->materia->nombre ?? 'No asignada' }}
                        </div>
                        <div class="text-cream-400 text-xs mt-1">
                            {{ $horarioAsignado->grupoMateria->materia->sigla ?? 'Sin sigla' }}
                        </div>
                    </div>

                    <!-- GRUPO -->
                    <div class="text-center p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                        <svg class="w-8 h-8 text-cream-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div class="text-cream-300 text-sm">Grupo</div>
                        <div class="text-cream-200 font-semibold">
                            {{ $horarioAsignado->grupoMateria->grupo->nombre ?? 'No asignado' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN DEL AULA -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-6 text-cream-200 border-b border-deep-teal-400 pb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Información del Aula
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- AULA ASIGNADA -->
                    <div class="p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 text-cream-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <div>
                                <div class="text-cream-300 text-sm">Aula Asignada</div>
                                <div class="text-cream-200 font-semibold text-lg">
                                    {{ $horarioAsignado->aula->nombre ?? 'No asignada' }}
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-cream-400">Tipo</div>
                                <div class="text-cream-200 font-medium">
                                    {{ $horarioAsignado->aula->tipo ?? 'No especificado' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-cream-400">Capacidad</div>
                                <div class="text-cream-200 font-medium">
                                    {{ $horarioAsignado->aula->capacidad ?? '0' }} estudiantes
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMACIÓN ADICIONAL -->
                    <div class="p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 text-cream-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-cream-200 font-semibold">Información Adicional</div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-cream-400">ID Asignación:</span>
                                <span class="text-cream-200 font-mono">{{ $horarioAsignado->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-cream-400">Creado:</span>
                                <span class="text-cream-200">{{ $horarioAsignado->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-cream-400">Actualizado:</span>
                                <span class="text-cream-200">{{ $horarioAsignado->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCIONES -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('coordinador.horarios.edit', $horarioAsignado->id) }}"
                   class="flex-1 bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Horario
                </a>
                <form action="{{ route('coordinador.horarios.destroy', $horarioAsignado->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('¿Estás seguro de eliminar este horario? Esta acción no se puede deshacer.')"
                            class="w-full bg-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600 transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar Horario
                    </button>
                </form>
                <a href="{{ route('coordinador.horarios.index') }}"
                   class="flex-1 bg-deep-teal-600 text-cream-200 px-6 py-3 rounded-lg font-semibold hover:bg-deep-teal-500 transition shadow-md flex items-center justify-center gap-2 border border-deep-teal-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Lista
                </a>
            </div>
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