@extends('layouts.app') {{-- ✅ Usar layout principal --}}

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
                {{-- ✅ RUTA CORREGIDA --}}
                <a href="{{ route('coordinador.materias.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Materia
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        @if($materias->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($materias as $materia)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 materia-card">
                    {{-- ... contenido de la card ... --}}
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium materia-estado">
                            @if($materia->grupoMaterias->count() > 0)
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Activa
                            @else
                                <i class="fas fa-clock text-amber-500 mr-1"></i>
                                Pendiente
                            @endif
                        </span>
                        <div class="flex gap-2">
                            {{-- ✅ RUTAS CORREGIDAS --}}
                            <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                Ver
                            </a>
                            <a href="{{ route('coordinador.materias.asignar-grupo', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-users mr-2"></i>
                                Grupos
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-2xl border border-deep-teal-100 shadow-lg">
                <table class="min-w-full divide-y divide-deep-teal-100">
                    {{-- ... cabecera de la tabla ... --}}
                    <tbody class="bg-white divide-y divide-deep-teal-50" id="materiasTable">
                        @foreach($materias as $materia)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200 materia-row">
                            {{-- ... contenido de la fila ... --}}
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    {{-- ✅ RUTAS CORREGIDAS --}}
                                    <a href="{{ route('coordinador.materias.show', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Detalles
                                    </a>
                                    <a href="{{ route('coordinador.materias.asignar-grupo', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-users mr-2"></i>
                                        Grupos
                                    </a>
                                    <a href="{{ route('coordinador.materias.edit', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                {{-- ... empty state ... --}}
                {{-- ✅ RUTA CORREGIDA --}}
                <a href="{{ route('coordinador.materias.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Registrar Primera Materia
                </a>
            </div>
        @endif
    </div>
</div>
@endsection