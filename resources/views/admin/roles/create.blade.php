@extends('layouts.app')

@section('title', 'Crear Rol')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-700 to-blue-600 px-6 py-5 flex justify-between items-center">
        <h3 class="text-2xl font-bold text-white">
            <i class="fas fa-user-shield mr-2"></i> Crear Nuevo Rol
        </h3>
        <a href="{{ route('admin.roles.index') }}" class="bg-white text-indigo-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-indigo-50">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre del Rol:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ejemplo: coordinador, docente, admin">
                @error('name')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Asignar Permisos:</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 border rounded-lg p-4 bg-gray-50">
                    @foreach ($permisos as $permiso)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permiso->name }}" class="form-checkbox h-5 w-5 text-indigo-600">
                            <span class="ml-2 text-gray-800 text-sm">{{ $permiso->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i> Guardar Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
