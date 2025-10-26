<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Horario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        accent: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-secondary-900 text-white">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 bg-secondary-900/95 backdrop-blur border-b border-secondary-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-accent-500 rounded-lg grid place-items-center">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10m-12 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold">Detalle de Horario</h1>
                        <p class="text-xs text-secondary-400">Información completa de la clase</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('visualizacion-semana.index') }}"
                       class="inline-flex items-center gap-2 bg-secondary-700 text-white px-3 py-2 rounded-lg font-medium hover:bg-secondary-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                    <a href="{{ url('/dashboard') }}"
                       class="hidden sm:inline-flex items-center gap-2 bg-accent-500 text-white px-3 py-2 rounded-lg font-medium hover:bg-accent-600 transition">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if($horario)
            <!-- INFORMACIÓN PRINCIPAL -->
            <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-4 sm:p-6">
                <h2 class="text-xl font-semibold mb-4 text-white">Información del Horario</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-300 mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Día de la Semana
                            </label>
                            <div class="bg-secondary-700 rounded-lg p-3">
                                <p class="text-white font-medium text-lg">
                                    @php
                                        $diasSemana = [
                                            1 => 'Lunes',
                                            2 => 'Martes', 
                                            3 => 'Miércoles',
                                            4 => 'Jueves',
                                            5 => 'Viernes',
                                            6 => 'Sábado'
                                        ];
                                        echo $diasSemana[$horario->dia] ?? 'Desconocido';
                                    @endphp
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-secondary-300 mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Horario
                            </label>
                            <div class="bg-primary-500 rounded-lg p-3">
                                <p class="text-white font-medium text-lg text-center">
                                    {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary-300 mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Duración
                            </label>
                            <div class="bg-accent-500 rounded-lg p-3">
                                <p class="text-white font-medium text-lg text-center">
                                    @php
                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                        echo $fin->diffInHours($inicio) . ' hora(s)';
                                    @endphp
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-secondary-300 mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Estado
                            </label>
                            <div class="bg-green-500 rounded-lg p-3">
                                <p class="text-white font-medium text-lg text-center">
                                    Activo
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CLASES ASOCIADAS -->
            <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-white">Clases en este Horario</h2>
                    <span class="bg-secondary-700 text-secondary-300 px-3 py-1 rounded-full text-sm">
                        {{ $horario->grupoMateriaHorarios->count() }} clase(s)
                    </span>
                </div>
                
                @if($horario->grupoMateriaHorarios->count() > 0)
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach($horario->grupoMateriaHorarios as $grupoMateriaHorario)
                            @php
                                $grupoMateria = $grupoMateriaHorario->grupoMateria;
                                $docenteNombre = $grupoMateriaHorario->docente->user->name ?? 'Sin docente asignado';
                                $color = '#0ea5e9'; // Color primario por defecto
                            @endphp
                            
                            <div class="bg-gradient-to-br from-secondary-700 to-secondary-800 border border-secondary-600 rounded-xl p-4 hover:border-primary-500/50 transition-all duration-200">
                                <!-- ENCABEZADO -->
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-3 h-3 rounded-full bg-primary-500"></div>
                                    <h3 class="font-semibold text-lg text-white">{{ $grupoMateria->materia->nombre ?? 'Sin materia' }}</h3>
                                </div>

                                <!-- INFORMACIÓN DETALLADA -->
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="text-secondary-400 block text-xs">Docente</span>
                                            <span class="text-white font-medium">{{ $docenteNombre }}</span>
                                        </div>
                                        <div>
                                            <span class="text-secondary-400 block text-xs">Grupo</span>
                                            <span class="text-white font-medium bg-secondary-600 px-2 py-1 rounded">{{ $grupoMateria->grupo->nombre ?? 'Sin grupo' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="text-secondary-400 block text-xs">Aula</span>
                                            <span class="text-white font-medium">{{ $grupoMateriaHorario->aula->nombre ?? 'Sin aula' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-secondary-400 block text-xs">Estado Aula</span>
                                            <span class="text-white font-medium capitalize">{{ $grupoMateriaHorario->estado_aula }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-2 border-t border-secondary-600">
                                        <span class="text-secondary-400 block text-xs mb-1">Materia</span>
                                        <code class="text-primary-400 font-mono text-sm bg-secondary-700 px-2 py-1 rounded">
                                            {{ $grupoMateria->sigla_materia ?? 'N/A' }}
                                        </code>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- ESTADO VACÍO -->
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-secondary-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white mb-2">No hay clases asignadas</h3>
                        <p class="text-secondary-400">No se encontraron clases para este horario.</p>
                    </div>
                @endif
            </div>
            @else
            <!-- NO ENCONTRADO -->
            <div class="bg-secondary-800/60 backdrop-blur border border-secondary-700 rounded-xl p-8 text-center">
                <svg class="w-16 h-16 text-accent-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h2 class="text-xl font-semibold text-white mb-2">Horario no encontrado</h2>
                <p class="text-secondary-400 mb-4">El horario que buscas no existe o no tienes permisos para verlo.</p>
                <a href="{{ route('visualizacion-semana.index') }}"
                   class="inline-flex items-center gap-2 bg-primary-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-600 transition">
                    Volver a la visualización
                </a>
            </div>
            @endif
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-secondary-900 border-t border-secondary-800 py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-secondary-400 text-sm">
                <p>Sistema de Gestión de Horarios - {{ date('Y') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>