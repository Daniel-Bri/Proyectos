<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
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
        .clase-card {
            transition: all 0.3s ease;
            border-left: 4px solid #026773;
        }
        .clase-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(1, 46, 64, 0.15);
        }
        .badge-horario {
            background: linear-gradient(135deg, #026773, #024959);
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Registro de Asistencia</h1>
                        <p class="text-xs text-cream-300">Gestión de asistencia docente</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-cream-200 text-sm hidden sm:block">
                        {{ auth()->user()->name }}
                    </span>
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
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

            <!-- INFORMACIÓN DEL DÍA -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-cream-200 mb-2">Clases del Día</h2>
                        <p class="text-cream-300 text-sm">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="bg-deep-teal-600 text-cream-200 px-4 py-2 rounded-lg border border-deep-teal-400">
                            <div class="text-sm">Hora actual</div>
                            <div class="text-lg font-bold" id="horaActual">
                                {{ \Carbon\Carbon::now()->format('H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LISTA DE CLASES -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-deep-teal-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tus Clases de Hoy
                </h3>

                @forelse($clases as $clase)
                <div class="clase-card bg-white rounded-xl shadow-md p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <!-- INFORMACIÓN DE LA CLASE -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                <!-- HORARIO -->
                                <div class="flex items-center gap-3">
                                    <div class="badge-horario text-white px-3 py-2 rounded-lg text-center min-w-24">
                                        <div class="text-sm font-medium">{{ $clase->horario->hora_inicio }} - {{ $clase->horario->hora_fin }}</div>
                                        <div class="text-xs opacity-90">{{ $clase->horario->dia }}</div>
                                    </div>
                                </div>

                                <!-- DETALLES -->
                                <div class="flex-1">
                                    <h4 class="font-semibold text-deep-teal-800 text-lg">
                                        {{ $clase->grupoMateria->materia->nombre }}
                                    </h4>
                                    <div class="text-gray-600 text-sm mt-1 space-y-1">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Grupo: <span class="font-medium">{{ $clase->grupoMateria->grupo->nombre }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Aula: <span class="font-medium">{{ $clase->aula->nombre }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Código: <span class="font-mono font-medium">{{ $clase->grupoMateria->materia->sigla }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <!-- BOTÓN CÓDIGO TEMPORAL -->
                            <a href="{{ route('docente.asistencia.codigo', $clase->id) }}"
                               class="bg-blue-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-600 transition shadow-md flex items-center justify-center gap-2 text-center text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Código
                            </a>

                            <!-- BOTÓN QR -->
                            <a href="{{ route('docente.asistencia.qr', $clase->id) }}"
                               class="bg-green-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-600 transition shadow-md flex items-center justify-center gap-2 text-center text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                QR
                            </a>
                        </div>
                    </div>

                    <!-- ESTADO Y HORARIO -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            @php
                                $estadoClase = $clase->getEstadoClase();
                                $badgeColors = [
                                    'disponible' => 'bg-green-100 text-green-800',
                                    'proximo' => 'bg-blue-100 text-blue-800',
                                    'pasado' => 'bg-gray-100 text-gray-800',
                                    'en_curso' => 'bg-orange-100 text-orange-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full font-medium {{ $badgeColors[$estadoClase] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($estadoClase) }}
                            </span>
                            <span class="text-gray-600">
                                Tiempo restante: <span class="font-medium">{{ $clase->getTiempoRestante() }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">No tienes clases programadas para hoy</h3>
                    <p class="text-gray-500 text-sm">Revisa tu horario semanal para otras fechas.</p>
                </div>
                @endforelse
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
        // Actualizar hora en tiempo real
        function actualizarHora() {
            const ahora = new Date();
            const hora = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            document.getElementById('horaActual').textContent = `${hora}:${minutos}:${segundos}`;
        }
        
        setInterval(actualizarHora, 1000);
        actualizarHora();

        // Auto-refresh cada 2 minutos para actualizar estados
        setTimeout(() => {
            window.location.reload();
        }, 120000);
    </script>
</body>
</html>