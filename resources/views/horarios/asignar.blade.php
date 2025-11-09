<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Horario Existente</title>
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
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Asignar Horario Existente</h1>
                        <p class="text-xs text-cream-300">Asignar horario predefinido a docente, materia y grupo</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('coordinador.horarios.create-base') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-4 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Crear Nuevo Horario
                    </a>
                    <a href="{{ route('coordinador.horarios.index') }}"
                       class="inline-flex items-center gap-2 bg-deep-teal-600 text-cream-200 px-4 py-2 rounded-lg font-medium hover:bg-deep-teal-500 transition shadow-md border border-deep-teal-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a Horarios
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- HORARIOS DISPONIBLES -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-6 text-cream-200 border-b border-deep-teal-400 pb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Horarios Base Disponibles
                </h2>

                @if($horariosDisponibles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($horariosDisponibles as $horario)
                    <div class="bg-deep-teal-600 border border-deep-teal-400 rounded-lg p-4 hover:border-cream-200 transition cursor-pointer horario-item"
                         data-horario-id="{{ $horario->id }}"
                         data-dia="{{ $horario->dia }}"
                         data-hora-inicio="{{ $horario->hora_inicio }}"
                         data-hora-fin="{{ $horario->hora_fin }}"
                         data-descripcion="{{ $horario->descripcion }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-cream-200 text-deep-teal-700 px-2 py-1 rounded text-sm font-semibold">
                                @php
                                    $dias = ['LUN' => 'Lun', 'MAR' => 'Mar', 'MIE' => 'Mié', 'JUE' => 'Jue', 'VIE' => 'Vie', 'SAB' => 'Sáb'];
                                @endphp
                                {{ $dias[$horario->dia] ?? $horario->dia }}
                            </span>
                            <span class="text-cream-300 text-xs bg-deep-teal-500 px-2 py-1 rounded">
                                ID: {{ $horario->id }}
                            </span>
                        </div>
                        <div class="text-cream-200 font-semibold text-center text-lg">
                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                        </div>
                        @if($horario->descripcion)
                        <div class="text-cream-400 text-xs text-center mt-1">
                            {{ $horario->descripcion }}
                        </div>
                        @endif
                        <div class="text-cream-400 text-xs text-center mt-1">
                            {{ \Carbon\Carbon::parse($horario->hora_fin)->diffInHours(\Carbon\Carbon::parse($horario->hora_inicio)) }} horas
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-cream-300 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-cream-200 mb-2">No hay horarios disponibles</h3>
                    <p class="text-cream-300 mb-4">Todos los horarios están asignados o no hay horarios base creados.</p>
                    <a href="{{ route('coordinador.horarios.create') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-4 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Crear Nuevo Horario Base
                    </a>
                </div>
                @endif
            </div>

            <!-- FORMULARIO DE ASIGNACIÓN -->
            @if($horariosDisponibles->count() > 0)
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-6 text-cream-200 border-b border-deep-teal-400 pb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Asignar Horario a Clase
                </h2>

                <form action="{{ route('coordinador.horarios.store-asignacion') }}" method="POST" id="asignacionForm">
                    @csrf

                    <div class="space-y-6">
                        <!-- HORARIO SELECCIONADO -->
                        <div class="bg-deep-teal-500 border-2 border-cream-200 rounded-lg p-4" id="horarioSeleccionado" style="display: none;">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-cream-300 text-sm">Horario Base Seleccionado</div>
                                    <div class="text-cream-200 font-semibold text-lg" id="horarioInfo">
                                        <!-- Se llena con JavaScript -->
                                    </div>
                                    <div class="text-cream-400 text-sm mt-1" id="horarioDescripcion">
                                        <!-- Se llena con JavaScript -->
                                    </div>
                                </div>
                                <button type="button" onclick="deseleccionarHorario()" class="text-cream-300 hover:text-cream-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" name="id_horario" id="id_horario">
                        </div>

                        <!-- ASIGNACIÓN ACADÉMICA -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- DOCENTE -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-cream-300">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Docente *
                                </label>
                                <select name="id_docente" required
                                        class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                                    <option value="" class="bg-deep-teal-700">Seleccionar docente</option>
                                    @foreach($docentes as $docente)
                                        <option value="{{ $docente['id'] }}" {{ old('id_docente') == $docente['id'] ? 'selected' : '' }} class="bg-deep-teal-700">
                                            {{ $docente['codigo'] }} - {{ $docente['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_docente')
                                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- MATERIA -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-cream-300">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    Materia *
                                </label>
                                <select name="sigla_materia" required
                                        class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                                    <option value="" class="bg-deep-teal-700">Seleccionar materia</option>
                                    @foreach($materias as $materia)
                                        <option value="{{ $materia->sigla }}" {{ old('sigla_materia') == $materia->sigla ? 'selected' : '' }} class="bg-deep-teal-700">
                                            {{ $materia->sigla }} - {{ $materia->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sigla_materia')
                                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- GRUPO Y AULA -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- GRUPO -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-cream-300">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Grupo *
                                </label>
                                <select name="id_grupo" required
                                        class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                                    <option value="" class="bg-deep-teal-700">Seleccionar grupo</option>
                                    @foreach($grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ old('id_grupo') == $grupo->id ? 'selected' : '' }} class="bg-deep-teal-700">
                                            {{ $grupo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_grupo')
                                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- AULA -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-cream-300">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Aula *
                                </label>
                                <select name="id_aula" required
                                        class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                                    <option value="" class="bg-deep-teal-700">Seleccionar aula</option>
                                    @foreach($aulas as $aula)
                                        <option value="{{ $aula->id }}" {{ old('id_aula') == $aula->id ? 'selected' : '' }} class="bg-deep-teal-700">
                                            {{ $aula->nombre }} - {{ $aula->tipo }} (Cap: {{ $aula->capacidad }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_aula')
                                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- INFORMACIÓN DE GESTIÓN ACTIVA -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-cream-300">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Gestión Académica
                            </label>
                            <div class="bg-deep-teal-500 border border-deep-teal-400 rounded-lg p-4">
                                <div class="flex items-center justify-between text-cream-200">
                                    <div>
                                        <div class="font-semibold">{{ $gestionActiva->nombre }}</div>
                                        <div class="text-cream-300 text-sm">
                                            {{ \Carbon\Carbon::parse($gestionActiva->fecha_inicio)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($gestionActiva->fecha_fin)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-cream-400 text-xs mt-1">
                                            Estado: <span class="font-medium capitalize">{{ $gestionActiva->estado }}</span>
                                        </div>
                                    </div>
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- CAMPO OCULTO PARA EL FORMULARIO -->
                            <input type="hidden" name="id_gestion" value="{{ $gestionActiva->id }}">
                            @error('id_gestion')
                                <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ESTADO DEL AULA -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-cream-300">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Estado del Aula *
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center p-4 bg-deep-teal-600 border-2 border-deep-teal-400 rounded-lg cursor-pointer hover:bg-deep-teal-500 transition has-[:checked]:border-cream-200 has-[:checked]:bg-deep-teal-500">
                                    <input type="radio" name="estado_aula" value="ocupado" {{ old('estado_aula', 'ocupado') == 'ocupado' ? 'checked' : '' }} class="text-cream-200 focus:ring-cream-200 mr-3">
                                    <div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="font-semibold text-cream-200">Aula Ocupada</span>
                                        </div>
                                        <p class="text-cream-300 text-sm mt-1">Aula asignada para clase</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 bg-deep-teal-600 border-2 border-deep-teal-400 rounded-lg cursor-pointer hover:bg-deep-teal-500 transition has-[:checked]:border-cream-200 has-[:checked]:bg-deep-teal-500">
                                    <input type="radio" name="estado_aula" value="disponible" {{ old('estado_aula') == 'disponible' ? 'checked' : '' }} class="text-cream-200 focus:ring-cream-200 mr-3">
                                    <div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            <span class="font-semibold text-cream-200">Aula Disponible</span>
                                        </div>
                                        <p class="text-cream-300 text-sm mt-1">Aula libre para uso</p>
                                    </div>
                                </label>
                            </div>
                            @error('estado_aula')
                                <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MENSAJES DE ERROR -->
                        @if($errors->any())
                            <div class="bg-red-500/20 border border-red-400 rounded-lg p-4">
                                <div class="flex items-center gap-2 text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <span class="font-semibold">Por favor corrige los siguientes errores:</span>
                                </div>
                                <ul class="mt-2 text-red-300 text-sm list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-deep-teal-400">
                            <button type="submit" id="submitBtn" disabled
                                    class="flex-1 bg-deep-teal-400 text-cream-200 px-6 py-3 rounded-lg font-semibold hover:bg-deep-teal-300 transition shadow-md flex items-center justify-center gap-2 border border-deep-teal-300 opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Asignar Horario
                            </button>
                            <a href="{{ route('coordinador.horarios.index') }}"
                               class="flex-1 bg-deep-teal-600 text-cream-200 px-6 py-3 rounded-lg font-semibold hover:bg-deep-teal-500 transition shadow-md flex items-center justify-center gap-2 text-center border border-deep-teal-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            @endif
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
        // Mapeo de días completos
        const diasCompletos = {
            'LUN': 'Lunes',
            'MAR': 'Martes', 
            'MIE': 'Miércoles',
            'JUE': 'Jueves',
            'VIE': 'Viernes',
            'SAB': 'Sábado'
        };

        let horarioSeleccionado = null;

        // Seleccionar horario
        document.querySelectorAll('.horario-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remover selección anterior
                document.querySelectorAll('.horario-item').forEach(i => {
                    i.classList.remove('border-cream-200', 'bg-deep-teal-500');
                    i.classList.add('border-deep-teal-400', 'bg-deep-teal-600');
                });

                // Seleccionar nuevo
                this.classList.remove('border-deep-teal-400', 'bg-deep-teal-600');
                this.classList.add('border-cream-200', 'bg-deep-teal-500');

                // Obtener datos
                const horarioId = this.dataset.horarioId;
                const dia = this.dataset.dia;
                const horaInicio = this.dataset.horaInicio;
                const horaFin = this.dataset.horaFin;
                const descripcion = this.dataset.descripcion;

                // Actualizar formulario
                document.getElementById('id_horario').value = horarioId;
                document.getElementById('horarioInfo').textContent = 
                    `${diasCompletos[dia] || dia} - ${horaInicio} a ${horaFin}`;
                
                if (descripcion) {
                    document.getElementById('horarioDescripcion').textContent = descripcion;
                    document.getElementById('horarioDescripcion').style.display = 'block';
                } else {
                    document.getElementById('horarioDescripcion').style.display = 'none';
                }
                
                document.getElementById('horarioSeleccionado').style.display = 'block';

                // Habilitar botón de enviar
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('submitBtn').classList.add('opacity-100', 'cursor-pointer');

                horarioSeleccionado = horarioId;
            });
        });

        // Deseleccionar horario
        function deseleccionarHorario() {
            document.querySelectorAll('.horario-item').forEach(i => {
                i.classList.remove('border-cream-200', 'bg-deep-teal-500');
                i.classList.add('border-deep-teal-400', 'bg-deep-teal-600');
            });

            document.getElementById('id_horario').value = '';
            document.getElementById('horarioSeleccionado').style.display = 'none';

            // Deshabilitar botón de enviar
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
            document.getElementById('submitBtn').classList.remove('opacity-100', 'cursor-pointer');

            horarioSeleccionado = null;
        }

        // Validación del formulario antes de enviar
        document.getElementById('asignacionForm').addEventListener('submit', function(e) {
            if (!horarioSeleccionado) {
                e.preventDefault();
                alert('Por favor selecciona un horario base');
                return false;
            }
        });
    </script>
</body>
</html>