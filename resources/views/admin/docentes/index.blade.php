@extends('layouts.app')

@section('title', 'Gestión de Docentes')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    Lista de Docentes
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Gestión completa del personal docente del sistema
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                @can('admin')
                <a href="{{ route('docentes.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Docente
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($docentes->count() > 0)
            <!-- Mobile Cards -->
            <div class="block sm:hidden space-y-4">
                @foreach($docentes as $docente)
                <div class="bg-white border border-deep-teal-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ substr($docente->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-deep-teal-800 text-sm">{{ $docente->user->name }}</p>
                                    <p class="text-xs text-deep-teal-600">{{ $docente->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#3CA6A6] bg-opacity-20 text-[#026773] border border-[#3CA6A6] border-opacity-30 shadow-sm">
                            {{ $docente->codigo }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Teléfono</p>
                            <p class="font-bold text-deep-teal-800">{{ $docente->telefono }}</p>
                        </div>
                        <div>
                            <p class="text-deep-teal-600 text-xs font-medium">Sueldo</p>
                            <p class="font-bold text-green-600">${{ number_format($docente->sueldo, 2) }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-deep-teal-600 text-xs font-medium mb-2">Carreras</p>
                        <div class="flex flex-wrap gap-1">
                            @if($docente->carreras->count() > 0)
                                @foreach($docente->carreras->take(2) as $carrera)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full border border-blue-200">
                                        {{ $carrera->nombre }}
                                    </span>
                                @endforeach
                                @if($docente->carreras->count() > 2)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full border border-gray-200">
                                        +{{ $docente->carreras->count() - 2 }}
                                    </span>
                                @endif
                            @else
                                <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs rounded-full border border-orange-200">
                                    Sin carreras😊
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-deep-teal-100">
                        <span class="text-xs text-deep-teal-500 font-medium">
                            <i class="far fa-calendar mr-1"></i>
                          
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('docentes.show', $docente->codigo) }}" 
                               class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-1"></i>
                            </a>
                            @can('admin')
                            <a href="{{ route('docentes.edit', $docente->codigo) }}" 
                               class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                            <form action="{{ route('docentes.destroy', $docente->codigo) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Estás seguro de eliminar este docente?')"
                                        class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-2xl border border-deep-teal-100 shadow-lg">
                <table class="min-w-full divide-y divide-deep-teal-100">
                    <thead class="bg-gradient-to-r from-[#012E40] to-[#024959]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Código</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Sueldo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Carreras</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#F2E3D5] uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-deep-teal-50">
                        @foreach($docentes as $docente)
                        <tr class="hover:bg-deep-teal-25 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 bg-[#3CA6A6] bg-opacity-20 text-[#026773] text-sm font-bold rounded-full border border-[#3CA6A6] border-opacity-30">
                                    {{ $docente->codigo }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white font-bold text-sm mr-3 shadow-md">
                                        {{ substr($docente->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-bold text-deep-teal-800">
                                        {{ $docente->user->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-deep-teal-700">
                                {{ $docente->user->email }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-deep-teal-800">
                                {{ $docente->telefono }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-green-600">
                                ${{ number_format($docente->sueldo, 2) }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @if($docente->carreras->count() > 0)
                                        @foreach($docente->carreras->take(3) as $carrera)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full border border-blue-200">
                                                {{ $carrera->nombre }}
                                            </span>
                                        @endforeach
                                        @if($docente->carreras->count() > 3)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full border border-gray-200">
                                                +{{ $docente->carreras->count() - 3 }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs rounded-full border border-orange-200">
                                            Sin carreras😊
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('docentes.show', $docente->codigo) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-[#3CA6A6] hover:bg-[#026773] text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    @can('admin')
                                    <a href="{{ route('docentes.edit', $docente->codigo) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </a>
                                    <form action="{{ route('docentes.destroy', $docente->codigo) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Estás seguro de eliminar este docente?')"
                                                class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                            <i class="fas fa-trash mr-1"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                <div class="bg-white px-6 py-4 rounded-2xl border border-deep-teal-100 shadow-lg">
                    {{ $docentes->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-chalkboard-teacher text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">No hay docentes registrados</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    Comienza agregando el primer docente al sistema.
                </p>
                @can('admin')
                <a href="{{ route('docentes.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Registrar Primer Docente
                </a>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection