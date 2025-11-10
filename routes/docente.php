<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asistencias\AsistenciaController;
use App\Http\Controllers\GestionAcademica\MateriaController;
use App\Http\Controllers\GestionAcademica\DocenteController;
use App\Http\Controllers\GestionDeHorarios\HorariosController;

Route::prefix('docente')
    ->middleware(['auth', 'role:docente'])
    ->as('docente.')
    ->group(function () {
        // Dashboard docente - REDIRIGIR AL DASHBOARD PRINCIPAL
        Route::get('/dashboard', function () {
            return redirect('/dashboard'); // ← Redirige al dashboard principal
        })->name('dashboard');

        // =========================================================================
        // MÓDULO DE ASISTENCIA (TUS RUTAS)
        // =========================================================================
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

        // =========================================================================
        // GESTIÓN ACADÉMICA (RUTAS DE ALEJANDRA)
        // =========================================================================
        
        // Materias del docente
        Route::get('/materias', [MateriaController::class, 'index'])->name('materias.index');
        Route::get('/materias/{materia}', [MateriaController::class, 'show'])->name('materias.show');
        
        // Horarios del docente
        Route::get('/horarios', [HorariosController::class, 'indexDocente'])->name('horarios.index');
        Route::get('/mi-horario', [HorariosController::class, 'miHorario'])->name('mi-horario'); 
        
        // Perfil del docente
        Route::get('/perfil', [DocenteController::class, 'perfil'])->name('perfil');
        
        // Carga Horaria
        Route::get('/carga-horaria', [DocenteController::class, 'miCargaHoraria'])->name('carga-horaria.index');
        
        Route::put('/change-password', [DocenteController::class, 'cambiarPassword'])
    ->middleware(['auth'])
    ->name('password.change');

        // Cambiar contraseña
        Route::put('/cambiar-password', [DocenteController::class, 'cambiarPassword'])->name('cambiar-password');
    });