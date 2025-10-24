<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Docente\AsistenciaController;

Route::prefix('docente')
    ->middleware(['auth', 'role:docente'])
    ->as('docente.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('docente.dashboard');
        })->name('dashboard');

        Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
    });
