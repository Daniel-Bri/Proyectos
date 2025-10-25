@extends('layouts.app')

@section('title', 'Detalles del Rol: ' . $rol->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-500 px-6 py-5">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-eye mr-3"></i>
                    Detalles del Rol
                </h2>
                <div class="space-x-2">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="bg-white text-purple-600 px-4 py-2 rounded-lg font-semibold shadow hover:bg-purple-50">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <a href="{{ route('admin.roles.edit', $rol->id) }}" 
                       class="bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-yellow-600">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Información del Rol -->
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Información del Rol
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Nombre:</p>
                        <p class="text-lg font-bold text-gray-800">{{ $rol->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Guard Name:</p>
                        <p class="text-lg text-gray-800">{{ $rol->guard_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Permisos -->
            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                    <i class="fas fa-key mr-2"></i>
                    Permisos Asignados ({{ $rol->permissions->count() }})
                </h3>
                @if($rol->permissions->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($rol->permissions as $permiso)
                            <span class="bg-green-100 text-green-800 text-sm px-3 py-2 rounded-full font-medium">
                                <i class="fas fa-check mr-1"></i> {{ $permiso->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Este rol no tiene permisos asignados.
                    </p>
                @endif
            </div>

            <!-- Acciones -->
            <div class="flex justify-center space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.roles.edit', $rol->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-edit mr-2"></i> Editar Rol
                </a>
                <form action="{{ route('admin.roles.destroy', $rol->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('¿Está seguro de eliminar este rol?')"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-trash mr-2"></i> Eliminar Rol
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection