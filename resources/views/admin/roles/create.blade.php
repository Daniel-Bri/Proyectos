@extends('layouts.app')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-6 py-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Crear Nuevo Rol
                </h2>
                <a href="{{ route('admin.roles.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#F2E3D5] hover:bg-white text-[#012E40] border border-transparent rounded-xl font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-deep-teal-700 font-semibold mb-2">
                        Nombre del Rol: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="w-full border border-deep-teal-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                        placeholder="Ejemplo: coordinador, asistente, supervisor">
                    @error('name')
                        <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-deep-teal-700 font-semibold mb-2">Permisos:</label>
                    <div class="border border-deep-teal-200 rounded-xl p-4 bg-deep-teal-25 max-h-96 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($permisos as $permiso)
                                <label class="inline-flex items-center p-3 hover:bg-white rounded-xl transition-all duration-150 border border-transparent hover:border-deep-teal-200">
                                    <input type="checkbox" name="permissions[]" value="{{ $permiso->name }}" 
                                           class="form-checkbox h-5 w-5 text-[#3CA6A6] rounded transition-all duration-200">
                                    <span class="ml-3 text-deep-teal-700 text-sm font-medium">{{ $permiso->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('permissions')
                        <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4 border-t border-deep-teal-200">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-deep-teal-300 text-deep-teal-700 rounded-xl hover:bg-deep-teal-50 transition-all duration-200 font-semibold">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.gradient-bg {
    background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
}

.bg-deep-teal-25 {
    background-color: rgba(1, 46, 64, 0.025);
}

.border-deep-teal-200 {
    border-color: rgba(1, 46, 64, 0.2);
}

.text-deep-teal-200 {
    color: rgba(242, 227, 213, 0.8);
}

.text-deep-teal-700 {
    color: rgba(1, 46, 64, 0.8);
}

.bg-deep-teal-50 {
    background-color: rgba(1, 46, 64, 0.05);
}
</style>
@endsection