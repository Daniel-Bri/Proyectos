@extends('layouts.app')

@section('title', 'Detalles del Rol: ' . $rol->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-6 py-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-eye mr-3"></i>
                    Detalles del Rol
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#F2E3D5] hover:bg-white text-[#012E40] border border-transparent rounded-xl font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <a href="{{ route('admin.roles.edit', $rol->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Información del Rol -->
            <div class="bg-gradient-to-r from-[#3CA6A6] to-[#026773] rounded-xl p-6 border border-[#3CA6A6]">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Información del Rol
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-[#F2E3D5] font-medium">Nombre:</p>
                        <p class="text-lg font-bold text-white">{{ $rol->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#F2E3D5] font-medium">Guard Name:</p>
                        <p class="text-lg text-white">{{ $rol->guard_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Permisos -->
            <div class="bg-gradient-to-r from-[#012E40] to-[#024959] rounded-xl p-6 border border-[#012E40]">
                <h3 class="text-lg font-semibold text-[#F2E3D5] mb-4 flex items-center">
                    <i class="fas fa-key mr-2"></i>
                    Permisos Asignados ({{ $rol->permissions->count() }})
                </h3>
                @if($rol->permissions->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($rol->permissions as $permiso)
                            <span class="bg-[#3CA6A6] bg-opacity-20 text-white text-sm px-3 py-2 rounded-xl border border-[#3CA6A6] border-opacity-50 font-medium">
                                <i class="fas fa-check mr-1"></i> {{ $permiso->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-[#F2E3D5] text-center py-4 font-medium">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Este rol no tiene permisos asignados.
                    </p>
                @endif
            </div>

            <!-- Acciones -->
            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 pt-4 border-t border-deep-teal-200">
                <a href="{{ route('admin.roles.edit', $rol->id) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-edit mr-2"></i> Editar Rol
                </a>
                <form action="{{ route('admin.roles.destroy', $rol->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('¿Está seguro de eliminar este rol?')"
                            class="inline-flex items-center justify-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-trash mr-2"></i> Eliminar Rol
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.gradient-bg {
    background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
}

.border-deep-teal-200 {
    border-color: rgba(1, 46, 64, 0.2);
}

.text-deep-teal-200 {
    color: rgba(242, 227, 213, 0.8);
}
</style>
@endsection