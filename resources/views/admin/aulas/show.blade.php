@extends('layouts.app')

@section('title', 'Detalles del Rol: ' . $role->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-4 py-5 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">
                        <i class="fas fa-user-shield mr-3"></i>
                        Detalles del Rol
                    </h3>
                    <p class="mt-1 text-blue-100 text-sm">
                        Información completa del rol {{ $role->name }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    @can('editar-roles')
                    <a href="{{ route('admin.roles.edit', $role) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-400 rounded-lg font-semibold text-xs uppercase tracking-widest transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Básica -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información Básica
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-blue-700">Nombre del Rol</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $role->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-700">Guard Name</dt>
                            <dd class="mt-1 text-sm text-gray-600">{{ $role->guard_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-700">Fecha de Creación</dt>
                            <dd class="mt-1 text-sm text-gray-600">{{ $role->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Estadísticas -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <h4 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Estadísticas
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <div class="text-2xl font-bold text-green-600">{{ $role->users_count }}</div>
                                <div class="text-sm text-green-700 font-medium">Usuarios</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <div class="text-2xl font-bold text-green-600">{{ $role->permissions->count() }}</div>
                                <div class="text-sm text-green-700 font-medium">Permisos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Permisos -->
            <div class="mt-6 bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-key mr-2"></i>
                    Permisos Asignados ({{ $role->permissions->count() }})
                </h4>
                
                @if($role->permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($role->permissions as $permiso)
                    <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $permiso->name }}</span>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Permiso</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-key text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">Este rol no tiene permisos asignados.</p>
                </div>
                @endif
            </div>

            <!-- Usuarios con este Rol -->
            @if($role->users->count() > 0)
            <div class="mt-6 bg-purple-50 rounded-lg p-6 border border-purple-200">
                <h4 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    Usuarios con este Rol ({{ $role->users->count() }})
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($role->users as $usuario)
                    <div class="bg-white border border-purple-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                {{ substr($usuario->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $usuario->name }}</div>
                                <div class="text-sm text-gray-600">{{ $usuario->email }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection