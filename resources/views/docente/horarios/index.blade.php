@extends('layouts.app')

@section('title', 'Mis Horarios - Docente')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Mis Horarios Asignados
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Gestiona y visualiza todos tus horarios académicos
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('docente.mi-horario') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-calendar-week mr-2"></i>
                    Vista Semanal
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

        @if($horarios->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($horarios as $horario)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    @php
                                        $dias = ['LUN' => 'L', 'MAR' => 'M', 'MIE' => 'X', 'JUE' => 'J', 'VIE' => 'V', 'SAB' => 'S'];
                                    @endphp
                                    {{ $dias[$horario->horario->dia] ?? $horario->horario->dia }}
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-sm">
                                        {{ \Carbon\Carbon::parse($horario->horario->hora_inicio)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($horario->horario->hora_fin)->format('H:i') }}
                                    </p>
                                    <p class="text-xs text-deep-teal-600">
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
                                        {{ $nombresDias[$horario->horario->dia] ?? $horario->horario->dia }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm bg-blue-100 text-blue-800 border-blue-200">
                            @php
                                $inicio = \Carbon\Carbon::parse($horario->horario->hora_inicio);
                                $fin = \Carbon\Carbon::parse($horario->horario->hora_fin);
                                $duracion = $inicio->diffInHours($fin);
                            @endphp
                            {{ $duracion }}h
                        </span>
                    </div>
                    
                    <div class="text-sm mb-4 space-y-3">
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Materia</p>
                            <p class="font-bold text-deep-teal-800">{{ $horario->grupoMateria->materia->nombre ?? 'N/A' }}</p>
                            <p class="text-xs text-deep-teal-500">{{ $horario->grupoMateria->materia->sigla ?? '' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-deep-teal-600 text-xs font-medium">Grupo</p>
                                <p class="font-bold text-deep-teal-800">{{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-deep-teal-600 text-xs font-medium">Aula</p>
                                <p class="font-bold text-deep-teal-800">{{ $horario->aula->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $horario->horario->hora_inicio }} - {{ $horario->horario->hora_fin }}
                        </span>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-lg border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Activo
                            </span>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Día y Horario</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Aula</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Duración</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($horarios as $horario)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                        @php
                                            $dias = ['LUN' => 'L', 'MAR' => 'M', 'MIE' => 'X', 'JUE' => 'J', 'VIE' => 'V', 'SAB' => 'S'];
                                        @endphp
                                        {{ $dias[$horario->horario->dia] ?? $horario->horario->dia }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-deep-teal-800">
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
                                            {{ $nombresDias[$horario->horario->dia] ?? $horario->horario->dia }}
                                        </div>
                                        <div class="text-sm text-deep-teal-600 font-mono">
                                            {{ \Carbon\Carbon::parse($horario->horario->hora_inicio)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($horario->horario->hora_fin)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-bold text-deep-teal-800">
                                    {{ $horario->grupoMateria->materia->nombre ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-deep-teal-500">
                                    {{ $horario->grupoMateria->materia->sigla ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-xl border shadow-sm bg-green-100 text-green-800 border-green-200">
                                    {{ $horario->grupoMateria->grupo->nombre ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-xl border shadow-sm bg-blue-100 text-blue-800 border-blue-200">
                                    {{ $horario->aula->nombre ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                @php
                                    $inicio = \Carbon\Carbon::parse($horario->horario->hora_inicio);
                                    $fin = \Carbon\Carbon::parse($horario->horario->hora_fin);
                                    $duracion = $inicio->diffInHours($fin);
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg bg-orange-100 text-orange-800">
                                    {{ $duracion }} hora{{ $duracion > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Activo
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Información de horarios -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-medium">Resumen de horarios</p>
                        <p class="text-blue-600 text-sm">
                            Total: <span class="font-bold">{{ $horarios->total() }}</span> horarios asignados | 
                            Días: <span class="font-bold">{{ $horarios->unique('horario.dia')->count() }}</span> días diferentes
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($horarios->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="bg-white rounded-2xl border border-deep-teal-100 shadow-lg p-4">
                    {{ $horarios->links() }}
                </div>
            </div>
            @endif

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
                    <a href="{{ route('docente.mi-horario') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#024959] hover:bg-[#012E40] text-[#F2E3D5] font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-calendar-week mr-2"></i>
                        Vista Semanal
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efectos hover para las tarjetas móviles
    const cards = document.querySelectorAll('.transform');
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