<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/coordinador', function () {
        return view('coordinador.index');
    })->name('coordinador.index');
});
