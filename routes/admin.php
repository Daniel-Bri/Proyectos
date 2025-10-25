<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionAcademica\AulaController;
use App\Http\Controllers\Administracion\BitacoraController;
use App\Http\Controllers\Administracion\RolController;

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
        // Gestión de Roles

        Route::controller(RolController::class)
            ->prefix('roles')
            ->name('roles.')
            ->group(function () {
                // CRUD básico de roles
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{role}', 'show')->name('show');
                Route::get('/{role}/edit', 'edit')->name('edit');
                Route::put('/{role}', 'update')->name('update');
                Route::delete('/{role}', 'destroy')->name('destroy');
                
                // Acciones adicionales para roles
                Route::post('/{role}/asignar-permisos', 'asignarPermisos')->name('asignar-permisos');
            });

        // Route::resource('docentes', DocenteController::class);
        // Route::resource('materias', MateriaController::class);
        // Gestión de Aulas
        Route::resource('aulas', AulaController::class)->names('aulas');
    });
