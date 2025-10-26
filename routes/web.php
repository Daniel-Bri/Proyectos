<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Dashboard principal (requiere login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Autenticación (login, logout, register, etc.)
require __DIR__.'/auth.php';

// Grupos de rutas por rol
require __DIR__.'/admin.php';
require __DIR__.'/docente.php';
require __DIR__.'/coordinador.php';



require __DIR__.'/visualizacion.php';
// Ruta temporal para debug
Route::get('/debug-auth', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return "
            <h1>Usuario Autenticado</h1>
            <p><strong>ID:</strong> {$user->id}</p>
            <p><strong>Nombre:</strong> {$user->name}</p>
            <p><strong>Email:</strong> {$user->email}</p>
            <p><strong>Roles:</strong> " . $user->getRoleNames()->join(', ') . "</p>
            <p><strong>¿Puede acceder a /admin/bitacora?</strong> " . ($user->hasRole('admin') ? 'SÍ ✅' : 'NO ❌') . "</p>
        ";
    }
    return "<h1>No autenticado</h1>";
});