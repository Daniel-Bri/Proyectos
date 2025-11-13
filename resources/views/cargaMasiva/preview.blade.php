<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsualización - Carga Masiva</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Previsualización de Importación</h1>
                        <p class="text-xs text-cream-300">Revise los datos antes de confirmar</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.carga-masiva.usuarios.index') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
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
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- RESUMEN DE IMPORTACIÓN -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <div class="grid md:grid-cols-4 gap-6 text-center">
                    <div class="bg-deep-teal-600 rounded-lg p-4 border border-deep-teal-400">
                        <div class="text-2xl font-bold text-cream-200">{{ $totalRegistros }}</div>
                        <div class="text-cream-300 text-sm">Total Registros</div>
                    </div>
                    <div class="bg-green-600 rounded-lg p-4 border border-green-400">
                        <div class="text-2xl font-bold text-white">{{ $totalRegistros - $totalErrores }}</div>
                        <div class="text-green-100 text-sm">Válidos</div>
                    </div>
                    <div class="bg-red-600 rounded-lg p-4 border border-red-400">
                        <div class="text-2xl font-bold text-white">{{ $totalErrores }}</div>
                        <div class="text-red-100 text-sm">Con Errores</div>
                    </div>
                    <div class="bg-blue-600 rounded-lg p-4 border border-blue-400">
                        <div class="text-2xl font-bold text-white">{{ $totalRegistros }}</div>
                        <div class="text-blue-100 text-sm">A Importar</div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-deep-teal-600 rounded-lg border border-deep-teal-400">
                    <div class="flex items-center justify-between">
                        <div class="text-cream-200">
                            <strong>Archivo:</strong> {{ $nombreArchivo }}
                        </div>
                        <div class="text-cream-300 text-sm">
                            <strong>Registros a procesar:</strong> {{ $totalRegistros }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ERROES DETECTADOS -->
            @if($totalErrores > 0)
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Errores Detectados ({{ $totalErrores }})
                </h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach($errores as $error)
                    <div class="bg-white border border-red-200 rounded-lg p-3 text-red-700 text-sm">
                        {{ $error }}
                    </div>
                    @endforeach
                </div>
                <p class="text-red-600 text-sm mt-4">
                    ❌ Los registros con errores no serán importados. Corrija el archivo y vuelva a intentar.
                </p>
            </div>
            @endif

            <!-- PREVISUALIZACIÓN DE DATOS -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-deep-teal-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Previsualización de Datos ({{ $totalRegistros - $totalErrores }} registros válidos)
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-deep-teal-50">
                            <tr>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Email</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Nombre</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Rol</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Código</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Teléfono</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Contraseña</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($datos as $index => $usuario)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $usuario['email'] }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-gray-700">{{ $usuario['name'] }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $rolColors = [
                                            'admin' => 'bg-purple-100 text-purple-800',
                                            'coordinador' => 'bg-blue-100 text-blue-800', 
                                            'docente' => 'bg-green-100 text-green-800'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $rolColors[$usuario['rol']] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $usuario['rol'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 font-mono text-sm">
                                    {{ $usuario['codigo_docente'] ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-gray-700">
                                    {{ $usuario['telefono'] ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4">
                                    <code class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-mono">
                                        {{ $usuario['password'] }}
                                    </code>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        ✅ Listo
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(count($datos) === 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>No hay registros válidos para importar.</p>
                </div>
                @endif
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <!-- BOTONES DE ACCIÓN -->
<div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
    <a href="{{ route('admin.carga-masiva.usuarios.index') }}"
    class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition shadow-md flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Cancelar
    </a>

    @if(count($datos) > 0)
    <form action="{{ route('admin.carga-masiva.usuarios.procesar') }}" method="POST" class="flex gap-4" id="importForm">
        @csrf
        <input type="hidden" name="id_gestion" value="{{ $id_gestion }}">
        
        <!-- Pasar los datos como array de JSON - CORREGIDO -->
        @foreach($datos as $index => $usuario)
            <input type="hidden" name="datos[{{ $index }}]" value="{{ json_encode($usuario) }}">
        @endforeach
        
        <button type="submit"
                class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition shadow-md flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Confirmar Importación ({{ count($datos) }} registros)
        </button>
    </form>
    @else
    <button disabled
            class="px-6 py-3 bg-gray-400 text-gray-200 rounded-lg font-semibold cursor-not-allowed flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Sin registros válidos
    </button>
    @endif
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
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmación antes de enviar
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!confirm('¿Está seguro de proceder con la importación? Esta acción creará {{ count($datos) }} usuarios en el sistema.')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>