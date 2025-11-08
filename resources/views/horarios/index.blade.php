<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación Manual de Horarios</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Asignación Manual de Horarios</h1>
                        <p class="text-xs text-cream-300">Gestión y administración de horarios académicos</p>
                    </div>
                </div>

                <a href="{{ url('/dashboard') }}"
                   class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- BOTONES DE ACCIÓN PRINCIPAL -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- BOTÓN CREAR HORARIO -->
                    <a href="{{ route('coordinador.horarios.create') }}"
                       class="flex-1 bg-cream-200 text-deep-teal-700 px-6 py-4 rounded-lg font-semibold hover:bg-cream-300 transition shadow-md flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-lg">Crear Horario</div>
                            <div class="text-sm opacity-75">Nuevo bloque horario</div>
                        </div>
                    </a>

                    <!-- BOTÓN ASIGNAR HORARIO -->
                    <a href="{{ route('coordinador.horarios.asignar') }}"
                       class="flex-1 bg-deep-teal-400 text-cream-200 px-6 py-4 rounded-lg font-semibold hover:bg-deep-teal-300 transition shadow-md flex items-center justify-center gap-3 border border-deep-teal-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-lg">Asignar Horario</div>
                            <div class="text-sm opacity-75">A grupo y docente</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- FILTROS DE BÚSQUEDA -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-lg font-semibold mb-4 text-cream-200">Filtros de Búsqueda</h2>
                <form method="GET" class="grid lg:grid-cols-4 gap-4">
                    <!-- FILTRO POR DOCENTE -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-cream-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Docente
                        </label>
                        <select class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                            <option value="" class="bg-deep-teal-700">Todos los docentes</option>
                            <!-- Opciones de docentes -->
                        </select>
                    </div>

                    <!-- FILTRO POR MATERIA -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-cream-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Materia
                        </label>
                        <select class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                            <option value="" class="bg-deep-teal-700">Todas las materias</option>
                            <!-- Opciones de materias -->
                        </select>
                    </div>

                    <!-- FILTRO POR GRUPO -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-cream-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Grupo
                        </label>
                        <select class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent">
                            <option value="" class="bg-deep-teal-700">Todos los grupos</option>
                            <!-- Opciones de grupos -->
                        </select>
                    </div>

                    <!-- BOTONES DE ACCIÓN -->
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="w-full bg-cream-200 text-deep-teal-700 px-6 py-3 rounded-lg font-medium hover:bg-cream-300 transition shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                        <a href="?"
                           class="bg-deep-teal-600 text-cream-200 px-4 py-3 rounded-lg font-medium hover:bg-deep-teal-500 transition shadow-md flex items-center justify-center border border-deep-teal-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    </div>
                </form>
            </div>

            <!-- LISTA DE HORARIOS -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-cream-200">Horarios Asignados</h2>
                    <div class="text-cream-300 text-sm mt-2 sm:mt-0">
                        Total: <span class="font-semibold">{{ $horarios->count() }}</span> horarios
                    </div>
                </div>

                <!-- TABLA DE HORARIOS -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-deep-teal-400">
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Día</th>
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Horario</th>
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Materia</th>
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Docente</th>
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Grupo</th>
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Aula</th>
                                <th class="text-left py-3 px-4 text-cream-200 font-semibold text-sm">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horarios as $horario)
                            <tr class="border-b border-deep-teal-600 hover:bg-deep-teal-600/50 transition">
                                <!-- DÍA -->
                                <td class="py-3 px-4 text-cream-200 text-sm">
                                    <span class="bg-deep-teal-500 text-cream-200 px-2 py-1 rounded text-xs font-medium">
                                        {{ $horario->horario->dia ?? 'N/A' }}
                                    </span>
                                </td>
                                
                                <!-- HORARIO -->
                                <td class="py-3 px-4 text-cream-200 text-sm">
                                    {{ $horario->horario->hora_inicio ?? 'N/A' }} - {{ $horario->horario->hora_fin ?? 'N/A' }}
                                </td>
                                
                                <!-- MATERIA -->
                                <td class="py-3 px-4 text-cream-200 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                        {{ $horario->grupoMateria->materia->nombre ?? 'Sin materia' }}
                                        @if($horario->grupoMateria && $horario->grupoMateria->materia)
                                            <span class="text-xs text-cream-300">({{ $horario->grupoMateria->materia->sigla }})</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- DOCENTE -->
                                <td class="py-3 px-4 text-cream-200 text-sm">
                                    {{ $horario->docente->user->name ?? 'Sin docente' }}
                                    @if($horario->docente)
                                        <span class="text-xs text-cream-300 block">({{ $horario->docente->codigo }})</span>
                                    @endif
                                </td>
                                
                                <!-- GRUPO -->
                                <td class="py-3 px-4 text-cream-200 text-sm">
                                    <span class="bg-deep-teal-500 text-cream-200 px-2 py-1 rounded text-xs">
                                        {{ $horario->grupoMateria->grupo->nombre ?? 'Sin grupo' }}
                                    </span>
                                </td>
                                
                                <!-- AULA -->
                                <td class="py-3 px-4 text-cream-200 text-sm">
                                    {{ $horario->aula->nombre ?? 'Sin aula' }}
                                </td>
                                
                                <!-- ACCIONES -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <!-- BOTÓN VER -->
                                        <a href="{{ route('coordinador.horarios.show', $horario->id) }}"
                                        class="text-cream-300 hover:text-cream-200 transition p-2 rounded-lg bg-deep-teal-500 hover:bg-deep-teal-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- BOTÓN EDITAR -->
                                        <a href="{{ route('coordinador.horarios.edit', $horario->id) }}"
                                        class="text-blue-300 hover:text-blue-200 transition p-2 rounded-lg bg-deep-teal-500 hover:bg-deep-teal-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- BOTÓN ELIMINAR -->
                                        <form action="{{ route('coordinador.horarios.destroy', $horario->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('¿Estás seguro de eliminar este horario?')"
                                                    class="text-red-300 hover:text-red-200 transition p-2 rounded-lg bg-deep-teal-500 hover:bg-deep-teal-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center">
                                    <div class="text-cream-300">
                                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <h3 class="text-lg font-semibold text-cream-200 mb-2">No hay horarios asignados</h3>
                                        <p class="text-cream-300">Comienza creando tu primer horario</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                @if($horarios->hasPages())
                <div class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        {{ $horarios->links() }}
                    </div>
                </div>
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
        // Confirmación para eliminar horarios
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[action*="destroy"]');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('¿Estás seguro de que deseas eliminar este horario? Esta acción no se puede deshacer.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>