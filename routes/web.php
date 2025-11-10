<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\GestionAcademica\DocenteController;
use App\Http\Controllers\Administracion\UserController;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Autenticación
require __DIR__.'/auth.php';

// =============================================
// RUTAS PROTEGIDAS (REQUIEREN LOGIN)
// =============================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // =============================================
    // RUTAS PARA CAMBIO DE CONTRASEÑA
    // =============================================
    Route::get('/change-password', function () {
        return view('auth.change-password');
    })->name('password.change');
    
    Route::post('/change-password', [DocenteController::class, 'cambiarPassword'])->name('password.update');

});

// =============================================
// RUTAS RESTANTES (agrega las demás aquí)
// =============================================

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
        ";
    }
    return "<h1>No autenticado</h1>";
});

// Rutas de docentes (si es que no están en admin.php)
Route::get('/roles-permisos', [RolController::class, 'index'])->name('roles.permisos');
Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes.index');
Route::get('/docentes/crear', [DocenteController::class, 'create'])->name('docentes.create');
Route::post('/docentes', [DocenteController::class, 'store'])->name('docentes.store');
Route::get('/docentes/{codigo}', [DocenteController::class, 'show'])->name('docentes.show');
Route::get('/docentes/{codigo}/editar', [DocenteController::class, 'edit'])->name('docentes.edit');
Route::put('/docentes/{codigo}', [DocenteController::class, 'update'])->name('docentes.update');
Route::delete('/docentes/{codigo}', [DocenteController::class, 'destroy'])->name('docentes.destroy');

// Rutas para carga horaria
Route::prefix('admin/docentes')->group(function () {
    Route::get('/{codigo}/carga-horaria', [DocenteController::class, 'cargaHoraria'])->name('admin.docentes.carga-horaria');
    Route::post('/{codigo}/asignar-grupo', [DocenteController::class, 'asignarGrupo'])->name('admin.docentes.asignar-grupo');
    Route::delete('/{codigo}/eliminar-grupo/{grupoMateriaId}', [DocenteController::class, 'eliminarGrupo'])->name('admin.docentes.eliminar-grupo');
});

// Rutas administrativas
Route::prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    // ... otras rutas de usuarios
});