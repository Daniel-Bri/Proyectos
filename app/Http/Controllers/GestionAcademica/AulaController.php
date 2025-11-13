<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Illuminate\Http\Request;
use App\Http\Controllers\Administracion\BitacoraController;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permisos
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para gestionar aulas.');
        }

        $query = Aula::query();

        // Filtros
        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('capacidad')) {
            $query->where('capacidad', '>=', $request->capacidad);
        }

        $aulas = $query->orderBy('nombre')->paginate(15);

        // Registrar consulta en bitácora
        BitacoraController::registrar(
            'Consulta de aulas',
            'Aula',
            null,
            auth()->id(),
            $request,
            "Consultó listado de aulas con filtros: " . $this->obtenerFiltrosBitacora($request)
        );

        return view('admin.aulas.index', compact('aulas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para crear aulas.');
        }

        // Registrar acceso al formulario de creación
        BitacoraController::registrar(
            'Acceso a creación de aula',
            'Aula',
            null,
            auth()->id(),
            request(),
            'Accedió al formulario de creación de aula'
        );

        return view('admin.aulas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para crear aulas.');
        }

        $request->validate([
            'nombre' => 'required|string|max:100|unique:aula,nombre', // ✅ Usar nombre como identificador único
            'capacidad' => 'required|integer|min:1',
            'tipo' => 'required|in:aula,laboratorio,biblioteca,auditorio,otros',
        ]);

        $aula = Aula::create([
            'nombre' => $request->nombre,
            'capacidad' => $request->capacidad,
            'tipo' => $request->tipo,
        ]);

        // Registrar en bitácora
        BitacoraController::registrarCreacion(
            'Aula', 
            $aula->id, 
            auth()->id(), 
            "Aula {$aula->nombre} creada. Capacidad: {$aula->capacidad}, Tipo: {$aula->tipo}"
        );

        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aula $aula)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para ver aulas.');
        }

        // Registrar consulta de detalle en bitácora
        BitacoraController::registrar(
            'Consulta de aula',
            'Aula',
            $aula->id,
            auth()->id(),
            request(),
            "Consultó detalles del aula: {$aula->nombre}"
        );

        return view('admin.aulas.show', compact('aula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aula $aula)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para editar aulas.');
        }

        // Registrar acceso a edición en bitácora
        BitacoraController::registrar(
            'Acceso a edición de aula',
            'Aula',
            $aula->id,
            auth()->id(),
            request(),
            "Accedió a editar aula: {$aula->nombre}"
        );

        return view('admin.aulas.edit', compact('aula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aula $aula)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para editar aulas.');
        }

        // Guardar datos antiguos para comparación
        $datosAntiguos = $aula->toArray();

        $request->validate([
            'nombre' => 'required|string|max:100|unique:aula,nombre,' . $aula->id,
            'capacidad' => 'required|integer|min:1',
            'tipo' => 'required|in:aula,laboratorio,biblioteca,auditorio,otros',
        ]);

        $aula->update([
            'nombre' => $request->nombre,
            'capacidad' => $request->capacidad,
            'tipo' => $request->tipo,
        ]);

        // Obtener cambios realizados
        $cambios = $this->obtenerCambiosBitacora($datosAntiguos, $aula->toArray());

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Aula', 
            $aula->id, 
            auth()->id(), 
            "Aula {$aula->nombre} actualizada. Cambios: " . ($cambios ?: 'Sin cambios detectados')
        );

        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aula $aula)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar aulas.');
        }

        // Guardar información para la bitácora antes de eliminar
        $infoAula = "Nombre: {$aula->nombre}, Capacidad: {$aula->capacidad}, Tipo: {$aula->tipo}";

        // Registrar en bitácora antes de eliminar
        BitacoraController::registrarEliminacion(
            'Aula', 
            $aula->id, 
            auth()->id(), 
            "Aula eliminada: {$infoAula}"
        );

        $aula->delete();

        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula eliminada exitosamente.');
    }

    /**
     * Obtener los filtros aplicados para la bitácora
     */
    private function obtenerFiltrosBitacora(Request $request)
    {
        $filtros = [];
        
        if ($request->filled('nombre')) {
            $filtros[] = "nombre: {$request->nombre}";
        }
        
        if ($request->filled('tipo')) {
            $filtros[] = "tipo: {$request->tipo}";
        }
        
        if ($request->filled('capacidad')) {
            $filtros[] = "capacidad mínima: {$request->capacidad}";
        }

        return $filtros ? implode(', ', $filtros) : 'Sin filtros';
    }

    /**
     * Obtener los cambios realizados en una actualización
     */
    private function obtenerCambiosBitacora(array $datosAntiguos, array $datosNuevos)
    {
        $cambios = [];
        $camposRelevantes = ['nombre', 'capacidad', 'tipo'];

        foreach ($camposRelevantes as $campo) {
            if (isset($datosAntiguos[$campo]) && isset($datosNuevos[$campo]) && 
                $datosAntiguos[$campo] != $datosNuevos[$campo]) {
                $cambios[] = "{$campo}: '{$datosAntiguos[$campo]}' → '{$datosNuevos[$campo]}'";
            }
        }

        return implode('; ', $cambios);
    }
}