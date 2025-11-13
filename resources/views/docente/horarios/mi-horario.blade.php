@extends('layouts.app')

@section('title', 'Mi Horario Semanal - Docente')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-calendar-week mr-3"></i>
                    Mi Horario Semanal
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    <i class="fas fa-user mr-2"></i>
                    Docente: {{ $docente->user->name ?? 'N/A' }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('docente.horarios.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-list mr-2"></i>
                    Vista de Lista
                </a>
                <a href="{{ url('/dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#024959] hover:bg-[#012E40] border border-transparent rounded-xl font-semibold text-xs text-[#F2E3D5] uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        @if($horarios->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-deep-teal-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                            <th class="px-6 py-4 text-center text-xs font-bold text-[#F2E3D5] uppercase tracking-wider border-r border-deep-teal-300">
                                <i class="fas fa-clock mr-2"></i>Hora/Día
                            </th>
                            @foreach($diasOrdenados as $dia)
                            <th class="px-6 py-4 text-center text-xs font-bold text-[#F2E3D5] uppercase tracking-wider border-r border-deep-teal-300 last:border-r-0">
                                @php
                                    $nombresDias = [
                                        'LUN' => 'Lunes',
                                        'MAR' => 'Martes',
                                        'MIE' => 'Miércoles', 
                                        'JUE' => 'Jueves',
                                        'VIE' => 'Viernes',
                                        'SAB' => 'Sábado'
                                    ];
                                @endphp
                                <i class="fas fa-calendar-day mr-2"></i>
                                {{ $nombresDias[$dia] ?? $dia }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @for($hora = 7; $hora <= 21; $hora++)
                        <tr class="hover:bg-deep-teal-25 transition-colors duration-200 border-b border-deep-teal-100 last:border-b-0">
                            <td class="px-6 py-4 text-center bg-deep-teal-50 font-bold text-deep-teal-800 border-r border-deep-teal-100">
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fas fa-clock text-deep-teal-500"></i>
                                    <span class="font-mono">{{ sprintf('%02d:00', $hora) }}</span>
                                </div>
                            </td>
                            @foreach($diasOrdenados as $dia)
                            <td class="p-3 min-w-[220px] border-r border-deep-teal-100 last:border-r-0">
                                @foreach($horariosPorDia->get($dia, []) as $horario)
                                    @php
                                        $horaInicio = \Carbon\Carbon::parse($horario->horario->hora_inicio);
                                        $horaFin = \Carbon\Carbon::parse($horario->horario->hora_fin);
                                    @endphp
                                    @if($horaInicio->hour == $hora)
                                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-l-4 border-blue-500 p-4 mb-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5">
                                        <!-- Header de la materia -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                                        {{ substr($horario->grupoMateria->materia->sigla, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-blue-800 text-sm leading-tight">
                                                            {{ $horario->grupoMateria->materia->nombre }}
                                                        </p>
                                                        <p class="text-xs text-blue-600">
                                                            {{ $horario->grupoMateria->materia->sigla }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información detallada -->
                                        <div class="space-y-2">
                                            <div class="flex items-center text-xs text-deep-teal-700">
                                                <i class="fas fa-users mr-2 text-green-500 w-4"></i>
                                                <span class="font-semibold">Grupo:</span>
                                                <span class="ml-1 bg-green-100 text-green-800 px-2 py-1 rounded-lg text-xs font-bold">
                                                    {{ $horario->grupoMateria->grupo->nombre }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center text-xs text-deep-teal-700">
                                                <i class="fas fa-door-open mr-2 text-purple-500 w-4"></i>
                                                <span class="font-semibold">Aula:</span>
                                                <span class="ml-1 bg-purple-100 text-purple-800 px-2 py-1 rounded-lg text-xs font-bold">
                                                    {{ $horario->aula->nombre }}
                                                </span>
                                            </div>

                                            <!-- Horario específico -->
                                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-deep-teal-100 mt-2">
                                                <span class="flex items-center text-xs text-deep-teal-600 font-semibold">
                                                    <i class="fas fa-clock mr-1 text-amber-500"></i>
                                                    Horario:
                                                </span>
                                                <span class="font-mono font-bold text-deep-teal-800 text-sm">
                                                    {{ $horaInicio->format('H:i') }} - {{ $horaFin->format('H:i') }}
                                                </span>
                                            </div>

                                            <!-- Duración -->
                                            <div class="flex justify-end">
                                                @php
                                                    $duracion = $horaInicio->diffInHours($horaFin);
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 text-xs font-bold rounded-lg">
                                                    <i class="fas fa-hourglass-half mr-1"></i>
                                                    {{ $duracion }}h
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </td>
                            @endforeach
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Información de resumen -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-bold text-lg">{{ $horarios->count() }}</p>
                        <p class="text-blue-600 text-sm">Total de horarios</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <p class="text-green-800 font-bold text-lg">{{ $horarios->unique('grupoMateria.materia.id')->count() }}</p>
                        <p class="text-green-600 text-sm">Materias diferentes</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-purple-800 font-bold text-lg">{{ $horarios->unique('grupoMateria.grupo.id')->count() }}</p>
                        <p class="text-purple-600 text-sm">Grupos asignados</p>
                    </div>
                </div>
            </div>
        </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-times text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No tienes horarios asignados</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Actualmente no tienes horarios asignados. Contacta con la coordinación académica.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ url('/dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('docente.horarios.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#024959] hover:bg-[#012E40] text-[#F2E3D5] font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-list mr-2"></i>
                        Vista de Lista
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efectos hover para las tarjetas de horario
    const horarioCards = document.querySelectorAll('.transform');
    horarioCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Resaltar la hora actual
    function resaltarHoraActual() {
        const ahora = new Date();
        const horaActual = ahora.getHours();
        const celdasHora = document.querySelectorAll('td:first-child');
        
        celdasHora.forEach(celda => {
            const horaTexto = celda.textContent.trim();
            const hora = parseInt(horaTexto.split(':')[0]);
            
            if (hora === horaActual) {
                celda.classList.add('bg-amber-100', 'text-amber-800');
                celda.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas fa-play-circle text-amber-500"></i>
                        <span class="font-mono font-bold">${horaTexto}</span>
                        <span class="text-xs bg-amber-500 text-white px-2 py-1 rounded-full">Ahora</span>
                    </div>
                `;
            }
        });
    }

    resaltarHoraActual();
});
</script>
@endpush
@endsection