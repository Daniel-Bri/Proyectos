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
        if ($request->filled('codigo')) {
            $query->where('codigo', 'LIKE', '%' . $request->codigo . '%');
        }

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }

        if ($request->filled('capacidad')) {
            $query->where('capacidad', '>=', $request->capacidad);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $aulas = $query->orderBy('nombre')->paginate(15);

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
            'codigo' => 'required|string|max:20|unique:aulas,codigo',
            'nombre' => 'required|string|max:100',
            'capacidad' => 'required|integer|min:1',
            'tipo' => 'required|in:ula,biblioteca,laboratorio,auditorio,otros',
            'ubicacion' => 'required|string|max:200',
            'equipamiento' => 'nullable|string',
            'estado' => 'required|in:Disponible,En Mantenimiento,No Disponible',
        ]);

        $aula = Aula::create($request->all());

        // Registrar en bitácora
        BitacoraController::registrarCreacion('Aula', $aula->id, auth()->id(), "Aula {$aula->codigo} creada");

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

        $request->validate([
            'codigo' => 'required|string|max:20|unique:aulas,codigo,' . $aula->id,
            'nombre' => 'required|string|max:100',
            'capacidad' => 'required|integer|min:1',
            'tipo' => 'required|in:aula,biblioteca,laboratorio,auditorio,otros',
            'ubicacion' => 'required|string|max:200',
            'equipamiento' => 'nullable|string',
            'estado' => 'required|in:Disponible,En Mantenimiento,No Disponible',
        ]);

        $aula->update($request->all());

        // Registrar en bitácora
        BitacoraController::registrarActualizacion('Aula', $aula->id, auth()->id(), "Aula {$aula->codigo} actualizada");

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

        // Registrar en bitácora antes de eliminar
        BitacoraController::registrarEliminacion('Aula', $aula->id, auth()->id(), "Aula {$aula->codigo} eliminada");

        $aula->delete();

        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula eliminada exitosamente.');
    }
}