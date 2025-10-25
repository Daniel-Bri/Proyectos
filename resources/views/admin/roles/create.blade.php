@extends('layouts.app')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-500 px-6 py-5">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Crear Nuevo Rol
                </h2>
                <a href="{{ route('admin.roles.index') }}" 
                   class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold shadow hover:bg-green-50">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                        Nombre del Rol: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Ejemplo: coordinador, asistente, supervisor">
                    @error('name')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Permisos:</label>
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 max-h-96 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($permisos as $permiso)
                                <label class="inline-flex items-center p-2 hover:bg-white rounded transition duration-150">
                                    <input type="checkbox" name="permissions[]" value="{{ $permiso->name }}" 
                                           class="form-checkbox h-5 w-5 text-green-600 rounded">
                                    <span class="ml-3 text-gray-700 text-sm">{{ $permiso->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('permissions')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-semibold">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection