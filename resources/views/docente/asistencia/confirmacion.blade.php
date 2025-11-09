<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia Confirmada</title>
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
        .success-card {
            background: linear-gradient(135deg, #10b981, #059669);
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
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Asistencia Confirmada</h1>
                        <p class="text-xs text-cream-300">Registro exitoso</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- TARJETA DE ÉXITO -->
            <div class="success-card rounded-xl p-8 text-center text-white shadow-lg">
                <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-3xl font-bold mb-2">¡Asistencia Registrada!</h2>
                <p class="text-green-100 text-lg">Tu asistencia ha sido confirmada exitosamente</p>
            </div>

            <!-- INFORMACIÓN DE LA ASISTENCIA -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-deep-teal-700 mb-4 text-center">Detalles del Registro</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- INFORMACIÓN DE LA CLASE -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-700 border-b pb-2">Información de la Clase</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Materia:</span>
                                <span class="font-medium">{{ $clase->grupoMateria->materia->nombre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Grupo:</span>
                                <span class="font-medium">{{ $clase->grupoMateria->grupo->nombre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Aula:</span>
                                <span class="font-medium">{{ $clase->aula->nombre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Horario:</span>
                                <span class="font-medium">{{ $clase->horario->hora_inicio }} - {{ $clase->horario->hora_fin }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- DETALLES DEL REGISTRO -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-700 border-b pb-2">Detalles del Registro</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fecha:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Hora de registro:</span>
                                <span class="font-medium">{{ $asistencia->hora_registro ?? \Carbon\Carbon::now()->format('H:i:s') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Estado:</span>
                                <span class="font-medium capitalize">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ ($asistencia->estado ?? 'presente') === 'presente' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $asistencia->estado ?? 'presente' }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Método:</span>
                                <span class="font-medium capitalize">{{ $asistencia->metodo ?? 'qr' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMPROBANTE -->
                @if($asistencia)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                    <h4 class="font-semibold text-gray-700 mb-2">Comprobante</h4>
                    <div class="text-sm text-gray-600">
                        <div>ID de registro: <span class="font-mono">{{ $asistencia->id }}</span></div>
                        <div>Registrado el: <span class="font-medium">{{ $asistencia->created_at->format('d/m/Y H:i:s') }}</span></div>
                    </div>
                </div>
                @endif
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('docente.asistencia.index') }}"
                   class="bg-deep-teal-500 text-cream-200 px-6 py-3 rounded-lg font-semibold hover:bg-deep-teal-400 transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Volver al Listado
                </a>
                
                <a href="{{ route('docente.asistencia.historial') }}"
                   class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Ver Historial
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

    <script>
        // Auto-redirección después de 10 segundos
        setTimeout(() => {
            window.location.href = "{{ route('docente.asistencia.index') }}";
        }, 10000);
        
        // Contador para redirección
        let segundosRestantes = 10;
        const contador = document.createElement('div');
        contador.className = 'text-center text-gray-600 text-sm mt-4';
        contador.innerHTML = `Serás redirigido automáticamente en <span class="font-bold">${segundosRestantes}</span> segundos...`;
        document.querySelector('main').appendChild(contador);
        
        setInterval(() => {
            segundosRestantes--;
            contador.innerHTML = `Serás redirigido automáticamente en <span class="font-bold">${segundosRestantes}</span> segundos...`;
        }, 1000);
    </script>
</body>
</html>