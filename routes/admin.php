<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionAcademica\AulaController;
use App\Http\Controllers\Administracion\BitacoraController;
use App\Http\Controllers\Administracion\RolController;
use App\Http\Controllers\Administracion\UserController;
use App\Http\Controllers\GestionAcademica\DocenteController;
use App\Http\Controllers\GestionAcademica\MateriaController;
use App\Http\Controllers\GestionAcademica\GrupoController;
use App\Http\Controllers\GestionDeHorarios\HorariosController;
use App\Http\Controllers\Administracion\CargaMasivaUsuariosController;
use App\Http\Controllers\AnalisisYReportes\ReporteHorariosSemanalesController;
use App\Http\Controllers\AnalisisYReportes\ReporteAsistenciaController;

// =========================================================================
// ✅ RUTAS PARA ADMIN
// =========================================================================
Route::prefix('admin')
    ->middleware(['auth'])
    ->as('admin.')
    ->group(function () {
        // =========================================================================
        // ✅ DASHBOARD PRINCIPAL - AGREGAR ESTA RUTA
        // =========================================================================

        
        // =========================================================================
        // ✅ RUTAS EXCLUSIVAS SOLO PARA ADMIN
        // =========================================================================
        //Route::middleware(['can:acceso_admin'])->group(function () {
            // RUTAS DE GESTIÓN DE USUARIOS SOLO PARA ADMIN
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{user}/profile', [UserController::class, 'profile'])->name('users.profile');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            
            // Rutas adicionales de usuarios
            Route::post('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');
            Route::post('/users/{user}/verification', [UserController::class, 'updateVerification'])->name('users.update-verification');
            Route::post('/users/{user}/generate-token', [UserController::class, 'generateTemporalToken'])->name('users.generate-token');

            // Bitácora de auditoría (SOLO ADMIN)
            Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
            Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
            Route::get('/bitacora/exportar', [BitacoraController::class, 'exportar'])->name('bitacora.exportar');
            Route::post('/bitacora/limpiar', [BitacoraController::class, 'limpiar'])->name('bitacora.limpiar');

            // Gestión de Roles (SOLO ADMIN)
            Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
            Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
            Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show');
            Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
            Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
            Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');


        // =========================================================================
        // ✅ CU18 - CARGA MASIVA DE USUARIOS DESDE EXCEL/CSV
        // =========================================================================
            Route::prefix('carga-masiva')->name('carga-masiva.')->group(function () {
                Route::prefix('usuarios')->name('usuarios.')->group(function () {
                    // Vista principal de carga
                    Route::get('/', [CargaMasivaUsuariosController::class, 'index'])->name('index');
                    
                    // Previsualización del archivo
                    Route::post('/preview', [CargaMasivaUsuariosController::class, 'preview'])->name('preview');
                    
                    // Procesar importación definitiva
                    Route::post('/procesar', [CargaMasivaUsuariosController::class, 'procesar'])->name('procesar');
                    
                    // Descargar plantilla
                    Route::get('/plantilla', [CargaMasivaUsuariosController::class, 'descargarPlantilla'])->name('plantilla');
                });
            });
       // });

        // =========================================================================
        // ✅ RUTAS COMPARTIDAS PARA ADMIN Y COORDINADOR
        // =========================================================================
        
        // MATERIAS - PARA ADMIN Y COORDINADOR
        Route::prefix('materias')->name('materias.')->group(function () {
            Route::get('/', [MateriaController::class, 'index'])->name('index');
            Route::get('/crear', [MateriaController::class, 'create'])->name('create');
            Route::post('/', [MateriaController::class, 'store'])->name('store');
            Route::get('/{sigla}', [MateriaController::class, 'show'])->name('show');
            Route::get('/{sigla}/editar', [MateriaController::class, 'edit'])->name('edit');
            Route::put('/{sigla}', [MateriaController::class, 'update'])->name('update');
            Route::delete('/{sigla}', [MateriaController::class, 'destroy'])->name('destroy');
            
            // Exportación
            Route::get('/exportar/excel', [MateriaController::class, 'export'])->name('export');
            
            // Asignación de aulas
            Route::get('/{sigla}/asignar-aulas', [MateriaController::class, 'asignarAulas'])->name('asignar-aulas');
            Route::post('/{sigla}/asignar-aulas', [MateriaController::class, 'storeAsignarAulas'])->name('store-asignar-aulas');
            
            // Asignación de grupos
            Route::get('/{sigla}/asignar-grupo', [MateriaController::class, 'asignarGrupo'])->name('asignar-grupo');
            Route::post('/{sigla}/asignar-grupo', [MateriaController::class, 'storeAsignarGrupo'])->name('store-asignar-grupo');
            
            // Horarios
            Route::get('/{sigla}/horarios', [MateriaController::class, 'horarios'])->name('horarios');
        });

        // DOCENTES - PARA ADMIN Y COORDINADOR
        Route::prefix('docentes')->name('docentes.')->group(function () {
            Route::get('/', [DocenteController::class, 'index'])->name('index');
            Route::get('/create', [DocenteController::class, 'create'])->name('create');
            Route::post('/', [DocenteController::class, 'store'])->name('store');
            Route::get('/{docente}', [DocenteController::class, 'show'])->name('show');
            Route::get('/{docente}/edit', [DocenteController::class, 'edit'])->name('edit');
            Route::put('/{docente}', [DocenteController::class, 'update'])->name('update');
            Route::delete('/{docente}', [DocenteController::class, 'destroy'])->name('destroy');
            
            // Exportación
            Route::get('/exportar/excel', [DocenteController::class, 'export'])->name('export');
            
            // Cambio de estado
            Route::post('/{docente}/cambiar-estado', [DocenteController::class, 'cambiarEstado'])->name('cambiar-estado');
        });

        // GRUPOS - PARA ADMIN Y COORDINADOR
        Route::prefix('grupos')->name('grupos.')->group(function () {
            Route::get('/', [GrupoController::class, 'index'])->name('index');
            Route::get('/crear', [GrupoController::class, 'create'])->name('create');
            Route::post('/', [GrupoController::class, 'store'])->name('store');
            Route::get('/{id}', [GrupoController::class, 'show'])->name('show');
            Route::get('/{id}/editar', [GrupoController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GrupoController::class, 'update'])->name('update');
            Route::delete('/{id}', [GrupoController::class, 'destroy'])->name('destroy');
            
            // Asignación de materias
            Route::get('/{id}/asignar-materias', [GrupoController::class, 'asignarMaterias'])->name('asignar-materias');
            Route::post('/{id}/asignar-materias', [GrupoController::class, 'storeAsignarMaterias'])->name('store-asignar-materias');
            Route::delete('/{idGrupo}/materia/{siglaMateria}', [GrupoController::class, 'removerMateria'])->name('remover-materia');
            
            // Exportación
            Route::get('/exportar/excel', [GrupoController::class, 'export'])->name('export');
        });

        // AULAS - PARA ADMIN Y COORDINADOR
        Route::prefix('aulas')->name('aulas.')->group(function () {
            Route::get('/', [AulaController::class, 'index'])->name('index');
            Route::get('/create', [AulaController::class, 'create'])->name('create');
            Route::post('/', [AulaController::class, 'store'])->name('store');
            Route::get('/{aula}', [AulaController::class, 'show'])->name('show');
            Route::get('/{aula}/edit', [AulaController::class, 'edit'])->name('edit');
            Route::put('/{aula}', [AulaController::class, 'update'])->name('update');
            Route::delete('/{aula}', [AulaController::class, 'destroy'])->name('destroy');
            
            Route::post('/{aula}/cambiar-estado', [AulaController::class, 'cambiarEstado'])->name('cambiar-estado');
        });

        // REPORTES - PARA ADMIN Y COORDINADOR
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::prefix('horarios-semanales')->name('horarios-semanales.')->group(function () {
                Route::get('/', [ReporteHorariosSemanalesController::class, 'index'])->name('index');
                Route::post('/generar', [ReporteHorariosSemanalesController::class, 'generarReporte'])->name('generar');
            });
            
            Route::prefix('asistencia')->name('asistencia.')->group(function () {
                Route::get('/', [ReporteAsistenciaController::class, 'index'])->name('index');
                Route::post('/generar', [ReporteAsistenciaController::class, 'generarReporte'])->name('generar');
                Route::get('/vista-previa', [ReporteAsistenciaController::class, 'vistaPrevia'])->name('vista-previa');
            });
        });

        // HORARIOS - PARA ADMIN Y COORDINADOR
        Route::get('/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');
        Route::post('/horarios/asignar', [HorariosController::class, 'storeAsignacion'])->name('horarios.store-asignacion');
        Route::resource('/horarios', HorariosController::class);

    }); // <-- CIERRE FINAL DEL GRUPO ADMIN