@extends('layouts.app')

@section('title', 'Gestión de Materias - Coordinador')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-book-open mr-3"></i>
                    Gestión Académica de Materias
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra las materias de tu área académica
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('coordinador.materias.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Materia
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-amber-800 font-medium">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <p class="text-rose-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Buscador y Filtros -->
        <div class="mb-6 bg-white rounded-2xl border border-deep-teal-100 p-4 sm:p-6 shadow-sm">
            <form action="{{ route('coordinador.materias.index') }}" method="GET" id="searchForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Buscador por texto -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-deep-teal-700 mb-2">
                            <i class="fas fa-search mr-2"></i>Buscar Materias
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-[#3CA6A6] transition-all duration-200 bg-white shadow-sm"
                                   placeholder="Buscar por sigla o nombre..."
                                   autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-deep-teal-400"></i>
                            </div>
                            @if(request('search'))
                            <button type="button" 
                                    onclick="clearSearch()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-deep-teal-400 hover:text-deep-teal-600">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Filtro por semestre -->
                    <div>
                        <label for="semestre" class="block text-sm font-medium text-deep-teal-700 mb-2">
                            <i class="fas fa-filter mr-2"></i>Semestre
                        </label>
                        <select name="semestre" 
                                id="semestre"
                                class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-[#3CA6A6] transition-all duration-200 bg-white shadow-sm"
                                onchange="this.form.submit()">
                            <option value="">Todos los semestres</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ request('semestre') == $i ? 'selected' : '' }}>
                                    Semestre {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filtro por estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-deep-teal-700 mb-2">
                            <i class="fas fa-chart-bar mr-2"></i>Estado
                        </label>
                        <select name="estado" 
                                id="estado"
                                class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-[#3CA6A6] transition-all duration-200 bg-white shadow-sm"
                                onchange="this.form.submit()">
                            <option value="">Todos los estados</option>
                            <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Con grupos</option>
                            <option value="sin_grupos" {{ request('estado') == 'sin_grupos' ? 'selected' : '' }}>Sin grupos</option>
                        </select>
                    </div>
                </div>

                <!-- Botones de acción del buscador -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 pt-4 border-t border-deep-teal-100">
                    <div class="text-sm text-deep-teal-600 mb-3 sm:mb-0">
                        @if(request()->anyFilled(['search', 'semestre', 'estado']))
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                <i class="fas fa-filter mr-1"></i>
                                Filtros aplicados
                            </span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if(request()->anyFilled(['search', 'semestre', 'estado']))
                            <a href="{{ route('coordinador.materias.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-times mr-2"></i>
                                Limpiar
                            </a>
                        @endif
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-search mr-2"></i>
                            Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Resultados de búsqueda -->
        @if(request()->anyFilled(['search', 'semestre', 'estado']))
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-medium">
                            Resultados de búsqueda
                            @if($materias->total() > 0)
                                <span class="text-blue-600">({{ $materias->total() }} materias encontradas)</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($materias->total() > 0)
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                    {{ $materias->firstItem() }} - {{ $materias->lastItem() }} de {{ $materias->total() }}
                </span>
                @endif
            </div>
        </div>
        @endif

        @if($materias->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($materias as $materia)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ substr($materia->sigla, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-sm">{{ $materia->sigla }}</p>
                                    <p class="text-xs text-deep-teal-600">{{ $materia->nombre }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm bg-green-100 text-green-800 border-green-200">
                            Semestre {{ $materia->semestre }}
                        </span>
                    </div>
                    
                    <div class="text-sm mb-4">
                        <div class="mb-3">
                            <p class="text-deep-teal-600 text-xs font-medium">Categoría</p>
                            <p class="font-bold text-deep-teal-800">{{ $materia->categoria->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Información de Grupos (SOLO LECTURA) -->
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Grupos Asignados</p>
                        <div class="flex flex-wrap gap-1">
                            @if($materia->grupoMaterias->count() > 0)
                                @foreach($materia->grupoMaterias->take(3) as $grupoMateria)
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-100">
                                        <i class="fas fa-users mr-1 text-xs"></i>
                                        {{ $grupoMateria->grupo->nombre ?? 'N/A' }}
                                    </span>
                                @endforeach
                                @if($materia->grupoMaterias->count() > 3)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-600 text-xs rounded-lg border border-gray-100">
                                        +{{ $materia->grupoMaterias->count() - 3 }} más
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-500 text-xs rounded-lg border border-gray-100">
                                    <i class="fas fa-users-slash mr-1 text-xs"></i>
                                    Sin grupos
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="fas fa-users mr-1"></i>
                            {{ $materia->grupoMaterias->count() }} grupos
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                Ver
                            </a>
                            <a href="{{ route('coordinador.materias.asignar-aulas', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-door-open mr-2"></i>
                                Aulas
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-2xl border border-deep-teal-100 shadow-lg">
                <table class="min-w-full divide-y divide-deep-teal-100">
                    <thead class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Semestre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Grupos</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($materias as $materia)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        {{ substr($materia->sigla, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800">
                                            {{ $materia->sigla }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600 max-w-xs truncate">
                                            {{ $materia->nombre }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-xl border shadow-sm bg-green-100 text-green-800 border-green-200">
                                    Semestre {{ $materia->semestre }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $materia->categoria->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center space-y-1">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg 
                                        {{ $materia->grupoMaterias->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $materia->grupoMaterias->count() }}
                                    </span>
                                    @if($materia->grupoMaterias->count() > 0)
                                        <div class="flex flex-wrap gap-1 justify-center">
                                            @foreach($materia->grupoMaterias->take(2) as $grupoMateria)
                                                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-100">
                                                    {{ $grupoMateria->grupo->nombre ?? 'N/A' }}
                                                </span>
                                            @endforeach
                                            @if($materia->grupoMaterias->count() > 2)
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-600 text-xs rounded-lg border border-gray-100">
                                                    +{{ $materia->grupoMaterias->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $materia->grupoMaterias->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    @if($materia->grupoMaterias->count() > 0)
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Activa
                                    @else
                                        <i class="fas fa-clock mr-1"></i>
                                        Sin grupos
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Detalles
                                    </a>
                                    <a href="{{ route('coordinador.materias.asignar-aulas', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-door-open mr-2"></i>
                                        Aulas
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($materias->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="bg-white rounded-2xl border border-deep-teal-100 p-4 shadow-sm">
                    {{ $materias->appends(request()->query())->links() }}
                </div>
            </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-search text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">
                    @if(request()->anyFilled(['search', 'semestre', 'estado']))
                        No se encontraron materias
                    @else
                        No hay materias registradas
                    @endif
                </h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    @if(request()->anyFilled(['search', 'semestre', 'estado']))
                        No hay materias que coincidan con tus criterios de búsqueda.
                    @else
                        Comienza agregando la primera materia al sistema académico.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if(request()->anyFilled(['search', 'semestre', 'estado']))
                        <a href="{{ route('coordinador.materias.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-2"></i>
                            Limpiar búsqueda
                        </a>
                    @endif
                    <a href="{{ route('coordinador.materias.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>
                        Registrar Primera Materia
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function clearSearch() {
    document.getElementById('search').value = '';
    document.getElementById('searchForm').submit();
}

// Auto-submit después de escribir (con delay)
let searchTimeout;
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
});

// Mostrar loading al buscar
document.getElementById('searchForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Buscando...';
        submitBtn.disabled = true;
    }
});
</script>
@endpush
@endsection