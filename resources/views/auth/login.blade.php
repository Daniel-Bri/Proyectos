<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Gestión</title>
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
        }
        
        .login-container {
            background: linear-gradient(135deg, var(--tw-color-deep-teal) 0%, var(--tw-color-dark-teal) 100%);
        }
        
        .form-container {
            box-shadow: 0 20px 40px rgba(1, 46, 64, 0.15);
        }
        
        .input-focus:focus {
            border-color: var(--tw-color-light-teal);
            box-shadow: 0 0 0 3px rgba(60, 166, 166, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--tw-color-medium-teal), var(--tw-color-dark-teal));
            transition: all 0.3s ease;
            color: rgb(0, 0, 0) !important;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(2, 103, 115, 0.3);
            color: white !important;
        }
        
        .floating-label {
            transition: all 0.2s ease;
        }
        
        .input-field:focus + .floating-label,
        .input-field:not(:placeholder-shown) + .floating-label {
            transform: translateY(-24px) scale(0.85);
            color: var(--tw-color-light-teal);
        }
    </style>
</head>
<body class="login-container flex items-center justify-center min-h-screen p-4">
    <div class="form-container bg-cream rounded-2xl w-full max-w-md overflow-hidden">
        <!-- Header con acento de color -->
        <div class="h-2 bg-gradient-to-r from-light-teal to-medium-teal"></div>
        
        <div class="p-8">
            <!-- Logo y título -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-medium-teal rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-deep-teal">Iniciar Sesión</h1>
                <p class="text-dark-teal mt-2">Accede a tu cuenta administrativa</p>
            </div>

            <!-- Mensajes de error -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6" role="alert">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <!-- Formulario -->
            <form method="POST" action="/login">
                @csrf
                
                <!-- Campo Email -->
                <div class="mb-6 relative">
                    <input 
                        type="email" 
                        name="email" 
                        class="input-field w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-deep-teal placeholder-transparent input-focus"
                        placeholder=" "
                        value="{{ old('email') }}" 
                        required
                        autocomplete="email"
                    >
                    <label class="floating-label absolute left-4 top-3 text-gray-500 pointer-events-none">
                        Correo electrónico
                    </label>
                    <div class="absolute right-3 top-3">
                        <svg class="w-5 h-5 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                </div>

                <!-- Campo Contraseña -->
                <div class="mb-8 relative">
                    <input 
                        type="password" 
                        name="password" 
                        class="input-field w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-deep-teal placeholder-transparent input-focus"
                        placeholder=" "
                        required
                        autocomplete="current-password"
                    >
                    <label class="floating-label absolute left-4 top-3 text-gray-500 pointer-events-none">
                        Contraseña
                    </label>
                    <div class="absolute right-3 top-3">
                        <svg class="w-5 h-5 text-light-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="btn-primary w-full text-white font-semibold py-3 px-4 rounded-lg mb-2">
                    Ingresar al sistema
                </button>
            </form>
        </div>
    </div>
</body>
</html>