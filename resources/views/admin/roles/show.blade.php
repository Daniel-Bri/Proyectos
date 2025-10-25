@extends('layouts.app')

@section('title', 'Detalles del Rol')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-700 to-cyan-500 px-6 py-5 flex justify-between items-center">
        <h3 class="text-2xl font-bold text-white">
            <i class="fas fa-eye mr-2"></i> Detalles del Rol
        </h3>
        <a href="{{ route('admin.roles.index') }}" class="bg-white text-blue-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-blue-50">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="p-6 space-y-6">
        <div class="border rounded-lg p-4">
            <h4 class="font-semibold text-gray-700 mb-2">Nombre del Rol:</h4>
            <p class="text-lg text-gray-800">{{ $rol->name }}</p>
        </div>

        <div class="border rounded-lg p-4">
            <h4 class="font-semibold text-gray-700 mb-3">Permisos Asignados:</h4>
            @if($rol->permissions->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($rol->permissions as $permiso)
                        <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full">
                            <i class="fas fa-key mr-1"></i> {{ $permiso->name }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay permisos asignados.</p>
            @endif
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.roles.edit', $rol->id) }}" class="bg-yellow-500 text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-yellow-600">
                <i class="fas fa-edit mr-2"></i> Editar Rol
            </a>
        </div>
    </div>
</div>
@endsection
