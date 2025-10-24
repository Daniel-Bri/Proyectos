<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión Docente</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar {
            background: linear-gradient(180deg, #012E40 0%, #024959 100%);
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
        
        .rotate-90 {
            transform: rotate(90deg);
        }
        
        /* Mejor alineación para paquetes */
        .package-toggle {
            align-items: center;
        }
        
        .package-content {
            display: flex;
            align-items: center;
            flex: 1;
        }
        
        /* Animación para nuevo elemento de bitácora */
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(60, 166, 166, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(60, 166, 166, 0); }
            100% { box-shadow: 0 0 0 0 rgba(60, 166, 166, 0); }
        }
        
        .bitacora-glow {
            animation: pulse-glow 2s infinite;
        }
    </style>
</head>
<body class="flex">
    <!-- Sidebar -->
    <div class="sidebar w-64 min-h-screen text-white p-4 hidden lg:block flex flex-col">
        <div class="flex-1">
            <div class="text-center mb-8 pt-4">
                <div class="w-16 h-16 bg-light-teal rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold">Sistema Docente</h1>
                <p class="text-light-teal text-sm">FICCT - UAGRM</p>
            </div>

            <nav class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="nav-item active flex items-center px-4 py-3 text-white">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>

                <!-- Paquete: Administración -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="package-content">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                            <span class="truncate">Administración</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="submenu ml-6">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Gestión de Usuarios</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Roles y Permisos</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Importar Usuarios</a>
                        <a href="{{ route('admin.bitacora.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Bitácora del Sistema
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Configuración</a>
                    </div>
                </div>

                <!-- Paquete: Gestión Académica -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="package-content">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l9-5-9-5-9 5 9 5zm0 0l9-5-9-5-9 5 9 5zm0 0l9-5-9-5-9 5 9 5z"></path>
                            </svg>
                            <span class="truncate">Gestión Académica</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="submenu ml-6">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Docentes</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Materias</a>
                        <a href="{{ route('admin.aulas.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate flex items-center">
                            <i class="fas fa-door-open mr-2 w-4 h-4"></i>
                            Aulas
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Grupos</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Carreras</a>
                    </div>
                </div>

                <!-- Paquete: Gestión de Horarios -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="package-content">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="truncate">Gestión de Horarios</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="submenu ml-6">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Asignación Manual</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Asignación Automática</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Visualizar Horarios</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Disponibilidad Aulas</a>
                    </div>
                </div>

                <!-- Paquete: Control de Asistencia -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="package-content">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="truncate">Asistencia</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="submenu ml-6">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Registro Digital</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Registro QR</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Ver Asistencias</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Justificaciones</a>
                    </div>
                </div>

                <!-- Paquete: Reportes y Analíticas -->
                <div class="package-group">
                    <button class="nav-item flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:text-white package-toggle">
                        <div class="package-content">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="truncate">Reportes y Analíticas</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="submenu ml-6">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Reporte Horarios</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Reporte Asistencias</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Estadísticas</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-light-teal hover:bg-opacity-20 rounded truncate">Dashboard Avanzado</a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Sección de Cerrar Sesión en la parte inferior -->
        <div class="mt-auto pt-4 border-t border-light-teal border-opacity-20">
            <!-- Acceso rápido a Bitácora -->
            @can('ver-bitacora')
            <a href="{{ route('bitacora.index') }}" class="nav-item flex items-center w-full px-4 py-3 text-light-teal hover:text-white hover:bg-light-teal hover:bg-opacity-20 transition-colors mb-2 bitacora-glow rounded-lg">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="truncate">Ver Bitácora</span>
                <span class="ml-auto bg-light-teal text-deep-teal text-xs px-2 py-1 rounded-full font-bold">New</span>
            </a>
            @endcan

            <!-- Información del usuario -->
            <div class="flex items-center px-4 py-2 text-gray-300">
                <svg class="w-5 h-5 mr-3 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="text-xs text-light-teal truncate">{{ auth()->user()->email ?? 'email@ejemplo.com' }}</p>
                </div>
            </div>

            <!-- Cerrar Sesión -->
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="nav-item flex items-center w-full px-4 py-3 text-gray-300 hover:text-white hover:bg-red-600 hover:bg-opacity-20 transition-colors">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="truncate">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 lg:p-8">
        <!-- Header Mobile -->
        <div class="lg:hidden bg-deep-teal text-white p-4 rounded-lg mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <h1 class="text-xl font-bold">Sistema Docente</h1>
                        <p class="text-light-teal text-sm">Dashboard</p>
                    </div>
                </div>
                <button id="menuToggle" class="text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobileMenu" class="lg:hidden hidden bg-white shadow-lg rounded-lg mb-6 p-4">
            <nav class="space-y-2">
                <a href="/dashboard" class="block px-4 py-2 bg-light-teal text-white rounded">Dashboard</a>
                <div class="border-t pt-2">
                    <p class="px-4 py-1 text-sm font-semibold text-deep-teal">Administración</p>
                    <a href="#" class="block px-6 py-1 text-sm text-deep-teal hover:bg-cream rounded">Usuarios</a>
                    <a href="#" class="block px-6 py-1 text-sm text-deep-teal hover:bg-cream rounded">Roles</a>
                    @can('ver-bitacora')
                    <a href="{{ route('bitacora.index') }}" class="block px-6 py-1 text-sm text-deep-teal hover:bg-cream rounded flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Bitácora
                    </a>
                    @endcan
                </div>
                <div class="border-t pt-2">
                    <p class="px-4 py-1 text-sm font-semibold text-deep-teal">Gestión Académica</p>
                    <a href="#" class="block px-6 py-1 text-sm text-deep-teal hover:bg-cream rounded">Docentes</a>
                    <a href="#" class="block px-6 py-1 text-sm text-deep-teal hover:bg-cream rounded">Materias</a>
                </div>
                <!-- Cerrar Sesión Móvil -->
                <div class="border-t pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- El resto de tu contenido del dashboard se mantiene igual -->
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-deep-teal">Dashboard</h1>
            <p class="text-dark-teal mt-2">Resumen general del sistema de gestión docente</p>
        </div>

        <!-- ... resto del contenido de tu dashboard ... -->

    </div>

    <script>
        // Toggle mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        // Cerrar menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const toggle = document.getElementById('menuToggle');
            
            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Toggle package submenus
        document.querySelectorAll('.package-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const submenu = this.nextElementSibling;
                const icon = this.querySelector('svg:last-child');
                
                submenu.classList.toggle('open');
                icon.classList.toggle('rotate-90');
            });
        });

        // Confirmación para cerrar sesión
        document.getElementById('logoutForm').addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                e.preventDefault();
            }
        });

        // Remover animación de glow después de 5 segundos
        setTimeout(() => {
            const bitacoraGlow = document.querySelector('.bitacora-glow');
            if (bitacoraGlow) {
                bitacoraGlow.style.animation = 'none';
            }
        }, 5000);
    </script>
</body>
</html>