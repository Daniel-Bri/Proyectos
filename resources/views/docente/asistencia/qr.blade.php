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
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            <div class="gradient-card rounded-xl p-6 shadow-lg fade-in">
                <div class="text-center">
                    <h2 class="text-xl font-semibold text-cream-200 mb-2">
                        {{ $clase->grupoMateria->materia->nombre }}
                    </h2>
                    <div class="text-cream-300 space-y-1 text-sm">
                        <div>Grupo: <span class="font-medium">{{ $clase->grupoMateria->grupo->nombre }}</span></div>
                        <div>Aula: <span class="font-medium">{{ $clase->aula->nombre }}</span></div>
                        <div>Horario: <span class="font-medium">{{ \Carbon\Carbon::parse($clase->horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($clase->horario->hora_fin)->format('H:i') }}</span></div>
                    </div>
                </div>
            </div>

            <!-- CÓDIGO QR -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center fade-in">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-deep-teal-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-deep-teal-700 mb-2">Escanea el Código QR</h3>
                    <p class="text-gray-600">Usa la cámara de tu celular para escanear este código y registrar tu asistencia automáticamente</p>
                </div>

                <!-- CONTENEDOR QR MEJORADO -->
                <div class="qr-container mx-auto mb-6 max-w-xs relative">
                    <!-- Spinner de carga -->
                    <div id="qrLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 rounded-lg z-10 fade-in">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-deep-teal-500 mx-auto mb-2"></div>
                            <p class="text-sm text-gray-600">Generando código QR...</p>
                        </div>
                    </div>
                    
                    <!-- Imagen QR con mejor manejo -->
                    <div id="qrImageContainer">
                        <img src="{{ route('docente.asistencia.qr.generar', $clase->id) }}?v={{ time() }}" 
                            alt="Código QR para asistencia - {{ $clase->grupoMateria->materia->nombre }}"
                            class="w-full h-auto rounded-lg border-2 border-gray-200 shadow-sm fade-in"
                            id="qrImage"
                            onload="document.getElementById('qrLoading').style.display = 'none'; this.style.opacity = '1';"
                            onerror="manejarErrorQR(this)"
                            style="opacity: 0; transition: opacity 0.5s ease-in;">
                    </div>
                    
                    <!-- Mensaje de error mejorado -->
                    <div id="qrError" class="hidden text-center p-6 bg-yellow-50 border border-yellow-200 rounded-lg fade-in">
                        <svg class="w-16 h-16 text-yellow-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p class="text-yellow-700 font-semibold mb-2">Código QR temporal</p>
                        <p class="text-yellow-600 text-sm mb-4">Usa el código alternativo para esta sesión</p>
                        
                        <!-- CÓDIGO ALTERNATIVO VISIBLE -->
                        <div class="bg-white p-4 rounded border border-yellow-300 mb-4">
                            <p class="text-xs text-gray-600 mb-2">Código de respaldo:</p>
                            <p class="text-xl font-mono font-bold text-deep-teal-600 bg-gray-50 p-2 rounded">
                                {{ strtoupper(Str::random(6)) }}
                            </p>
                        </div>
                        
                        <div class="flex gap-3 justify-center">
                            <button onclick="reintentarQR()" 
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reintentar QR
                            </button>
                            <a href="{{ route('docente.asistencia.codigo', $clase->id) }}"
                               class="bg-deep-teal-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-deep-teal-600 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Usar Código
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CONTADOR DE TIEMPO -->
                <div class="mb-6">
                    <div class="text-gray-600 text-sm mb-2">QR válido por:</div>
                    <div class="text-2xl font-bold text-deep-teal-600" id="contadorTiempo">15:00</div>
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
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
                    <h4 class="font-semibold text-green-800 mb-3 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        📱 ¿Cómo escanear el QR?
                    </h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-green-700 text-left">
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs mt-0.5 flex-shrink-0">1</span>
                                <span>Abre la cámara de tu celular y enfoca el código QR</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs mt-0.5 flex-shrink-0">2</span>
                                <span>Espera a que detecte automáticamente el código</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs mt-0.5 flex-shrink-0">3</span>
                                <span>Toca la notificación o enlace que aparece</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-green-200 rounded-full flex items-center justify-center text-green-800 font-bold text-xs mt-0.5 flex-shrink-0">4</span>
                                <span>¡Tu asistencia se registrará automáticamente!</span>
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
        // Contador de tiempo regresivo para QR (15 minutos)
        let tiempoRestante = 15 * 60; // 15 minutos en segundos
        
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
        
        // Manejo de errores del QR
        function manejarErrorQR(imgElement) {
            console.log('Error cargando QR');
            document.getElementById('qrLoading').style.display = 'none';
            document.getElementById('qrError').classList.remove('hidden');
            imgElement.style.display = 'none';
        }

        function reintentarQR() {
            console.log('Reintentando cargar QR...');
            const qrImage = document.getElementById('qrImage');
            const qrError = document.getElementById('qrError');
            const qrLoading = document.getElementById('qrLoading');
            
            qrError.classList.add('hidden');
            qrLoading.style.display = 'flex';
            qrImage.style.display = 'none';
            qrImage.style.opacity = '0';
            
            // Forzar recarga con timestamp nuevo
            setTimeout(() => {
                qrImage.src = "{{ route('docente.asistencia.qr.generar', $clase->id) }}?v=" + new Date().getTime();
                qrImage.style.display = 'block';
            }, 500);
        }

        // Mostrar error después de timeout
        setTimeout(function() {
            const qrImage = document.getElementById('qrImage');
            const qrLoading = document.getElementById('qrLoading');
            
            if (qrImage.style.opacity === '0' || qrImage.naturalWidth === 0) {
                qrLoading.style.display = 'none';
                document.getElementById('qrError').classList.remove('hidden');
                qrImage.style.display = 'none';
            }
        }, 8000); // 8 segundos de timeout

        // Iniciar cuando la página cargue
        document.addEventListener('DOMContentLoaded', function() {
            actualizarContador();
            
            // Mostrar la imagen QR
            const qrImage = document.getElementById('qrImage');
            qrImage.style.display = 'block';
            
            // Recargar QR cada 2 minutos para mantenerlo fresco
            setInterval(() => {
                if (tiempoRestante > 60) { // Solo si queda más de 1 minuto
                    qrImage.src = "{{ route('docente.asistencia.qr.generar', $clase->id) }}?v=" + new Date().getTime();
                }
            }, 2 * 60 * 1000);
        });
    </script>
</body>
</html>