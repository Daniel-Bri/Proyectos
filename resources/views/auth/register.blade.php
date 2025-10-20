<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Crear Cuenta</h2>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="/register">
            @csrf
            <div class="mb-4">
                <label>Nombre</label>
                <input type="text" name="nombre" class="w-full border p-2 rounded" value="{{ old('nombre') }}" required>
            </div>
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email') }}" required>
            </div>
            <div class="mb-4">
                <label>Contraseña</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label>Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
            </div>
            <button class="bg-green-600 text-white w-full py-2 rounded">Registrarse</button>
        </form>
        <p class="mt-4 text-center">
            ¿Ya tienes cuenta?
            <a href="/login" class="text-blue-600">Inicia sesión</a>
        </p>
    </div>
</body>
</html>
