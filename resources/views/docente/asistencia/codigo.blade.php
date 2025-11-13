<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Asistencia</title>
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
        .codigo-display {
            background: linear-gradient(135deg, #026773, #024959);
            color: white;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 gradient-header shadow-lg border-b border-deep-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('docente.asistencia.index') }}" 
                       class="text-cream-200 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Código de Verificación</h1>
                        <p class="text-xs text-cream-300">CU12 - Registro con código temporal</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-cream-200 text-sm hidden sm:block">
                        {{ auth()->user()->name }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- INFORMACIÓN DE LA CLASE -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <div class="text-center">
                    <h2 class="text-xl font-semibold text-cream-200 mb-2">
                        {{ $clase->grupoMateria->materia->nombre }}
                    </h2>
                    <div class="text-cream-300 space-y-1 text-sm">
                        <div>Grupo: <span class="font-medium">{{ $clase->grupoMateria->grupo->nombre }}</span></div>
                        <div>Aula: <span class="font-medium">{{ $clase->aula->nombre }}</span></div>
                        <div>Horario: <span class="font-medium">{{ $clase->horario->hora_inicio }} - {{ $clase->horario->hora_fin }}</span></div>
                    </div>
                </div>
            </div>

            <!-- CÓDIGO TEMPORAL -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-deep-teal-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-deep-teal-700 mb-2">Código de Verificación</h3>
                    <p class="text-gray-600">Ingresa este código en el formulario para registrar tu asistencia</p>
                </div>

                <!-- CÓDIGO VISUAL -->
                <div class="codigo-display rounded-2xl p-8 mb-6 shadow-lg">
                    <div class="text-4xl font-bold tracking-wider mb-2">{{ $codigo }}</div>
                    <div class="text-cream-200 text-sm">Válido por 5 minutos</div>
                </div>

                <!-- CONTADOR DE TIEMPO -->
                <div class="mb-6">
                    <div class="text-gray-600 text-sm mb-2">Tiempo restante:</div>
                    <div class="text-2xl font-bold text-deep-teal-600" id="contadorTiempo">05:00</div>
                </div>

                <!-- FORMULARIO DE VALIDACIÓN -->
                <form action="{{ route('docente.asistencia.codigo.validar') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="clase_id" value="{{ $clase->id }}">
                    <input type="hidden" name="codigo" value="{{ $codigo }}">
                    
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirma el código ingresándolo nuevamente:
                        </label>
                        <input type="text" 
                               name="codigo_confirmacion" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-xl font-mono tracking-wider uppercase"
                               maxlength="6"
                               placeholder="XXXXXX"
                               required
                               autocomplete="off">
                    </div>

                    <button type="submit"
                            class="w-full bg-green-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-600 transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirmar Asistencia
                    </button>
                </form>

                <!-- INFORMACIÓN ADICIONAL -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">💡 ¿Cómo funciona?</h4>
                    <ul class="text-blue-700 text-sm space-y-1 text-left">
                        <li>• El código es válido solo por 5 minutos</li>
                        <li>• Debes ingresar el mismo código en el campo de confirmación</li>
                        <li>• La asistencia se registra automáticamente al confirmar</li>
                        <li>• Solo puedes registrar asistencia una vez por clase</li>
                    </ul>
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
        // Contador de tiempo regresivo
        let tiempoRestante = 5 * 60; // 5 minutos en segundos
        
        function actualizarContador() {
            const minutos = Math.floor(tiempoRestante / 60);
            const segundos = tiempoRestante % 60;
            
            document.getElementById('contadorTiempo').textContent = 
                `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
            
            if (tiempoRestante <= 0) {
                // Redirigir cuando expire el tiempo
                alert('El código ha expirado. Por favor, genera uno nuevo.');
                window.location.href = "{{ route('docente.asistencia.index') }}";
            } else {
                tiempoRestante--;
                setTimeout(actualizarContador, 1000);
            }
        }
        
        // Iniciar contador
        actualizarContador();
        
        // Auto-mayúsculas en el input
        document.querySelector('input[name="codigo_confirmacion"]').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
        
        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const codigoOriginal = "{{ $codigo }}";
            const codigoConfirmacion = document.querySelector('input[name="codigo_confirmacion"]').value;
            
            if (codigoConfirmacion !== codigoOriginal) {
                e.preventDefault();
                alert('El código de confirmación no coincide. Por favor, verifica.');
            }
        });
    </script>
</body>
</html>