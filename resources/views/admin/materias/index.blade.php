@extends('layouts.app')

@section('title', 'Gestión de Materias - Admin')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-book mr-3"></i>
                    Gestión Completa de Materias
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Administra todas las materias del sistema académico
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.materias.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Materia
                </a>
                <a href="{{ route('admin.materias.export') }}" 
                   id="exportBtn"
                   class="inline-flex items-center px-4 py-2 bg-[#024959] hover:bg-[#012E40] border border-transparent rounded-xl font-semibold text-xs text-[#F2E3D5] uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar Excel
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Alertas -->
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

                    <!-- Información de Grupos -->
                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Grupos Asignados</p>
                        <div class="flex flex-wrap gap-1">
                            @if($materia->grupoMaterias->count() > 0)
                                @foreach($materia->grupoMaterias->take(3) as $grupoMateria)
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-100">
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
                            <a href="{{ route('admin.materias.show', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                Ver
                            </a>
                            <a href="{{ route('admin.materias.edit', $materia->sigla) }}" 
                               class="inline-flex items-center px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
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
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-lg 
                                    {{ $materia->grupoMaterias->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $materia->grupoMaterias->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $materia->grupoMaterias->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $materia->grupoMaterias->count() > 0 ? 'Activa' : 'Sin grupos' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.materias.show', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-2"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.materias.edit', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar
                                    </a>
                                    <a href="{{ route('admin.materias.asignar-grupo', $materia->sigla) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-users mr-2"></i>
                                        Grupos
                                    </a>
                                    <form action="{{ route('admin.materias.destroy', $materia->sigla) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Está seguro de eliminar esta materia?')"
                                                class="inline-flex items-center px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                            <i class="fas fa-trash mr-2"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Información de exportación -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-medium">Exportación disponible</p>
                        <p class="text-blue-600 text-sm">Puede exportar el listado completo de materias haciendo clic en "Exportar Excel"</p>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-book text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay materias registradas</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Comienza agregando la primera materia al sistema académico.
                </p>
                <a href="{{ route('admin.materias.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primera Materia
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportBtn');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            // Mostrar mensaje de confirmación
            if (!confirm('¿Está seguro de que desea exportar el listado de materias a Excel?')) {
                e.preventDefault();
            } else {
                // Mostrar mensaje de carga
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exportando...';
                exportBtn.classList.add('opacity-50', 'cursor-not-allowed');
                
                // Restaurar después de 3 segundos (por si hay error)
                setTimeout(() => {
                    exportBtn.innerHTML = originalText;
                    exportBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }, 3000);
            }
        });
    }
});
</script>
@endpush
@endsection