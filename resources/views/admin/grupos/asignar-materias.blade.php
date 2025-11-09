@extends('layouts.app')

@section('title', 'Asignar Materias - Admin')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-4">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header Mobile Optimizado -->
        <div class="gradient-bg px-3 py-4 sm:px-6">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-[#F2E3D5]">
                        <i class="fas fa-book mr-2 sm:mr-3"></i>
                        Asignar Materias a Grupo
                    </h3>
                    <p class="mt-1 sm:mt-2 text-deep-teal-200 text-xs sm:text-sm">
                        Asigna materias específicas para: <strong>{{ $grupo->nombre }}</strong>
                    </p>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <a href="{{ route('admin.grupos.show', $grupo->id) }}" 
                       class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg sm:rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <form action="{{ route('admin.grupos.store-asignar-materias', $grupo->id) }}" method="POST" id="asignacionForm">
                @csrf

                @if($errors->any())
                    <div class="mb-4 sm:mb-6 bg-rose-50 border border-rose-200 rounded-xl sm:rounded-2xl p-3 sm:p-5">
                        <div class="flex items-center mb-2 sm:mb-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-xs sm:text-base"></i>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-rose-800">Corrige los siguientes errores</h4>
                        </div>
                        <ul class="list-disc list-inside text-rose-700 space-y-1 text-xs sm:text-sm">
                            @foreach($errors->all() as $error)
                                <li class="break-words">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Información del Grupo -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-blue-100 shadow-sm mb-6 sm:mb-8">
                    <h4 class="text-base sm:text-lg font-bold text-blue-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-sm sm:text-base"></i>
                        Información del Grupo
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                {{ substr($grupo->nombre, 0, 2) }}
                            </div>
                            <p class="font-bold text-blue-900 text-xs sm:text-sm">{{ $grupo->nombre }}</p>
                            <p class="text-blue-700 text-xs">Grupo</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                <i class="fas fa-book"></i>
                            </div>
                            <p class="font-bold text-purple-900 text-xs sm:text-sm">{{ $grupo->grupoMaterias->count() }}</p>
                            <p class="text-purple-700 text-xs">Materias Actuales</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center text-white text-lg sm:text-2xl font-bold shadow-lg">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <p class="font-bold text-orange-900 text-xs sm:text-sm">{{ $materias->count() }}</p>
                            <p class="text-orange-700 text-xs">Materias Disp.</p>
                        </div>
                    </div>
                </div>

                @if($materias->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
                    <!-- Selección de Materias -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-emerald-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-emerald-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-book mr-2 text-sm sm:text-base"></i>
                            Materias Disponibles
                        </h4>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-emerald-700 mb-2">
                                    Seleccione las materias a asignar *
                                </label>
                                <div class="max-h-60 sm:max-h-80 overflow-y-auto border border-emerald-200 rounded-lg sm:rounded-xl bg-white p-2 sm:p-4" id="lista-materias">
                                    @foreach($materias as $materia)
                                    <div class="materia-item flex items-center p-2 sm:p-3 hover:bg-emerald-50 rounded-lg transition-colors duration-150 border border-transparent hover:border-emerald-200 cursor-pointer" onclick="toggleMateria('{{ $materia->sigla }}')">
                                        <input type="checkbox" 
                                               name="materias[]" 
                                               value="{{ $materia->sigla }}" 
                                               id="materia_{{ $materia->sigla }}"
                                               class="materia-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-emerald-300 rounded transition-colors duration-200 pointer-events-none">
                                        <label for="materia_{{ $materia->sigla }}" class="ml-3 flex-1 cursor-pointer select-none">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-emerald-900">{{ $materia->sigla }}</span>
                                                <span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">
                                                    S{{ $materia->semestre }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-emerald-700 mt-1">{{ \Illuminate\Support\Str::limit($materia->nombre, 40) }}</p>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-emerald-600 mt-2">Seleccione una o más materias para asignar al grupo</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Selección -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-amber-100 shadow-sm">
                        <h4 class="text-base sm:text-lg font-bold text-amber-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-sm sm:text-base"></i>
                            Resumen de Selección
                        </h4>
                        
                        <div id="resumen-seleccion" class="space-y-3 min-h-[200px]">
                            <div class="text-center py-8 text-amber-500">
                                <i class="fas fa-mouse-pointer text-2xl mb-2"></i>
                                <p class="text-sm">Seleccione materias para ver el resumen</p>
                            </div>
                        </div>

                        <div id="contador-seleccion" class="mt-4 p-3 bg-amber-100 rounded-lg hidden">
                            <div class="flex items-center justify-between">
                                <span class="text-amber-800 font-medium text-sm">Total seleccionado:</span>
                                <span id="total-seleccionados" class="px-2 py-1 bg-amber-500 text-white rounded-full text-xs font-bold">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado de la Asignación -->
                <div id="estado-asignacion" class="hidden mb-6 sm:mb-8 p-4 bg-green-50 border border-green-200 rounded-xl sm:rounded-2xl">
                    <div class="flex items-center">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                            <i class="fas fa-check text-xs sm:text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-800 text-sm sm:text-base" id="estado-texto">Asignación lista</p>
                            <p class="text-green-600 text-xs sm:text-sm" id="estado-descripcion">Puede confirmar la asignación de materias</p>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end pt-4 sm:pt-6 border-t border-deep-teal-100">
                    <a href="{{ route('admin.grupos.show', $grupo->id) }}" 
                       class="order-2 sm:order-1 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-xs sm:text-sm">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            id="btn-submit"
                            class="order-1 sm:order-2 inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-400 text-white font-bold rounded-lg sm:rounded-xl transition-all duration-200 text-xs sm:text-sm mb-2 sm:mb-0 cursor-not-allowed"
                            disabled>
                        <i class="fas fa-book mr-1 sm:mr-2"></i>
                        Confirmar Asignación
                    </button>
                </div>
                @else
                <!-- Estado cuando no hay materias disponibles -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-6 bg-amber-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-amber-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-700 mb-3">No hay materias disponibles</h3>
                    <p class="text-amber-600 mb-6">Todas las materias ya están asignadas a este grupo o no hay materias activas en el sistema.</p>
                    <a href="{{ route('admin.grupos.show', $grupo->id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Grupo
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@if($materias->count() > 0)
<script>
// Función global para alternar selección de materia
function toggleMateria(sigla) {
    const checkbox = document.getElementById('materia_' + sigla);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        
        // Añadir clase visual cuando está seleccionado
        const item = checkbox.closest('.materia-item');
        if (checkbox.checked) {
            item.classList.add('bg-emerald-100', 'border-emerald-300');
            item.classList.remove('hover:bg-emerald-50');
        } else {
            item.classList.remove('bg-emerald-100', 'border-emerald-300');
            item.classList.add('hover:bg-emerald-50');
        }
        
        actualizarInterfaz();
    }
}

// Función para remover materia del resumen
function removerMateria(sigla) {
    const checkbox = document.getElementById('materia_' + sigla);
    if (checkbox) {
        checkbox.checked = false;
        
        // Remover clase visual
        const item = checkbox.closest('.materia-item');
        item.classList.remove('bg-emerald-100', 'border-emerald-300');
        item.classList.add('hover:bg-emerald-50');
        
        actualizarInterfaz();
    }
}

// Función principal para actualizar la interfaz
function actualizarInterfaz() {
    const checkboxes = document.querySelectorAll('.materia-checkbox');
    const seleccionados = Array.from(checkboxes).filter(cb => cb.checked);
    const total = seleccionados.length;
    
    console.log('Materias seleccionadas:', total);
    
    // Actualizar contador
    document.getElementById('total-seleccionados').textContent = total;
    
    // Actualizar resumen
    const resumenContainer = document.getElementById('resumen-seleccion');
    const contadorContainer = document.getElementById('contador-seleccion');
    const estadoAsignacion = document.getElementById('estado-asignacion');
    const btnSubmit = document.getElementById('btn-submit');
    
    if (total > 0) {
        // Mostrar contador
        contadorContainer.classList.remove('hidden');
        contadorContainer.style.display = 'block';
        
        // Actualizar resumen con materias seleccionadas
        let html = '';
        seleccionados.forEach((checkbox) => {
            const sigla = checkbox.value;
            const item = checkbox.closest('.materia-item');
            const nombre = item.querySelector('p').textContent;
            const semestre = item.querySelector('span:last-child').textContent;
            
            html += `
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-amber-200 shadow-sm">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <span class="font-bold text-amber-900 text-sm">${sigla}</span>
                            <span class="ml-2 text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded-full">${semestre}</span>
                        </div>
                        <p class="text-xs text-amber-700 mt-1">${nombre}</p>
                    </div>
                    <button type="button" 
                            class="ml-2 text-rose-500 hover:text-rose-700 transition-colors duration-150 p-1 rounded-full hover:bg-rose-50"
                            onclick="removerMateria('${sigla}')">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `;
        });
        resumenContainer.innerHTML = html;
        
        // Mostrar estado de asignación
        estadoAsignacion.classList.remove('hidden');
        estadoAsignacion.style.display = 'block';
        document.getElementById('estado-texto').textContent = `${total} materia(s) seleccionada(s)`;
        
        // Habilitar botón
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('bg-gray-400', 'cursor-not-allowed');
        btnSubmit.classList.add('bg-[#3CA6A6]', 'hover:bg-[#026773]', 'shadow-lg', 'hover:shadow-xl', 'transform', 'hover:-translate-y-0.5', 'cursor-pointer');
        
    } else {
        // Ocultar contador
        contadorContainer.classList.add('hidden');
        contadorContainer.style.display = 'none';
        
        // Mostrar mensaje vacío
        resumenContainer.innerHTML = `
            <div class="text-center py-8 text-amber-500">
                <i class="fas fa-mouse-pointer text-2xl mb-2"></i>
                <p class="text-sm">Seleccione materias para ver el resumen</p>
            </div>
        `;
        
        // Ocultar estado de asignación
        estadoAsignacion.classList.add('hidden');
        estadoAsignacion.style.display = 'none';
        
        // Deshabilitar botón
        btnSubmit.disabled = true;
        btnSubmit.classList.remove('bg-[#3CA6A6]', 'hover:bg-[#026773]', 'shadow-lg', 'hover:shadow-xl', 'transform', 'hover:-translate-y-0.5', 'cursor-pointer');
        btnSubmit.classList.add('bg-gray-400', 'cursor-not-allowed');
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de asignación de materias cargado');
    
    // Agregar event listeners a los checkboxes para cambios directos
    const checkboxes = document.querySelectorAll('.materia-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Actualizar clase visual del item
            const item = this.closest('.materia-item');
            if (this.checked) {
                item.classList.add('bg-emerald-100', 'border-emerald-300');
                item.classList.remove('hover:bg-emerald-50');
            } else {
                item.classList.remove('bg-emerald-100', 'border-emerald-300');
                item.classList.add('hover:bg-emerald-50');
            }
            actualizarInterfaz();
        });
    });
    
    // Validación del formulario
    const form = document.getElementById('asignacionForm');
    form.addEventListener('submit', function(e) {
        const seleccionados = document.querySelectorAll('.materia-checkbox:checked').length;
        
        if (seleccionados === 0) {
            e.preventDefault();
            alert('Por favor, seleccione al menos una materia antes de enviar.');
            return false;
        }
        
        // Mostrar estado de carga
        const btnSubmit = document.getElementById('btn-submit');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
        btnSubmit.disabled = true;
        
        // Restaurar el texto después de 3 segundos por si hay error
        setTimeout(() => {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }, 3000);
    });
    
    // Inicializar interfaz
    actualizarInterfaz();
});
</script>
@endif
@endsection