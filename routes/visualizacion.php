<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionDeHorarios\VisualizacionController;

// Grupo con prefijo para evitar conflictos
Route::prefix('visualizacion-semana')->name('visualizacion-semana.')->middleware(['auth'])->group(function () {
    // Ruta principal: /visualizacion-semana
    Route::get('/', [VisualizacionController::class, 'index'])->name('index');
    Route::get('/calendario', [VisualizacionController::class, 'calendario'])->name('calendario');
    // Ruta para detalle: /visualizacion-semana/{id}
    Route::get('/{id}', [VisualizacionController::class, 'show'])->name('show');

    
});