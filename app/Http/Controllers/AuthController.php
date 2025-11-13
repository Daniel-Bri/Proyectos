<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// AÑADE ESTA LÍNEA PARA IMPORTAR EL CONTROLADOR DE BITÁCORA
use App\Http\Controllers\Administracion\BitacoraController;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // REGISTRO EN BITÁCORA: Login exitoso
            BitacoraController::registrarLogin($user->id, $request);
            
            $request->session()->regenerate();
            
            // Registro adicional con más detalles
            BitacoraController::registrar(
                'Inicio de sesión exitoso',
                'Usuario',
                $user->id,
                $user->id,
                $request,
                "Usuario {$user->nombre} ({$user->email}) inició sesión correctamente"
            );
            
            return redirect()->intended('/dashboard')->with('success', 'Inicio de sesión correcto');
        }

        // REGISTRO EN BITÁCORA: Intento de login fallido
        BitacoraController::registrar(
            'Intento de inicio de sesión fallido',
            'Usuario',
            null,
            null,
            $request,
            "Intento fallido con email: {$request->email}"
        );

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ])->onlyInput('email');
    }

    // Mostrar formulario de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // REGISTRO EN BITÁCORA: Registro de nuevo usuario
        BitacoraController::registrarCreacion(
            'Usuario',
            $user->id,
            $user->id,
            "Nuevo usuario registrado: {$user->nombre} ({$user->email})"
        );

        Auth::login($user);

        // REGISTRO EN BITÁCORA: Login automático después del registro
        BitacoraController::registrarLogin($user->id, $request);

        return redirect('/dashboard')->with('success', 'Cuenta creada correctamente');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            // REGISTRO EN BITÁCORA: Logout
            BitacoraController::registrarLogout($user->id, $request);
            
            // Registro adicional con más detalles
            BitacoraController::registrar(
                'Cierre de sesión',
                'Usuario',
                $user->id,
                $user->id,
                $request,
                "Usuario {$user->nombre} ({$user->email}) cerró sesión"
            );
        } else {
            // REGISTRO EN BITÁCORA: Logout sin usuario autenticado
            BitacoraController::registrar(
                'Cierre de sesión (sin usuario)',
                'Usuario',
                null,
                null,
                $request,
                'Intento de cierre de sesión sin usuario autenticado'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }

    // Método adicional para mostrar formulario de cambio de contraseña
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Método para cambiar contraseña
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            // REGISTRO EN BITÁCORA: Intento de cambio de contraseña fallido
            BitacoraController::registrar(
                'Intento de cambio de contraseña fallido',
                'Usuario',
                $user->id,
                $user->id,
                $request,
                'Contraseña actual incorrecta'
            );

            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        // REGISTRO EN BITÁCORA: Cambio de contraseña exitoso
        BitacoraController::registrar(
            'Cambio de contraseña exitoso',
            'Usuario',
            $user->id,
            $user->id,
            $request,
            'Contraseña actualizada correctamente'
        );

        return redirect('/dashboard')->with('success', 'Contraseña cambiada correctamente');
    }

    // Método para mostrar perfil de usuario
    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    // Método para actualizar perfil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $oldData = [
            'nombre' => $user->nombre,
            'email' => $user->email,
        ];

        $user->nombre = $request->nombre;
        $user->email = $request->email;
        $user->save();

        // REGISTRO EN BITÁCORA: Actualización de perfil
        $changes = [];
        if ($oldData['nombre'] !== $user->nombre) {
            $changes[] = "Nombre: {$oldData['nombre']} → {$user->nombre}";
        }
        if ($oldData['email'] !== $user->email) {
            $changes[] = "Email: {$oldData['email']} → {$user->email}";
        }

        BitacoraController::registrarActualizacion(
            'Usuario',
            $user->id,
            $user->id,
            $changes ? "Perfil actualizado: " . implode(', ', $changes) : "Perfil actualizado sin cambios"
        );

        return redirect('/profile')->with('success', 'Perfil actualizado correctamente');
    }
}