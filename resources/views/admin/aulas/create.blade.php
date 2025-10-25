@extends('layouts.app')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Crear Nuevo Rol
                    </h3>
                    <p class="mt-1 text-blue-100 text-sm">
                        Define un nuevo rol y asigna sus permisos
                    </p>
                </div>
                <a href="{{ route('admin.roles.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Información Básica -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Información del Rol</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre del Rol <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                       placeholder="Ej: asistente, supervisor, etc." required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Permisos -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Asignar Permisos</h4>
                        <p class="text-sm text-gray-600 mb-4">Selecciona los permisos que tendrá este rol:</p>

                        <div class="space-y-4 max-h-96 overflow-y-auto p-2">
                            @foreach($permisos as $grupo => $permisosGrupo)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-white px-4 py-3 border-b border-gray-200">
                                    <h5 class="font-semibold text-gray-800 capitalize">
                                        <i class="fas fa-folder mr-2 text-blue-500"></i>
                                        {{ $grupo }}
                                    </h5>
                                </div>
                                <div class="bg-gray-50 p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($permisosGrupo as $permiso)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}" 
                                               id="permiso-{{ $permiso->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="permiso-{{ $permiso->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $permiso->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.roles.index') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Crear Rol
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection