<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR de Asistencia</title>
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
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Código QR</h1>
                        <p class="text-xs text-cream-300">CU13 - Registro con código QR</p>
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

            <!-- CÓDIGO QR -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-deep-teal-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-deep-teal-700 mb-2">Escanea el Código QR</h3>
                    <p class="text-gray-600">Usa la cámara de tu celular para escanear este código y registrar tu asistencia automáticamente</p>
                </div>

                <!-- QR VISUAL -->
                <div class="qr-container mx-auto mb-6 max-w-xs">
                    <img src="{{ route('docente.asistencia.qr.generar', $clase->id) }}" 
                         alt="Código QR para asistencia"
                         class="w-full h-auto">
                </div>

                <!-- CONTADOR DE TIEMPO -->
                <div class="mb-6">
                    <div class="text-gray-600 text-sm mb-2">QR válido por:</div>
                    <div class="text-2xl font-bold text-deep-teal-600" id="contadorTiempo">30:00</div>
                </div>

                <!-- BOTÓN ALTERNATIVO -->
                <div class="border-t border-gray-200 pt-6">
                    <p class="text-gray-600 text-sm mb-4">¿Problemas con el QR?</p>
                    <a href="{{ route('docente.asistencia.codigo', $clase->id) }}"
                       class="inline-flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Usar Código de Verificación
                    </a>
                </div>

                <!-- INSTRUCCIONES -->
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-3">📱 ¿Cómo escanear el QR?</h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-green-700 text-left">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs">1</span>
                                <span>Abre la cámara de tu celular</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs">2</span>
                                <span>Enfoca el código QR</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs">3</span>
                                <span>Toca el enlace que aparece</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs">4</span>
                                <span>¡Asistencia registrada!</span>
                            </div>
                        </div>
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
        // Contador de tiempo regresivo para QR (30 minutos)
        let tiempoRestante = 30 * 60; // 30 minutos en segundos
        
        function actualizarContador() {
            const minutos = Math.floor(tiempoRestante / 60);
            const segundos = tiempoRestante % 60;
            
            document.getElementById('contadorTiempo').textContent = 
                `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
            
            if (tiempoRestante <= 0) {
                // Redirigir cuando expire el tiempo
                alert('El QR ha expirado. Por favor, genera uno nuevo.');
                window.location.href = "{{ route('docente.asistencia.index') }}";
            } else {
                tiempoRestante--;
                setTimeout(actualizarContador, 1000);
            }
        }
        
        // Iniciar contador
        actualizarContador();
        
        // Verificar si la asistencia fue registrada (para QR escaneado desde otro dispositivo)
        function verificarAsistencia() {
            fetch("{{ route('docente.asistencia.confirmacion', $clase->id) }}")
                .then(response => response.json())
                .then(data => {
                    if (data.asistencia_registrada) {
                        window.location.href = "{{ route('docente.asistencia.confirmacion', $clase->id) }}";
                    }
                });
        }
        
        // Verificar cada 5 segundos si se registró la asistencia via QR
        setInterval(verificarAsistencia, 5000);
    </script>
</body>
</html>