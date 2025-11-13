@extends('layouts.app')

@section('title', 'Detalles de Usuario')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F2E3D5] to-[#3CA6A6] rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg mr-4">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-[#F2E3D5]">
                            <i class="fas fa-user-circle mr-3"></i>
                            Detalles de Usuario: {{ $user->name }}
                        </h3>
                        <p class="mt-2 text-deep-teal-200 text-sm">
                            Información completa y detallada del usuario
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white border border-transparent rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    
                    <!-- ✅ NUEVO: Botón eliminar en vista de detalles -->
                    @can('admin')
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('¿Está seguro de eliminar este usuario? Se eliminarán también los datos relacionados (docente, etc.). Esta acción no se puede deshacer.')"
                                class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white border border-transparent rounded-xl font-semibold text-xs uppercase tracking-widest transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información Personal -->
                    <div class="bg-white rounded-2xl p-6 border border-deep-teal-100 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md mr-4">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h4 class="text-xl font-bold text-deep-teal-800">
                                Información Personal
                            </h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600 mb-2">
                                        <i class="fas fa-user mr-2 text-[#3CA6A6]"></i>
                                        Nombre Completo
                                    </label>
                                    <div class="bg-deep-teal-25 border border-deep-teal-200 rounded-xl p-4">
                                        <p class="text-lg font-bold text-deep-teal-800">{{ $user->name }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600 mb-2">
                                        <i class="fas fa-envelope mr-2 text-[#3CA6A6]"></i>
                                        Correo Electrónico
                                    </label>
                                    <div class="bg-deep-teal-25 border border-deep-teal-200 rounded-xl p-4">
                                        <p class="text-lg font-bold text-deep-teal-800">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600 mb-2">
                                        <i class="fas fa-fingerprint mr-2 text-[#3CA6A6]"></i>
                                        ID de Usuario
                                    </label>
                                    <div class="bg-deep-teal-25 border border-deep-teal-200 rounded-xl p-4">
                                        <p class="text-lg font-mono font-bold text-deep-teal-800">#{{ $user->id }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600 mb-2">
                                        <i class="fas fa-calendar-alt mr-2 text-[#3CA6A6]"></i>
                                        Fecha de Registro
                                    </label>
                                    <div class="bg-deep-teal-25 border border-deep-teal-200 rounded-xl p-4">
                                        <p class="text-lg font-bold text-deep-teal-800">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roles y Permisos -->
                    <div class="bg-white rounded-2xl p-6 border border-deep-teal-100 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#6f42c1] to-[#8B5FBF] rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md mr-4">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="text-xl font-bold text-deep-teal-800">
                                Roles y Permisos
                            </h4>
                        </div>
                        
                        @if($user->roles->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($user->roles as $role)
                                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4 text-center group hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="w-16 h-16 bg-gradient-to-br from-[#6f42c1] to-[#8B5FBF] rounded-full flex items-center justify-center text-white text-xl font-bold shadow-md mx-auto mb-3">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <h5 class="text-lg font-bold text-purple-800 mb-2">{{ $role->name }}</h5>
                                    <p class="text-sm text-purple-600">Rol asignado</p>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center text-gray-400 text-2xl mx-auto mb-4">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h5 class="text-lg font-bold text-gray-600 mb-2">Sin Roles Asignados</h5>
                                <p class="text-gray-500">No se han asignado roles a este usuario.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Columna Lateral -->
                <div class="space-y-6">
                    <!-- Estado de la Cuenta -->
                    <div class="bg-white rounded-2xl p-6 border border-deep-teal-100 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md mr-4">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="text-xl font-bold text-deep-teal-800">
                                Estado de la Cuenta
                            </h4>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block text-sm font-medium text-green-800 mb-1">
                                            Verificación de Email
                                        </label>
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full border border-green-200">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Verificado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full border border-yellow-200">
                                                <i class="fas fa-clock mr-2"></i>
                                                Pendiente
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-green-600 text-2xl">
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle"></i>
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block text-sm font-medium text-blue-800 mb-1">
                                            Contraseña Establecida
                                        </label>
                                        @if($user->password_set)
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full border border-green-200">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Sí
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm font-bold rounded-full border border-red-200">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                No
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-blue-600 text-2xl">
                                        @if($user->password_set)
                                            <i class="fas fa-lock"></i>
                                        @else
                                            <i class="fas fa-lock-open"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block text-sm font-medium text-purple-800 mb-1">
                                            Estado General
                                        </label>
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full border border-green-200">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 text-sm font-bold rounded-full border border-gray-200">
                                                <i class="fas fa-pause-circle mr-2"></i>
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-purple-600 text-2xl">
                                        @if($user->email_verified_at)
                                            <i class="fas fa-user-check"></i>
                                        @else
                                            <i class="fas fa-user-clock"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-2xl p-6 border border-deep-teal-100 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md mr-4">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h4 class="text-xl font-bold text-deep-teal-800">
                                Acciones Rápidas
                            </h4>
                        </div>
                        
                        <div class="space-y-3">
                            <!-- Botón Editar -->
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                                <i class="fas fa-edit mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                Editar Usuario
                            </a>
                            
                            <!-- Botón Ver Todos -->
                            <a href="{{ route('admin.users.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                                <i class="fas fa-users mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                Ver Todos los Usuarios
                            </a>
                            
                            <!-- ✅ NUEVO: Botón Eliminar en Acciones Rápidas -->
                            @can('admin')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Está seguro de eliminar este usuario? Se eliminarán también los datos relacionados (docente, etc.). Esta acción no se puede deshacer.')"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                                    <i class="fas fa-trash mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                    Eliminar Usuario
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="bg-white rounded-2xl p-6 border border-deep-teal-100 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md mr-4">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h4 class="text-xl font-bold text-deep-teal-800">
                                Información Adicional
                            </h4>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-deep-teal-100">
                                <span class="text-deep-teal-600 font-medium">Última Actualización</span>
                                <span class="text-deep-teal-800 font-bold">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($user->email_verified_at)
                            <div class="flex justify-between items-center py-2 border-b border-deep-teal-100">
                                <span class="text-deep-teal-600 font-medium">Email Verificado</span>
                                <span class="text-deep-teal-800 font-bold">{{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center py-2">
                                <span class="text-deep-teal-600 font-medium">Roles Asignados</span>
                                <span class="text-deep-teal-800 font-bold">{{ $user->roles->count() }}</span>
                            </div>
                            
                            <!-- ✅ NUEVO: Información de Docente Relacionado -->
                            @if($user->docente)
                            <div class="flex justify-between items-center py-2 border-t border-deep-teal-100 mt-2 pt-2">
                                <span class="text-deep-teal-600 font-medium">Docente Relacionado</span>
                                <span class="text-deep-teal-800 font-bold">{{ $user->docente->codigo }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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

.border-deep-teal-100 {
    border-color: rgba(1, 46, 64, 0.1);
}

.border-deep-teal-200 {
    border-color: rgba(1, 46, 64, 0.2);
}

.text-deep-teal-200 {
    color: rgba(242, 227, 213, 0.8);
}

.text-deep-teal-600 {
    color: rgba(1, 46, 64, 0.7);
}

.text-deep-teal-800 {
    color: rgba(1, 46, 64, 0.9);
}

.bg-deep-teal-50 {
    background-color: rgba(1, 46, 64, 0.05);
}
</style>
@endsection