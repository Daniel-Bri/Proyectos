<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/docente', function () {
        return view('docente.index');
    })->name('docente.index');
});
