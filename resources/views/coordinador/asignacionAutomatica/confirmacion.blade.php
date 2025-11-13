<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación - Asignación Automática</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'deep-teal': {
                            500: '#026773',
                            600: '#024954',
                            700: '#012e36',
                            800: '#01242a',
                        },
                        'cream': {
                            200: '#f2e3d5',
                            300: '#e8d5c4',
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Asignación Completada</h1>
                        <p class="text-xs text-cream-300">Proceso de asignación automática finalizado</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- TARJETA DE CONFIRMACIÓN -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-green-500 px-6 py-8 text-center">
                    <svg class="w-16 h-16 text-white mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-2xl font-bold text-white mb-2">¡Asignación Exitosa!</h2>
                    <p class="text-green-100">Los horarios han sido generados y guardados correctamente</p>
                </div>

                <div class="p-6">
                    <!-- RESUMEN -->
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-deep-teal-700">{{ $resultado['asignaciones_creadas'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Horarios Creados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-deep-teal-700">{{ $resultado['docentes_asignados'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Docentes Asignados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-deep-teal-700">{{ $resultado['aulas_utilizadas'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Aulas Utilizadas</div>
                        </div>
                    </div>

                    <!-- DETALLES -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Detalles de la Asignación</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Gestión Académica:</span>
                                <span class="font-medium">{{ $gestionNombre ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tipo de Asignación:</span>
                                <span class="font-medium">{{ $tipoAsignacion == 'completa' ? 'Completa' : 'Inteligente' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Máximo horas/docente:</span>
                                <span class="font-medium">{{ $maxHorasDocente ?? 0 }} horas</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha de ejecución:</span>
                                <span class="font-medium">{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ACCIONES -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('coordinador.horarios.index') }}"
                           class="px-6 py-3 bg-deep-teal-600 text-white rounded-lg font-semibold hover:bg-deep-teal-700 transition shadow-md text-center">
                            Ver Horarios Asignados
                        </a>
                        
                        <a href="{{ route('coordinador.asignacion-automatica.index') }}"
                           class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition shadow-md text-center">
                            Nueva Asignación
                        </a>

                        <a href="{{ route('coordinador.dashboard') }}"
                           class="px-6 py-3 bg-cream-200 text-deep-teal-700 rounded-lg font-semibold hover:bg-cream-300 transition shadow-md text-center">
                            Ir al Dashboard
                        </a>
                    </div>
                </div>
            </div>

        </section>
    </main>
</body>
</html>