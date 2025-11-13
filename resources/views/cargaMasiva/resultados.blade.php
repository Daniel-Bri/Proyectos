<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - Carga Masiva</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Resultados de Importación</h1>
                        <p class="text-xs text-cream-300">Resumen del proceso de carga masiva</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.carga-masiva.usuarios.index') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- RESUMEN ESTADÍSTICAS -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-cream-200 mb-6">Resumen de Importación</h2>
                
                <div class="grid md:grid-cols-4 gap-6 text-center">
                    <div class="bg-deep-teal-600 rounded-lg p-4 border border-deep-teal-400">
                        <div class="text-2xl font-bold text-cream-200">{{ $totalProcesados }}</div>
                        <div class="text-cream-300 text-sm">Total Procesados</div>
                    </div>
                    <div class="bg-green-600 rounded-lg p-4 border border-green-400">
                        <div class="text-2xl font-bold text-white">{{ $resultados['exitosos'] }}</div>
                        <div class="text-green-100 text-sm">Éxitos</div>
                    </div>
                    <div class="bg-red-600 rounded-lg p-4 border border-red-400">
                        <div class="text-2xl font-bold text-white">{{ count($resultados['errores']) }}</div>
                        <div class="text-red-100 text-sm">Errores</div>
                    </div>
                    <div class="bg-blue-600 rounded-lg p-4 border border-blue-400">
                        <div class="text-2xl font-bold text-white">
                            {{ $totalProcesados > 0 ? number_format(($resultados['exitosos'] / $totalProcesados) * 100, 1) : 0 }}%
                        </div>
                        <div class="text-blue-100 text-sm">Tasa de Éxito</div>
                    </div>
                </div>

                <!-- BARRA DE PROGRESO -->
                <!-- BARRA DE PROGRESO -->
                <div class="mt-6">
                    <div class="flex justify-between text-cream-300 text-sm mb-2">
                        <span>Progreso de importación</span>
                        <span>{{ $resultados['exitosos'] }}/{{ $totalProcesados }}</span>
                    </div>
                    <div class="w-full bg-deep-teal-600 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full transition-all duration-500" 
                            style="width: {{ $totalProcesados > 0 ? ($resultados['exitosos'] / $totalProcesados) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- RESULTADO PRINCIPAL -->
            @if($resultados['exitosos'] > 0)
            <div class="bg-green-50 border border-green-200 rounded-xl p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">¡Importación Exitosa!</h3>
                        <p class="text-green-600 text-sm">Se crearon {{ $resultados['exitosos'] }} usuarios correctamente.</p>
                    </div>
                </div>

                <!-- USUARIOS CREADOS -->
                <div class="mt-4">
                    <h4 class="font-semibold text-green-800 mb-3">Usuarios Creados:</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-green-100">
                                <tr>
                                    <th class="text-left py-2 px-3 text-green-800 font-semibold">Email</th>
                                    <th class="text-left py-2 px-3 text-green-800 font-semibold">Nombre</th>
                                    <th class="text-left py-2 px-3 text-green-800 font-semibold">Rol</th>
                                    <th class="text-left py-2 px-3 text-green-800 font-semibold">Código</th>
                                    <th class="text-left py-2 px-3 text-green-800 font-semibold">Contraseña</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-green-200">
                                @foreach($resultados['usuarios_creados'] as $usuario)
                                <tr class="hover:bg-green-50">
                                    <td class="py-2 px-3 text-green-700">{{ $usuario['email'] }}</td>
                                    <td class="py-2 px-3 text-green-700">{{ $usuario['name'] }}</td>
                                    <td class="py-2 px-3">
                                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs font-medium">
                                            {{ $usuario['rol'] }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-green-700 font-mono text-xs">
                                        {{ $usuario['codigo_docente'] }}
                                    </td>
                                    <td class="py-2 px-3">
                                        <code class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-mono">
                                            {{ $usuario['password_generada'] }}
                                        </code>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- ERRORES DURANTE LA IMPORTACIÓN -->
            @if(count($resultados['errores']) > 0)
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Errores Durante la Importación</h3>
                        <p class="text-red-600 text-sm">Se encontraron {{ count($resultados['errores']) }} errores.</p>
                    </div>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @foreach($resultados['errores'] as $error)
                    <div class="bg-white border border-red-200 rounded-lg p-3 text-red-700 text-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>{{ $error }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- ACCIONES FINALES -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6 border-t border-gray-200">
                <a href="{{ route('admin.carga-masiva.usuarios.index') }}"
                   class="px-6 py-3 bg-deep-teal-500 text-cream-200 rounded-lg font-semibold hover:bg-deep-teal-400 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nueva Importación
                </a>

                @if($resultados['exitosos'] > 0)
                <button onclick="window.print()"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir Reporte
                </button>
                @endif
            </div>

            <!-- INFORMACIÓN ADICIONAL -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <h4 class="font-semibold text-yellow-800 mb-3">📋 Información Importante</h4>
                <div class="space-y-2 text-sm text-yellow-700">
                    <p>• Las contraseñas mostradas son temporales y los usuarios deberán cambiarlas en su primer acceso.</p>
                    <p>• Los usuarios con rol "docente" tienen acceso al sistema de registro de asistencia.</p>
                    <p>• Los coordinadores pueden gestionar horarios y asignaciones académicas.</p>
                    <p>• Los administradores tienen acceso completo al sistema.</p>
                </div>
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

    <script>
        // Auto-scroll para errores
        document.addEventListener('DOMContentLoaded', function() {
            const erroresSection = document.querySelector('.bg-red-50');
            if (erroresSection) {
                erroresSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    </script>
</body>
</html>