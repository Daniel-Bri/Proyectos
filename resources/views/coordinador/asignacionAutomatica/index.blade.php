<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación Automática de Horarios</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Asignación Automática de Horarios</h1>
                        <p class="text-xs text-cream-300">Generación inteligente de horarios académicos</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ url('/coordinador/horarios') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a Horarios
                    </a>
                    <a href="{{ url('/coordinador/dashboard') }}"
                       class="inline-flex items-center gap-2 bg-deep-teal-600 text-cream-200 px-3 py-2 rounded-lg font-medium hover:bg-deep-teal-500 transition shadow-md border border-deep-teal-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
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
            
            <!-- ALERTAS -->
            @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Errores de validación:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- TARJETAS DE ASIGNACIÓN -->
            <div class="grid lg:grid-cols-2 gap-6">
                
                <!-- ASIGNACIÓN COMPLETA -->
                <div class="gradient-card rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg grid place-items-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-cream-200">🔄 Asignación Completa</h2>
                            <p class="text-cream-300 text-sm">Reinicia todo y asigna desde cero</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-deep-teal-600/50 rounded-lg p-4">
                            <h3 class="text-cream-200 font-medium mb-2">⚠️ Advertencia</h3>
                            <ul class="text-cream-300 text-sm space-y-1">
                                <li>• Elimina TODOS los horarios existentes</li>
                                <li>• Distribución óptima desde cero</li>
                                <li>• Ideal para inicio de gestión académica</li>
                            </ul>
                        </div>

                        <form action="{{ route('coordinador.asignacion-automatica.completa') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-cream-300 mb-2">
                                        Gestión Académica
                                    </label>
                                    <select name="id_gestion" class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent" required>
                                        <option value="">Seleccionar gestión...</option>
                                        @if(isset($gestiones) && $gestiones->count() > 0)
                                            @foreach($gestiones as $gestion)
                                                <option value="{{ $gestion->id }}">{{ $gestion->nombre }} - {{ $gestion->estado }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No hay gestiones disponibles</option>
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-cream-300 mb-2">
                                        Máximo horas por docente
                                    </label>
                                    <input type="number" name="max_horas_docente" value="40" min="1" max="50" 
                                           class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent" required>
                                    <p class="text-cream-400 text-xs mt-1">Máximo 50 horas semanales por docente</p>
                                </div>

                                <button type="submit" 
                                        onclick="return confirm('¿ESTÁ SEGURO? Esto eliminará TODOS los horarios existentes de esta gestión y generará nuevos.')"
                                        class="w-full bg-yellow-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Ejecutar Asignación Completa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ASIGNACIÓN INTELIGENTE -->
                <div class="gradient-card rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-green-500 rounded-lg grid place-items-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-cream-200">🧠 Asignación Inteligente</h2>
                            <p class="text-cream-300 text-sm">Respeta horarios existentes y completa faltantes</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-deep-teal-600/50 rounded-lg p-4">
                            <h3 class="text-cream-200 font-medium mb-2">✅ Recomendado</h3>
                            <ul class="text-cream-300 text-sm space-y-1">
                                <li>• Mantiene asignaciones manuales existentes</li>
                                <li>• Asigna solo horarios faltantes</li>
                                <li>• Ideal para ajustes durante la gestión</li>
                            </ul>
                        </div>

                        <form action="{{ route('coordinador.asignacion-automatica.inteligente') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-cream-300 mb-2">
                                        Gestión Académica
                                    </label>
                                    <select name="id_gestion" class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent" required>
                                        <option value="">Seleccionar gestión...</option>
                                        @if(isset($gestiones) && $gestiones->count() > 0)
                                            @foreach($gestiones as $gestion)
                                                <option value="{{ $gestion->id }}">{{ $gestion->nombre }} - {{ $gestion->estado }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No hay gestiones disponibles</option>
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-cream-300 mb-2">
                                        Máximo horas por docente
                                    </label>
                                    <input type="number" name="max_horas_docente" value="40" min="1" max="50" 
                                           class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent" required>
                                    <p class="text-cream-400 text-xs mt-1">Máximo 50 horas semanales por docente</p>
                                </div>

                                <button type="submit" 
                                        class="w-full bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ejecutar Asignación Inteligente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN ADICIONAL -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-cream-200 mb-4">📊 Información del Sistema</h3>
                <div class="grid md:grid-cols-4 gap-4 text-cream-300">
                    <div class="bg-deep-teal-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-cream-200">
                            {{ $estadisticas['docentes'] ?? 0 }}
                        </div>
                        <div class="text-sm">Docentes Disponibles</div>
                    </div>
                    <div class="bg-deep-teal-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-cream-200">
                            {{ $estadisticas['materias'] ?? 0 }}
                        </div>
                        <div class="text-sm">Materias por Asignar</div>
                    </div>
                    <div class="bg-deep-teal-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-cream-200">
                            {{ $estadisticas['aulas'] ?? 0 }}
                        </div>
                        <div class="text-sm">Aulas Disponibles</div>
                    </div>
                    <div class="bg-deep-teal-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-cream-200">
                            {{ $estadisticas['horarios_asignados'] ?? 0 }}
                        </div>
                        <div class="text-sm">Horarios Asignados</div>
                    </div>
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
        // Validación adicional del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const select = form.querySelector('select[name="id_gestion"]');
                    const input = form.querySelector('input[name="max_horas_docente"]');
                    
                    if (!select.value) {
                        e.preventDefault();
                        alert('Por favor seleccione una gestión académica');
                        select.focus();
                        return;
                    }
                    
                    if (!input.value || input.value < 1 || input.value > 50) {
                        e.preventDefault();
                        alert('Las horas por docente deben estar entre 1 y 50');
                        input.focus();
                        return;
                    }
                });
            });
        });
    </script>
</body>
</html>