@extends('layouts.app')

@section('title', 'Mi Perfil - Docente')

@section('content')
<div class="bg-white shadow-xl rounded-2xl border border-deep-teal-200 overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg px-4 py-5 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex-1">
                <!-- Sistema FICCT en la parte superior -->
                <div class="mb-3">
                    <span class="inline-flex items-center px-3 py-1 bg-[#F2E3D5] bg-opacity-20 text-[#F2E3D5] text-sm font-semibold rounded-full border border-[#F2E3D5] border-opacity-30">
                        <i class="fas fa-university mr-2"></i>
                        Sistema FICCT
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-[#F2E3D5]">
                    <i class="fas fa-user-circle mr-3"></i>
                    Mi Perfil Docente
                </h3>
                <p class="mt-2 text-deep-teal-200 text-sm">
                    Información personal y académica
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('docente.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#3CA6A6] hover:bg-[#026773] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-25 to-deep-teal-25">
        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <p class="text-rose-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-amber-800 font-medium">Por favor corrige los siguientes errores:</p>
                        <ul class="text-amber-700 text-sm mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($docente)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna izquierda - Información personal -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-deep-teal-100 p-6">
                        <!-- Foto de perfil -->
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-[#026773] to-[#024959] rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg mb-4">
                                @if($docente->user && $docente->user->name)
                                    {{ strtoupper(substr($docente->user->name, 0, 1)) }}
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold text-deep-teal-800">
                                {{ $docente->user->name ?? 'Nombre no disponible' }}
                            </h2>
                            <p class="text-deep-teal-600 text-sm">
                                {{ $docente->user->email ?? 'Email no disponible' }}
                            </p>
                        </div>

                        <!-- Información de contacto -->
                        <div class="space-y-4">
                            <div class="flex items-center text-sm text-deep-teal-700">
                                <i class="fas fa-id-card mr-3 text-[#3CA6A6] w-5"></i>
                                <span class="font-medium">Código:</span>
                                <span class="ml-2 font-bold">{{ $docente->codigo ?? 'N/A' }}</span>
                            </div>
                            
                            @if($docente->telefono)
                            <div class="flex items-center text-sm text-deep-teal-700">
                                <i class="fas fa-phone mr-3 text-[#3CA6A6] w-5"></i>
                                <span class="font-medium">Teléfono:</span>
                                <span class="ml-2">{{ $docente->telefono }}</span>
                            </div>
                            @endif

                            @if($docente->direccion)
                            <div class="flex items-start text-sm text-deep-teal-700">
                                <i class="fas fa-map-marker-alt mr-3 text-[#3CA6A6] w-5 mt-0.5"></i>
                                <div>
                                    <span class="font-medium">Dirección:</span>
                                    <p class="mt-1 text-deep-teal-600">{{ $docente->direccion }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Botón para cambiar contraseña -->
                        <div class="mt-6 pt-6 border-t border-deep-teal-100">
                            <button type="button" 
                                    id="changePasswordBtn"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-key mr-2"></i>
                                Cambiar Contraseña
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha - Información académica -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Información académica -->
                        <div class="bg-white rounded-2xl shadow-lg border border-deep-teal-100 p-6">
                            <h3 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                                <i class="fas fa-graduation-cap mr-2 text-[#3CA6A6]"></i>
                                Información Académica
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600">Especialidad</label>
                                    <p class="mt-1 text-sm text-deep-teal-800 font-semibold">
                                        {{ $docente->especialidad ?? 'No especificada' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600">Título Profesional</label>
                                    <p class="mt-1 text-sm text-deep-teal-800 font-semibold">
                                        {{ $docente->titulo ?? 'No especificado' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600">Años de Experiencia</label>
                                    <p class="mt-1 text-sm text-deep-teal-800 font-semibold">
                                        {{ $docente->experiencia ?? 'No especificada' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-deep-teal-600">Estado</label>
                                    <span class="mt-1 inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ ($docente->estado ?? 'activo') === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($docente->estado ?? 'activo') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Carreras asignadas -->
                        @if($docente->carreras && $docente->carreras->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg border border-deep-teal-100 p-6">
                            <h3 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                                <i class="fas fa-bookmark mr-2 text-[#3CA6A6]"></i>
                                Carreras Asignadas
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($docente->carreras as $carrera)
                                <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <i class="fas fa-book text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-blue-800 text-sm">{{ $carrera->nombre }}</p>
                                        <p class="text-blue-600 text-xs">{{ $carrera->codigo ?? '' }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Estadísticas rápidas -->
                        <div class="bg-white rounded-2xl shadow-lg border border-deep-teal-100 p-6">
                            <h3 class="text-lg font-bold text-deep-teal-800 mb-4 flex items-center">
                                <i class="fas fa-chart-bar mr-2 text-[#3CA6A6]"></i>
                                Resumen Académico
                            </h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center p-4 bg-green-50 rounded-xl border border-green-100">
                                    <div class="w-10 h-10 mx-auto bg-green-500 rounded-full flex items-center justify-center text-white mb-2">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <p class="text-2xl font-bold text-green-800">0</p>
                                    <p class="text-green-600 text-xs font-medium">Materias</p>
                                </div>
                                
                                <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                                    <div class="w-10 h-10 mx-auto bg-blue-500 rounded-full flex items-center justify-center text-white mb-2">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <p class="text-2xl font-bold text-blue-800">0</p>
                                    <p class="text-blue-600 text-xs font-medium">Grupos</p>
                                </div>
                                
                                <div class="text-center p-4 bg-purple-50 rounded-xl border border-purple-100">
                                    <div class="w-10 h-10 mx-auto bg-purple-500 rounded-full flex items-center justify-center text-white mb-2">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <p class="text-2xl font-bold text-purple-800">0</p>
                                    <p class="text-purple-600 text-xs font-medium">Horarios</p>
                                </div>
                                
                                <div class="text-center p-4 bg-amber-50 rounded-xl border border-amber-100">
                                    <div class="w-10 h-10 mx-auto bg-amber-500 rounded-full flex items-center justify-center text-white mb-2">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <p class="text-2xl font-bold text-amber-800">{{ date('Y') }}</p>
                                    <p class="text-amber-600 text-xs font-medium">Año Actual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-deep-teal-100 to-deep-teal-200 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-slash text-deep-teal-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-deep-teal-700 mb-3">Información no disponible</h3>
                <p class="text-deep-teal-600 max-w-md mx-auto text-lg mb-6">
                    No se pudo cargar la información de tu perfil docente.
                </p>
                <a href="{{ route('docente.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Dashboard
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal para cambiar contraseña -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="gradient-bg px-6 py-4 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-[#F2E3D5]">
                    <i class="fas fa-key mr-2"></i>
                    Cambiar Contraseña
                </h3>
                <button type="button" 
                        id="closeModalBtn"
                        class="text-[#F2E3D5] hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="passwordForm" action="{{ route('docente.cambiar-password') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-deep-teal-700 mb-2">
                        Contraseña Actual
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="current_password" 
                               id="current_password"
                               required
                               class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all">
                        <button type="button" 
                                class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-deep-teal-400 hover:text-deep-teal-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label for="new_password" class="block text-sm font-medium text-deep-teal-700 mb-2">
                        Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               required
                               minlength="8"
                               class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all">
                        <button type="button" 
                                class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-deep-teal-400 hover:text-deep-teal-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-deep-teal-500 mt-1">Mínimo 8 caracteres</p>
                </div>
                
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-deep-teal-700 mb-2">
                        Confirmar Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation"
                               required
                               class="w-full px-4 py-3 border border-deep-teal-200 rounded-xl focus:ring-2 focus:ring-[#3CA6A6] focus:border-transparent transition-all">
                        <button type="button" 
                                class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-deep-teal-400 hover:text-deep-teal-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button"
                        id="cancelBtn"
                        class="flex-1 px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#3CA6A6] hover:bg-[#026773] text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing password modal');
    
    const modal = document.getElementById('passwordModal');
    const openButton = document.getElementById('changePasswordBtn');
    const closeButton = document.getElementById('closeModalBtn');
    const cancelButton = document.getElementById('cancelBtn');
    
    // Función para abrir modal
    function openModal() {
        console.log('Opening modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.getElementById('passwordForm').reset();
        }
    }
    
    // Función para cerrar modal
    function closeModal() {
        console.log('Closing modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    // Event listeners
    if (openButton) {
        openButton.addEventListener('click', openModal);
        console.log('Open button event listener added');
    }
    
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }
    
    if (cancelButton) {
        cancelButton.addEventListener('click', closeModal);
    }
    
    // Cerrar modal al hacer click fuera
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
    
    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // Form validation
    const form = document.getElementById('passwordForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('❌ Las contraseñas no coinciden');
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('❌ La contraseña debe tener al menos 8 caracteres');
                return false;
            }
            
            console.log('Form submitted successfully');
        });
    }
    
    console.log('Password modal initialization complete');
});
</script>
@endsection