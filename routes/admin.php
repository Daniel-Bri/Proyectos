<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionAcademica\AulaController;
use App\Http\Controllers\Administracion\BitacoraController;
use App\Http\Controllers\Administracion\RolController;
use App\Http\Controllers\Administracion\UserController;
use App\Http\Controllers\GestionAcademica\DocenteController;
use App\Http\Controllers\GestionAcademica\MateriaController;
use App\Http\Controllers\GestionDeHorarios\HorariosController;

// Panel administrativo (solo usuarios con rol “admin”)
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->as('admin.')
    ->group(function () {

        // ✅ RUTAS DE AULAS PARA ADMIN (CORREGIDAS)
        Route::prefix('aulas')->name('aulas.')->group(function () {
            Route::get('/', [AulaController::class, 'index'])->name('index');
            Route::get('/create', [AulaController::class, 'create'])->name('create');
            Route::post('/', [AulaController::class, 'store'])->name('store');
            Route::get('/{aula}', [AulaController::class, 'show'])->name('show');
            Route::get('/{aula}/edit', [AulaController::class, 'edit'])->name('edit');
            Route::put('/{aula}', [AulaController::class, 'update'])->name('update');
            Route::delete('/{aula}', [AulaController::class, 'destroy'])->name('destroy');
            
            // Ruta adicional para cambiar estado
            Route::post('/{aula}/cambiar-estado', [AulaController::class, 'cambiarEstado'])->name('cambiar-estado');
        });

        // ✅ RUTAS DE MATERIAS PARA ADMIN
        Route::prefix('materias')->name('materias.')->group(function () {
            // Rutas CRUD básicas
            Route::get('/', [MateriaController::class, 'index'])->name('index');
            Route::get('/create', [MateriaController::class, 'create'])->name('create');
            Route::post('/', [MateriaController::class, 'store'])->name('store');
            Route::get('/{sigla}', [MateriaController::class, 'show'])->name('show');
            Route::get('/{sigla}/edit', [MateriaController::class, 'edit'])->name('edit');
            Route::put('/{sigla}', [MateriaController::class, 'update'])->name('update');
            Route::delete('/{sigla}', [MateriaController::class, 'destroy'])->name('destroy');
            
            // Exportación
            Route::get('/export', [MateriaController::class, 'export'])->name('export');
            
            // Gestión de Aulas
            Route::get('/{sigla}/asignar-aulas', [MateriaController::class, 'asignarAulas'])->name('asignar-aulas');
            Route::post('/{sigla}/store-asignar-aulas', [MateriaController::class, 'storeAsignarAulas'])->name('store-asignar-aulas');
            
            // Horarios y APIs
            Route::get('/{sigla}/horarios', [MateriaController::class, 'horarios'])->name('horarios');
            Route::get('/get-horarios', [MateriaController::class, 'getHorarios'])->name('get-horarios');
            Route::get('/get-aulas', [MateriaController::class, 'getAulas'])->name('get-aulas');
        });
        
        // Bitácora de auditoría
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
        Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
        Route::get('/bitacora/exportar', [BitacoraController::class, 'exportar'])->name('bitacora.exportar');
        Route::post('/bitacora/limpiar', [BitacoraController::class, 'limpiar'])->name('bitacora.limpiar');

        // Gestión de Roles
        Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show');
        Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');

        // Gestión de Usuarios
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/profile', [UserController::class, 'profile'])->name('users.profile');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Rutas adicionales de usuarios
        Route::post('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');
        Route::post('/users/{user}/verification', [UserController::class, 'updateVerification'])->name('users.update-verification');
        Route::post('/users/{user}/generate-token', [UserController::class, 'generateTemporalToken'])->name('users.generate-token');

        // horarios - GESTIÓN DE HORARIOS PARA ADMIN
        Route::get('/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');
        Route::post('/horarios/asignar', [HorariosController::class, 'storeAsignacion'])->name('horarios.store-asignacion');
        Route::resource('/horarios', HorariosController::class);
    });