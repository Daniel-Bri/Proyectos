<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\GestionDeHorarios\HorariosController;
use App\Http\Controllers\GestionDeHorarios\AsignacionAutomaticaController;
use App\Http\Controllers\AnalisisYReportes\ReporteAulasController;

Route::prefix('coordinador')
    ->middleware(['auth', 'role:coordinador'])
    ->as('coordinador.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('dashboard');

        // =========================================================================
        // GESTIÓN DE MATERIAS - COORDINADOR (CON CONTROLADOR EXISTENTE)
        // =========================================================================
        Route::prefix('materias')->name('materias.')->group(function () {
            // CRUD Básico
            Route::get('/', [MateriaController::class, 'index'])->name('index');
            Route::get('/create', [MateriaController::class, 'create'])->name('create');
            Route::post('/', [MateriaController::class, 'store'])->name('store');
            Route::get('/{sigla}', [MateriaController::class, 'show'])->name('show');
            Route::get('/{sigla}/edit', [MateriaController::class, 'edit'])->name('edit');
            Route::put('/{sigla}', [MateriaController::class, 'update'])->name('update');
            
            // Asignación de Grupos - Coordinador
            Route::get('/{sigla}/asignar-grupo', [MateriaController::class, 'asignarGrupo'])->name('asignar-grupo');
            Route::post('/{sigla}/asignar-grupo', [MateriaController::class, 'storeAsignarGrupo'])->name('store-asignar-grupo');
            
            // Horarios
            Route::get('/{sigla}/horarios', [MateriaController::class, 'horarios'])->name('horarios');
        });

        // API Routes para Materias - Coordinador (SIN PREFIJO API)
        Route::prefix('materias')->name('materias.')->group(function () {
            Route::get('/get-horarios', [MateriaController::class, 'getHorarios'])->name('get-horarios');
            Route::get('/get-aulas', [MateriaController::class, 'getAulas'])->name('get-aulas');
        });

        // =========================================================================
        // GESTIÓN DE HORARIOS - COORDINADOR (RUTAS CORREGIDAS)
        // =========================================================================
        Route::prefix('horarios')->name('horarios.')->group(function () {
            // CREAR HORARIO BASE (sin asignación)
            Route::get('/create-base', [HorariosController::class, 'create'])->name('create-base');
            Route::post('/store-base', [HorariosController::class, 'store'])->name('store-base');
            
            // ASIGNAR HORARIO EXISTENTE
            Route::get('/asignar', [HorariosController::class, 'asignar'])->name('asignar');
            Route::post('/store-asignacion', [HorariosController::class, 'storeAsignacion'])->name('store-asignacion');
            
            // CRUD PARA HORARIOS ASIGNADOS
            Route::get('/', [HorariosController::class, 'index'])->name('index');
            Route::get('/{id}', [HorariosController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HorariosController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HorariosController::class, 'update'])->name('update');
            Route::delete('/{id}', [HorariosController::class, 'destroy'])->name('destroy');
        });

        // =========================================================================
        // ASIGNACIÓN AUTOMÁTICA DE HORARIOS - NUEVAS RUTAS
        // =========================================================================
        Route::prefix('asignacion-automatica')->name('asignacion-automatica.')->group(function () {
            Route::get('/', [AsignacionAutomaticaController::class, 'index'])->name('index');
            Route::post('/completa', [AsignacionAutomaticaController::class, 'asignacionCompleta'])->name('completa');
            Route::post('/inteligente', [AsignacionAutomaticaController::class, 'asignacionInteligente'])->name('inteligente');
        });

        // =========================================================================
        // REPORTES - RUTAS CORREGIDAS
        // =========================================================================
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::prefix('aulas')->name('aulas.')->group(function () {
                // Vista principal
                Route::get('/disponibles', [ReporteAulasController::class, 'index'])->name('disponibles');
                
                // Generar reporte
                Route::post('/disponibles/generar', [ReporteAulasController::class, 'generarReporte'])->name('disponibles.generar');
                
                // Exportar PDF
                Route::get('/disponibles/pdf', [ReporteAulasController::class, 'generarPDF'])->name('disponibles.pdf');
            });
        });
    });