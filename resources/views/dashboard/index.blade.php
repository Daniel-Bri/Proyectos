<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión Docente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'deep-teal': '#012E40',
                        'dark-teal': '#024959',
                        'medium-teal': '#026773',
                        'light-teal': '#3CA6A6',
                        'cream': '#F2E3D5',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F2E3D5 0%, #ffffff 100%);
            min-height: 100vh;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #012E40 0%, #024959 100%);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 50;
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        .overlay {
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .nav-item {
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .nav-item:hover {
            background: rgba(60, 166, 166, 0.2);
            transform: translateX(5px);
        }
        
        .nav-item.active {
            background: #3CA6A6;
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .submenu.open {
            max-height: 500px;
        }
        
        .sub-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .sub-submenu.open {
            max-height: 200px;
        }
        
        .rotate-90 {
            transform: rotate(90deg);
        }
        
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="flex">
    <!-- Overlay para móvil -->
    <div id="overlay" class="overlay fixed inset-0 lg:hidden hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed lg:relative w-64 min-h-screen text-white p-4 flex flex-col z-50">
        <div class="flex-1">
            <!-- Botón cerrar en móvil -->
            <div class="flex justify-between items-center mb-8 lg:hidden">
                <div class="text-center flex-1">
                    <div class="w-12 h-12 bg-light-teal rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <h1 class="text-lg font-bold">Sistema Docente</h1>
                    <p class="text-light-teal text-xs">FICCT - UAGRM</p>
                </div>
                <button id="closeSidebar" class="text-white ml-4">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Logo desktop -->
            <div class="text-center mb-8 pt-4 hidden lg:block">
                <div class="w-16 h-16 bg-light-teal rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold">Sistema Docente</h1>
                <p class="text-light-teal text-sm">FICCT - UAGRM</p>
            </div>

            <nav class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="nav-item active flex items-center px-4 py-3 text-white">
                    <i class="fas fa-home mr-3 w-5 h-5"></i>
                    <span class="truncate">Dashboard</span>
                </a>

                <!-- Paquete: Administración (SOLO para admin y coordinador) -->
                @auth
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))
                    <div class="package-group">
                        <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                            <div class="flex items-center flex-1">
                                <i class="fas fa-cog mr-3 w-5 h-5"></i>
                                <span class="truncate">Administración</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform"></i>
                        </button>
                        <div class="submenu ml-6">
                            @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.users.index') }}" 
                            class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-users mr-2 w-4 h-4"></i>
                                Gestión de Usuarios
                            </a>
                            <a href="{{ route('admin.roles.index') }}" 
                            class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-user-shield mr-2 w-4 h-4"></i>
                                Roles y Permisos
                            </a>
                            <a href="{{ route('admin.carga-masiva.usuarios.index') }}" 
                            class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-upload mr-2 w-4 h-4"></i>
                                Carga Masiva
                            </a>
                            @endif
                            <a href="{{ route('admin.bitacora.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-clipboard-list mr-2 w-4 h-4"></i>
                                Bitácora del Sistema
                            </a>
                        </div>
                    </div>
                    @endif
                @endauth

                <!-- Paquete: Gestión Académica -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="flex items-center flex-1">
                            <i class="fas fa-book-open mr-3 w-5 h-5"></i>
                            <span class="truncate">Gestión Académica</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div class="submenu ml-6">
                        <!-- Submenú Docentes -->
                        @auth
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))
                            <div class="submenu-item">
                                <button class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate docentes-toggle">
                                    <div class="flex items-center">
                                        <i class="fas fa-chalkboard-teacher mr-2 w-4 h-4"></i>
                                        Docentes
                                    </div>
                                    <i class="fas fa-chevron-right text-xs transition-transform"></i>
                                </button>
                                <div class="sub-submenu ml-4">
                                    <a href="{{ route('docentes.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                        <i class="fas fa-list mr-2 w-4 h-4"></i>
                                        Lista de Docentes
                                    </a>
                                    @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('docentes.create') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                        <i class="fas fa-user-plus mr-2 w-4 h-4"></i>
                                        Registrar Docente
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endauth
                        
                        <!-- Grupos -->
                        @auth
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))
                            <a href="{{ route('admin.grupos.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-users mr-2 w-4 h-4"></i>
                                Grupos
                            </a>
                            @endif
                        @endauth
                        
                        <!-- Materias -->
                        @auth
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.materias.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-book mr-2 w-4 h-4"></i>
                                Materias
                            </a>
                        @elseif(auth()->user()->hasRole('coordinador'))
                            <a href="{{ route('coordinador.materias.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-book mr-2 w-4 h-4"></i>
                                Materias
                            </a>
                        @elseif(auth()->user()->hasRole('docente'))
                            <a href="{{ route('docente.materias.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-book mr-2 w-4 h-4"></i>
                                Mis Materias
                            </a>
                        @endif
                        @endauth

                        <!-- Aulas (solo admin y coordinador) -->
                        @auth
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))
                            <a href="{{ route('admin.aulas.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-door-open mr-2 w-4 h-4"></i>
                                Aulas
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Paquete: Horarios -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="flex items-center flex-1">
                            <i class="fas fa-clock mr-3 w-5 h-5"></i>
                            <span class="truncate">Horarios</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div class="submenu ml-6">
                        @auth
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))
                            <a href="{{ route('coordinador.horarios.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-calendar-alt mr-2 w-4 h-4"></i>
                                Asignar Horarios
                            </a>
                            <a href="{{ route('coordinador.asignacion-automatica.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-robot mr-2 w-4 h-4"></i>
                                Asignación Automática
                            </a>
                            <a href="{{ route('coordinador.reportes.aulas.disponibles') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-chart-bar mr-2 w-4 h-4"></i>
                                Reportes de Aulas
                            </a>
                            @elseif(auth()->user()->hasRole('docente'))
                            <a href="{{ route('docente.horarios.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-calendar-alt mr-2 w-4 h-4"></i>
                                Mi Horario
                            </a>
                            <a href="{{ route('docente.mi-horario') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-table mr-2 w-4 h-4"></i>
                                Vista Semanal
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Paquete: Asistencia (solo para docentes) -->
                @auth
                    @if(auth()->user()->hasRole('docente'))
                    <div class="package-group">
                        <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                            <div class="flex items-center flex-1">
                                <i class="fas fa-clipboard-check mr-3 w-5 h-5"></i>
                                <span class="truncate">Asistencia</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform"></i>
                        </button>
                        <div class="submenu ml-6">
                            <a href="{{ route('docente.asistencia.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-edit mr-2 w-4 h-4"></i>
                                Registro Digital
                            </a>
                            <a href="{{ route('docente.asistencia.qr') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-qrcode mr-2 w-4 h-4"></i>
                                Código QR
                            </a>
                            <a href="{{ route('docente.asistencia.historial') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                <i class="fas fa-history mr-2 w-4 h-4"></i>
                                Historial
                            </a>
                        </div>
                    </div>
                    @endif
                @endauth

                <!-- Perfil y Cuenta -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="flex items-center flex-1">
                            <i class="fas fa-user mr-3 w-5 h-5"></i>
                            <span class="truncate">Mi Cuenta</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform"></i>
                    </button>
                    <div class="submenu ml-6">
                        <!-- Perfil según el rol del usuario -->
                        @auth
                            @if(auth()->user()->hasRole('docente'))
                                <a href="{{ route('docente.perfil') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                    <i class="fas fa-user-edit mr-2 w-4 h-4"></i>
                                    Mi Perfil
                                </a>
                            @else
                                <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                                    <i class="fas fa-user-edit mr-2 w-4 h-4"></i>
                                    Mi Perfil
                                </a>
                            @endif
                        @endauth
                        
                        <a href="{{ route('password.change') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                            <i class="fas fa-lock mr-2 w-4 h-4"></i>
                            Cambiar Contraseña
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Sección inferior -->
        <div class="mt-auto pt-4 border-t border-light-teal border-opacity-20">
            <!-- Usuario -->
            <div class="flex items-center px-4 py-3 text-gray-300">
                <div class="w-8 h-8 bg-light-teal rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="text-xs text-light-teal truncate">
                        @auth
                            {{ auth()->user()->getRoleNames()->first() ?? 'Usuario' }}
                        @else
                            Invitado
                        @endauth
                    </p>
                </div>
            </div>

            <!-- Cerrar Sesión -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="nav-item flex items-center w-full px-4 py-3 text-gray-300 hover:text-white hover:bg-red-600 hover:bg-opacity-20 transition-colors">
                    <i class="fas fa-sign-out-alt mr-3 w-5 h-5"></i>
                    <span class="truncate">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-h-screen">
        <!-- Header Mobile -->
        <header class="bg-deep-teal text-white p-4 lg:hidden sticky top-0 z-40 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button id="menuToggle" class="text-white mr-3">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold">Sistema Docente</h1>
                        <p class="text-light-teal text-xs">
                            @auth
                                Dashboard - {{ auth()->user()->getRoleNames()->first() ?? 'Usuario' }}
                            @else
                                Dashboard
                            @endauth
                        </p>
                    </div>
                </div>
                <div class="w-8 h-8 bg-light-teal rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
            </div>
        </header>

        <!-- Contenido Principal -->
        <main class="p-4 lg:p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl lg:text-3xl font-bold text-deep-teal">Dashboard</h1>
                <p class="text-dark-teal mt-2 text-sm lg:text-base">
                    @auth
                        @if(auth()->user()->hasRole('docente'))
                            Bienvenido/a, {{ auth()->user()->name }} - Panel del Docente
                        @elseif(auth()->user()->hasRole('coordinador'))
                            Bienvenido/a, {{ auth()->user()->name }} - Panel del Coordinador
                        @else
                            Bienvenido/a, {{ auth()->user()->name }} - Panel de Administración
                        @endif
                    @else
                        Resumen general del sistema de gestión docente
                    @endauth
                </p>
            </div>

            <!-- Estadísticas Responsive -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Tarjeta 1 - Usuarios -->
                <div class="stat-card bg-white rounded-xl p-4 shadow-lg border-l-4 border-light-teal">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-light-teal bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-light-teal text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-deep-teal">
                                @if(class_exists('App\Models\User'))
                                    {{ \App\Models\User::count() }}
                                @else
                                    150
                                @endif
                            </p>
                            <p class="text-sm text-dark-teal">Total Usuarios</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2 - Aulas -->
                <div class="stat-card bg-white rounded-xl p-4 shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-door-open text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-deep-teal">
                                @if(class_exists('App\Models\Aula'))
                                    {{ \App\Models\Aula::count() }}
                                @else
                                    25
                                @endif
                            </p>
                            <p class="text-sm text-dark-teal">Aulas Registradas</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3 - Materias -->
                <div class="stat-card bg-white rounded-xl p-4 shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-deep-teal">
                                @if(class_exists('App\Models\Materia'))
                                    {{ \App\Models\Materia::count() }}
                                @else
                                    45
                                @endif
                            </p>
                            <p class="text-sm text-dark-teal">Materias</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 4 - Grupos -->
                <div class="stat-card bg-white rounded-xl p-4 shadow-lg border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-deep-teal">
                                @if(class_exists('App\Models\Grupo'))
                                    {{ \App\Models\Grupo::count() }}
                                @else
                                    18
                                @endif
                            </p>
                            <p class="text-sm text-dark-teal">Grupos Activos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas (Personalizadas por rol) -->
            <div class="bg-white rounded-xl p-6 shadow-lg mb-8">
                <h2 class="text-xl font-bold text-deep-teal mb-4">Acciones Rápidas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @auth
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))
                        <!-- Acciones para Admin/Coordinador -->
                        <a href="{{ route('admin.grupos.index') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p class="font-semibold">Gestionar Grupos</p>
                        </a>
                        <a href="{{ route('admin.aulas.index') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-door-open text-2xl mb-2"></i>
                            <p class="font-semibold">Gestionar Aulas</p>
                        </a>
                        <a href="{{ route('coordinador.horarios.index') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                            <p class="font-semibold">Asignar Horarios</p>
                        </a>
                        <a href="{{ route('coordinador.reportes.aulas.disponibles') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-chart-bar text-2xl mb-2"></i>
                            <p class="font-semibold">Reportes Aulas</p>
                        </a>
                        @elseif(auth()->user()->hasRole('docente'))
                        <!-- Acciones para Docente -->
                        <a href="{{ route('docente.materias.index') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-book text-2xl mb-2"></i>
                            <p class="font-semibold">Mis Materias</p>
                        </a>
                        <a href="{{ route('docente.horarios.index') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                            <p class="font-semibold">Mi Horario</p>
                        </a>
                        <a href="{{ route('docente.asistencia.index') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-clipboard-check text-2xl mb-2"></i>
                            <p class="font-semibold">Registrar Asistencia</p>
                        </a>
                        <a href="{{ route('docente.perfil') }}" class="bg-cream hover:bg-light-teal hover:text-white text-deep-teal rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-user text-2xl mb-2"></i>
                            <p class="font-semibold">Mi Perfil</p>
                        </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Información adicional según el rol -->
            @auth
                @if(auth()->user()->hasRole('docente'))
                <!-- Panel del Docente -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Próximas Clases -->
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold text-deep-teal mb-4 flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Próximas Clases Hoy
                        </h3>
                        <div class="space-y-3">
                            @php
                                $hoy = \Carbon\Carbon::now()->format('Y-m-d');
                                $hora_actual = \Carbon\Carbon::now()->format('H:i:s');
                            @endphp
                            @if(class_exists('App\Models\GrupoMateriaHorario'))
                                @php
                                    $proximas_clases = \App\Models\GrupoMateriaHorario::with(['grupoMateria.materia', 'horario', 'aula'])
                                        ->whereHas('horario', function($query) use ($hoy, $hora_actual) {
                                            $query->where('dia', strtoupper(\Carbon\Carbon::now()->isoFormat('ddd')))
                                                  ->where('hora_inicio', '>=', $hora_actual);
                                        })
                                        ->where('id_docente', auth()->user()->docente->codigo ?? null)
                                        ->orderBy('hora_inicio')
                                        ->take(3)
                                        ->get();
                                @endphp
                                @forelse($proximas_clases as $clase)
                                <div class="flex items-center justify-between p-3 bg-cream rounded-lg">
                                    <div>
                                        <p class="font-semibold text-deep-teal">{{ $clase->grupoMateria->materia->nombre ?? 'Materia' }}</p>
                                        <p class="text-sm text-dark-teal">{{ $clase->horario->hora_inicio ?? '' }} - {{ $clase->aula->nombre ?? 'Aula' }}</p>
                                    </div>
                                    <span class="bg-light-teal text-white px-2 py-1 rounded text-xs">
                                        {{ $clase->horario->hora_inicio ?? '' }}
                                    </span>
                                </div>
                                @empty
                                <p class="text-gray-500 text-center py-4">No hay clases programadas para hoy</p>
                                @endforelse
                            @else
                                <p class="text-gray-500 text-center py-4">Sistema de horarios no disponible</p>
                            @endif
                        </div>
                    </div>

                    <!-- Resumen de Asistencia -->
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold text-deep-teal mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Resumen de Asistencia
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-dark-teal">Asistencias este mes:</span>
                                <span class="font-bold text-deep-teal">12</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-dark-teal">Clases impartidas:</span>
                                <span class="font-bold text-deep-teal">15</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                            <p class="text-sm text-dark-teal text-center">80% de asistencia registrada</p>
                        </div>
                    </div>
                </div>
                @endif
            @endauth

            <!-- Sistema Status -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-deep-teal mb-4">Estado del Sistema</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 border rounded-lg">
                        <i class="fas fa-database text-2xl text-green-500 mb-2"></i>
                        <p class="font-semibold">Base de Datos</p>
                        <p class="text-sm text-green-500">Conectada</p>
                    </div>
                    <div class="text-center p-4 border rounded-lg">
                        <i class="fas fa-server text-2xl text-green-500 mb-2"></i>
                        <p class="font-semibold">Servidor</p>
                        <p class="text-sm text-green-500">Operativo</p>
                    </div>
                    <div class="text-center p-4 border rounded-lg">
                        <i class="fas fa-shield-alt text-2xl text-green-500 mb-2"></i>
                        <p class="font-semibold">Seguridad</p>
                        <p class="text-sm text-green-500">Activa</p>
                    </div>
                    <div class="text-center p-4 border rounded-lg">
                        <i class="fas fa-sync text-2xl text-blue-500 mb-2"></i>
                        <p class="font-semibold">Última Actualización</p>
                        <p class="text-sm text-blue-500">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle sidebar móvil
        document.getElementById('menuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.add('open');
            overlay.classList.remove('hidden');
        });

        // Cerrar sidebar
        document.getElementById('closeSidebar').addEventListener('click', closeSidebar);
        document.getElementById('overlay').addEventListener('click', closeSidebar);

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
        }

        // Toggle submenus
        document.querySelectorAll('.package-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const submenu = this.nextElementSibling;
                const icon = this.querySelector('.fa-chevron-down');
                
                submenu.classList.toggle('open');
                icon.classList.toggle('rotate-90');
            });
        });

        // Cerrar sidebar al hacer clic en un enlace (móvil)
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Toggle submenús de docentes
        document.querySelectorAll('.docentes-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const submenu = this.nextElementSibling;
                const icon = this.querySelector('.fa-chevron-right');
                
                submenu.classList.toggle('open');
                icon.classList.toggle('rotate-90');
            });
        });

        // Cerrar submenús al hacer clic fuera
        document.addEventListener('click', function() {
            document.querySelectorAll('.sub-submenu').forEach(submenu => {
                submenu.classList.remove('open');
            });
            document.querySelectorAll('.docentes-toggle .fa-chevron-right').forEach(icon => {
                icon.classList.remove('rotate-90');
            });
        });

        // Prevenir que los clics en los submenús cierren el menú principal
        document.querySelectorAll('.submenu, .sub-submenu').forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>