@extends('layouts.app')

@section('title', 'Mis Materias - Docente')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header con Sistema FICCT en la parte superior -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex-1">
                <!-- Sistema FICCT en la parte superior -->
                <div class="mb-3">
                    <span class="inline-flex items-center px-3 py-1 bg-[#F2E3D5] bg-opacity-20 text-[#F2E3D5] text-sm font-semibold rounded-full border border-[#F2E3D5] border-opacity-30">
                        <i class="fas fa-university mr-2"></i>
                        Sistema FICCT
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-book mr-3"></i>
                    Mis Materias Asignadas
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Gestiona las materias que tienes asignadas este período
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('docente.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Dashboard
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

        @if($materias && $materias->count() > 0)
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

                    <!-- Información de Grupos -->
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Grupos Asignados</p>
                        <div class="flex flex-wrap gap-1">
                            @if($materia->grupoMaterias && $materia->grupoMaterias->count() > 0)
                                @foreach($materia->grupoMaterias->take(3) as $grupoMateria)
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-100">
                                        {{ $grupoMateria->grupo->nombre ?? 'N/A' }}
                                        @if($grupoMateria->horarios && $grupoMateria->horarios->count() > 0)
                                            ({{ $grupoMateria->horarios->count() }}h)
                                        @endif
                                    </span>
                                @endforeach
                                @if($materia->grupoMaterias->count() > 3)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-600 text-xs rounded-lg border border-gray-100">
                                        +{{ $materia->grupoMaterias->count() - 3 }} más
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-500 text-xs rounded-lg border border-gray-100">
                                    Sin grupos
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Horarios -->
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Horarios</p>
                        <div class="space-y-1">
                            @php
                                $horariosCount = 0;
                                if($materia->grupoMaterias) {
                                    foreach($materia->grupoMaterias as $grupoMateria) {
                                        $horariosCount += $grupoMateria->horarios ? $grupoMateria->horarios->count() : 0;
                                    }
                                }
                            @endphp
                            @if($horariosCount > 0)
                                <span class="inline-flex items-center px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg border border-purple-100">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $horariosCount }} horario(s)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-gray-500 text-xs rounded-lg border border-gray-100">
                                    Sin horarios definidos
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="fas fa-users mr-1"></i>
                            {{ $materia->grupoMaterias ? $materia->grupoMaterias->count() : 0 }} grupos
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('docente.materias.show', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                Detalles
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Horarios</th>
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
                                @php
                                    $gruposCount = $materia->grupoMaterias ? $materia->grupoMaterias->count() : 0;
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg 
                                    {{ $gruposCount > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $gruposCount }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                @php
                                    $horariosCount = 0;
                                    if($materia->grupoMaterias) {
                                        foreach($materia->grupoMaterias as $grupoMateria) {
                                            $horariosCount += $grupoMateria->horarios ? $grupoMateria->horarios->count() : 0;
                                        }
                                    }
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg 
                                    {{ $horariosCount > 0 ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $horariosCount }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @php
                                    $gruposCount = $materia->grupoMaterias ? $materia->grupoMaterias->count() : 0;
                                    $horariosCount = 0;
                                    if($materia->grupoMaterias) {
                                        foreach($materia->grupoMaterias as $grupoMateria) {
                                            $horariosCount += $grupoMateria->horarios ? $grupoMateria->horarios->count() : 0;
                                        }
                                    }
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $gruposCount > 0 && $horariosCount > 0 ? 'bg-green-100 text-green-800' : 
                                       ($gruposCount > 0 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $gruposCount > 0 && $horariosCount > 0 ? 'Activa' : 
                                       ($gruposCount > 0 ? 'Sin horarios' : 'Sin grupos') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('docente.materias.show', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Detalles
                                    </a>
                                    <a href="{{ route('docente.horarios.index') }}?materia={{ $materia->sigla }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-clock mr-2"></i>
                                        Horarios
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Resumen -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-medium">Resumen de materias asignadas</p>
                        <p class="text-blue-600 text-sm">
                            Tienes <strong>{{ $materias->count() }}</strong> materia(s) asignada(s) con un total de 
                            <strong>
                                @php
                                    $totalGrupos = 0;
                                    $totalHorarios = 0;
                                    foreach($materias as $materia) {
                                        $totalGrupos += $materia->grupoMaterias ? $materia->grupoMaterias->count() : 0;
                                        if($materia->grupoMaterias) {
                                            foreach($materia->grupoMaterias as $grupoMateria) {
                                                $totalHorarios += $grupoMateria->horarios ? $grupoMateria->horarios->count() : 0;
                                            }
                                        }
                                    }
                                @endphp
                                {{ $totalGrupos }}
                            </strong> grupo(s) y 
                            <strong>{{ $totalHorarios }}</strong> horario(s) semanales.
                        </p>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-book-open text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No tienes materias asignadas</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Actualmente no se te han asignado materias para este período académico.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('docente.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('docente.horarios.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-clock mr-2"></i>
                        Ver Horarios
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efectos hover para las tarjetas
    const cards = document.querySelectorAll('.bg-white.border');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
@endsection