<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asistencias\AsistenciaController;

Route::prefix('docente')
    ->middleware(['auth', 'role:docente'])
    ->as('docente.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('docente.dashboard');
        })->name('dashboard');

        // Módulo de Asistencia
        Route::prefix('asistencia')
            ->as('asistencia.')
            ->group(function () {
                // CU12 & CU13 - Vista principal
                Route::get('/', [AsistenciaController::class, 'index'])->name('index');
                
                // CU12 - Registro con código temporal
                Route::get('/codigo/{id}', [AsistenciaController::class, 'mostrarCodigo'])->name('codigo');
                Route::post('/codigo/validar', [AsistenciaController::class, 'validarCodigo'])->name('codigo.validar');
                Route::post('/codigo/generar', [AsistenciaController::class, 'generarCodigo'])->name('codigo.generar');
                
                // CU13 - Registro con QR
                Route::get('/qr/{id}', [AsistenciaController::class, 'mostrarQR'])->name('qr');
                Route::post('/qr/validar', [AsistenciaController::class, 'validarQR'])->name('qr.validar');
                Route::get('/qr/generar/{id}', [AsistenciaController::class, 'generarQR'])->name('qr.generar');
                
                // Confirmación común
                Route::post('/registrar', [AsistenciaController::class, 'registrarAsistencia'])->name('registrar');
                Route::get('/confirmacion/{id}', [AsistenciaController::class, 'confirmacion'])->name('confirmacion');
                
                // Historial
                Route::get('/historial', [AsistenciaController::class, 'historial'])->name('historial');
            });
    });