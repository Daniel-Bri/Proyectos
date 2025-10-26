<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionAcademica\AulaController;
use App\Http\Controllers\Administracion\BitacoraController;

// Panel administrativo (solo usuarios con rol “admin” o permiso “ver-bitacora”)
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])  // ← 'role:admin' (singular)
    ->as('admin.')
    ->group(function () {
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
        
        // Bitácora de auditoría
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
        Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
        Route::get('/bitacora/exportar', [BitacoraController::class, 'exportar'])->name('bitacora.exportar');
        Route::post('/bitacora/limpiar', [BitacoraController::class, 'limpiar'])->name('bitacora.limpiar');

        // Aquí puedes añadir otras rutas de administración
        // Route::resource('docentes', DocenteController::class);
        // Route::resource('materias', MateriaController::class);
        // Gestión de Aulas
        Route::resource('aulas', AulaController::class)->names('aulas');
       
    });
