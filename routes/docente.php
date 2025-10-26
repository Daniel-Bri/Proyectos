<?php

use Illuminate\Support\Facades\Route;

Route::prefix('docente')
    ->middleware(['auth', 'role:docente'])
    ->as('docente.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('docente.dashboard');
        })->name('dashboard');

    });
