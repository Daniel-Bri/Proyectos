<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionDeHorarios\HorariosController;

Route::prefix('coordinador')
    ->middleware(['auth', 'role:coordinador'])
    ->as('coordinador.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('dashboard');

        // Rutas específicas DEBEN IR ANTES del resource
        Route::get('/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');
        Route::post('/horarios/asignar', [HorariosController::class, 'storeAsignacion'])->name('horarios.store-asignacion');
        
        // Resource DEBE IR DESPUÉS de las rutas específicas
        Route::resource('/horarios', HorariosController::class);
    });