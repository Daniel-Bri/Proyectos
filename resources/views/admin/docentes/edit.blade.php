@extends('layouts.app')

@section('title', 'Editar Docente')

@section('content')
<!-- Header Sistema FICCT -->
<div class="bg-gradient-to-r from-[#012E40] to-[#024959] px-4 py-5 sm:px-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#F2E3D5]">
                <i class="fas fa-user-edit mr-3"></i>
                Editar Docente: {{ $docente->user->name }}
            </h1>
            <p class="mt-2 text-deep-teal-200 text-sm">
                Actualice la información del docente
            </p>
        </div>
        <div class="card-tools">
            <a href="{{ route('docentes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <form action="{{ route('docentes.update', $docente->codigo) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información Personal -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                        <h4 class="text-lg font-bold text-blue-800 mb-6 flex items-center">
                            <i class="fas fa-user-circle mr-3"></i>
                            Información Personal
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="codigo" class="block text-sm font-bold text-blue-700 mb-2">
                                    Código del Docente *
                                </label>
                                <input type="text" 
                                       id="codigo" 
                                       name="codigo" 
                                       value="{{ old('codigo', $docente->codigo) }}"
                                       class="w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('codigo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nombre" class="block text-sm font-bold text-blue-700 mb-2">
                                    Nombre Completo *
                                </label>
                                <input type="text" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $docente->user->name) }}"
                                       class="w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-bold text-blue-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $docente->user->email) }}"
                                       class="w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-bold text-blue-700 mb-2">
                                    Teléfono *
                                </label>
                                <input type="text" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono', $docente->telefono) }}"
                                       class="w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('telefono')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información Laboral -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 shadow-sm">
                        <h4 class="text-lg font-bold text-green-800 mb-6 flex items-center">
                            <i class="fas fa-briefcase mr-3"></i>
                            Información Laboral
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="sueldo" class="block text-sm font-bold text-green-700 mb-2">
                                    Sueldo *
                                </label>
                                <input type="number" 
                                       step="0.01" 
                                       id="sueldo" 
                                       name="sueldo" 
                                       value="{{ old('sueldo', $docente->sueldo) }}"
                                       class="w-full px-4 py-3 border border-green-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('sueldo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fecha_contrato" class="block text-sm font-bold text-green-700 mb-2">
                                    Fecha de Contrato *
                                </label>
                                <input type="date" 
                                       id="fecha_contrato" 
                                       name="fecha_contrato" 
                                       value="{{ old('fecha_contrato', $docente->fecha_contrato ? $docente->fecha_contrato->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-3 border border-green-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('fecha_contrato')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fecha_final" class="block text-sm font-bold text-green-700 mb-2">
                                    Fecha Final *
                                </label>
                                <input type="date" 
                                       id="fecha_final" 
                                       name="fecha_final" 
                                       value="{{ old('fecha_final', $docente->fecha_final ? $docente->fecha_final->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-3 border border-green-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm"
                                       required>
                                @error('fecha_final')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-green-700 mb-2">
                                    Carreras
                                </label>
                                <div class="space-y-2 border border-green-200 rounded-xl p-4 bg-white max-h-48 overflow-y-auto">
                                    @foreach($carreras as $carrera)
                                        <label class="flex items-center space-x-3 p-2 hover:bg-green-50 rounded-lg cursor-pointer transition-colors duration-200">
                                            <input type="checkbox" 
                                                   name="carreras[]" 
                                                   value="{{ $carrera->id }}"
                                                   {{ in_array($carrera->id, old('carreras', $docente->carreras->pluck('id')->toArray())) ? 'checked' : '' }}
                                                   class="rounded border-green-300 text-green-600 focus:ring-green-500 h-4 w-4">
                                            <span class="text-sm font-medium text-gray-700">{{ $carrera->nombre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('carreras')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-green-600 mt-2">
                                    ✅ Selecciona una o más carreras haciendo clic en los checkboxes
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('docentes.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Docente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border: 1px solid transparent;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-secondary {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.card-tools {
    position: static;
}
</style>
@endsection