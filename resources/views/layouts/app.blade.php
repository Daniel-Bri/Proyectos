<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema FICCT')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuración de colores personalizados -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'deep-teal': {
                            50: '#f0f7f9',
                            100: '#e1eff2',
                            200: '#b8dfe6',
                            300: '#8fced9',
                            400: '#3CA6A6',
                            500: '#026773',
                            600: '#024959',
                            700: '#012E40',
                            800: '#012535',
                            900: '#001c29',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        .gradient-bg {
            background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/dashboard') }}" class="text-xl font-bold text-[#F2E3D5] hover:text-white transition-colors duration-200">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Sistema FICCT
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-sm text-[#F2E3D5]">{{ auth()->user()->name }}</span>
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm focus:outline-none">
                                <div class="h-8 w-8 bg-[#3CA6A6] rounded-full flex items-center justify-center text-white font-semibold hover:bg-[#026773] transition-colors duration-200">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 border border-gray-200">
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-deep-teal-50 hover:text-deep-teal-700 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-[#F2E3D5] hover:text-white text-sm transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-[#3CA6A6] text-white px-6 py-3 rounded-lg shadow-lg border border-[#026773]">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-[#024959] text-[#F2E3D5] px-6 py-3 rounded-lg shadow-lg border border-[#012E40]">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Alpine.js para dropdowns -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>