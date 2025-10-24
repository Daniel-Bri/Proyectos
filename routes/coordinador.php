<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coordinador\HorarioController;

Route::prefix('coordinador')
    ->middleware(['auth', 'role:coordinador'])
    ->as('coordinador.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('dashboard');

        Route::resource('/horarios', HorarioController::class);
    });
