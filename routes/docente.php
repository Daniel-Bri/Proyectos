<?php

use Illuminate\Support\Facades\Route;
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

        // O si prefieres mostrar una vista existente:
        // Route::get('/dashboard', function () {
        //     return view('dashboard'); // ← Usa la vista dashboard principal
        // })->name('dashboard');


        // Materias del docente - USAR EL NUEVO CONTROLADOR
        Route::get('/materias', [MateriaController::class, 'index'])->name('materias.index');
        Route::get('/materias/{materia}', [MateriaController::class, 'show'])->name('materias.show');
        // Horarios del docente
        Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
        
        // Perfil del docente
        Route::get('/perfil', [DocenteController::class, 'perfil'])->name('perfil');

        // Horarios del docente 
        Route::get('/horarios', [HorariosController::class, 'indexDocente'])->name('horarios.index');
        Route::get('/mi-horario', [HorariosController::class, 'miHorario'])->name('mi-horario'); 
        
        // Carga Horaria
        Route::get('/carga-horaria', [DocenteController::class, 'miCargaHoraria'])->name('carga-horaria.index');

        // Cambiar contraseña
        Route::put('/cambiar-password', [DocenteController::class, 'cambiarPassword'])->name('cambiar-password');
    });