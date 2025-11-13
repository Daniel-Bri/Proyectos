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
                
                // CU13 - QR (RUTAS CORREGIDAS)
                Route::get('/qr/{id}', [AsistenciaController::class, 'mostrarQR'])->name('qr');
                Route::get('/qr/{id}/generar', [AsistenciaController::class, 'generarQR'])->name('qr.generar');
                
                // ✅ RUTA CORREGIDA: Cambiar completamente la ruta de validación
                Route::get('/validar-qr-escaneado', [AsistenciaController::class, 'validarQR'])
                     ->name('qr.validar');
                
                // CU12 - Código temporal
                Route::get('/codigo/{id}', [AsistenciaController::class, 'mostrarCodigo'])->name('codigo');
                Route::post('/codigo/validar', [AsistenciaController::class, 'validarCodigo'])->name('codigo.validar');
                
                // Confirmación
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