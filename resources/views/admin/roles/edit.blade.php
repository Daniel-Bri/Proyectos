@extends('layouts.app')

@section('title', 'Editar Rol')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-600 to-orange-500 px-6 py-5 flex justify-between items-center">
        <h3 class="text-2xl font-bold text-white">
            <i class="fas fa-edit mr-2"></i> Editar Rol: {{ $rol->name }}
        </h3>
        <a href="{{ route('admin.roles.index') }}" class="bg-white text-yellow-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-yellow-50">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.roles.update', $rol->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre del Rol:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $rol->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                @error('name')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Permisos Asignados:</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 border rounded-lg p-4 bg-gray-50">
                    @foreach ($permisos as $permiso)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permiso->name }}"
                                class="form-checkbox h-5 w-5 text-yellow-600"
                                {{ in_array($permiso->id, $rolPermisos) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-800 text-sm">{{ $permiso->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-600 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-yellow-700">
                    <i class="fas fa-save mr-2"></i> Actualizar Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
