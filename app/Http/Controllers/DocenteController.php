<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use App\Models\Materia;
use App\Models\Carrera;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            $docentes = Docente::with(['user', 'carreras'])->paginate(10);
        } else if ($user->hasRole('coordinador')) {
            $docentes = Docente::whereHas('user', function($query) use ($user) {
                $query->where('facultad_id', $user->facultad_id);
            })->with(['user', 'carreras'])->paginate(10);
        } else {
            abort(403, 'No tienes permisos para ver esta página.');
        }
        
        return view('admin.docentes.index', compact('docentes'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Solo los administradores pueden crear docentes.');
        }
        
        // Filtrar solo las 3 carreras específicas
        $carreras = Carrera::whereIn('nombre', [
            'Ingeniería en Sistemas',
            'Ingeniería Informática', 
            'Ingeniería en Redes y Telecomunicaciones'
        ])->get();
        
        return view('admin.docentes.create', compact('carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:docente',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:15',
            'sueldo' => 'required|numeric|min:0',
            'fecha_contrato' => 'required|date',
            'carreras' => 'nullable|array',
            'carreras.*' => 'exists:carrera,id' // Cambiado de 'carreras' a 'carrera'
            ]);

        try {
            DB::beginTransaction();

            // Crear usuario
            $user = User::create([
                'name' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make('password123'),
                'password_set' => false
            ]);

            // Crear docente
            $docente = Docente::create([
                'codigo' => $request->codigo,
                'telefono' => $request->telefono,
                'sueldo' => $request->sueldo,
                'fecha_contrato' => $request->fecha_contrato,
                'id_users' => $user->id
            ]);

            // Asignar carreras (solo las permitidas)
            if ($request->has('carreras')) {
                // Filtrar solo las carreras permitidas
                $carrerasPermitidas = Carrera::whereIn('nombre', [
                    'Ingeniería en Sistemas',
                    'Ingeniería Informática',
                    'Ingeniería en Redes y Telecomunicaciones'
                ])->whereIn('id', $request->carreras)->pluck('id')->toArray();
                
                $docente->carreras()->sync($carrerasPermitidas);
            }

            // Asignar rol de docente
            $user->assignRole('docente');

            DB::commit();

            return redirect()->route('docentes.index')
                ->with('success', 'Docente registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar el docente: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($codigo)
    {
        $docente = Docente::with(['user', 'carreras', 'asistencias.grupoMateria.materia'])->findOrFail($codigo);
        
        // Obtener materias únicas a través de asistencias
        $materiasDelDocente = $docente->asistencias->groupBy(function($asistencia) {
            return $asistencia->grupoMateria->sigla_materia;
        })->map(function($group) {
            return $group->first()->grupoMateria->materia;
        });
        
        return view('admin.docentes.show', compact('docente', 'materiasDelDocente'));
    }

    public function edit($codigo)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para editar docentes.');
        }

        $docente = Docente::with(['user', 'carreras'])->findOrFail($codigo);
        
        // Filtrar solo las 3 carreras específicas
        $carreras = Carrera::whereIn('nombre', [
            'Ingeniería en Sistemas',
            'Ingeniería Informática',
            'Ingeniería en Redes y Telecomunicaciones'
        ])->get();
        
        return view('admin.docentes.edit', compact('docente', 'carreras'));
    }

    public function update(Request $request, $codigo)
    {
        $docente = Docente::findOrFail($codigo);

        $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('docentes')->ignore($docente->codigo, 'codigo')
            ],
            'nombre' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($docente->id_users)
            ],
            'telefono' => 'required|string|max:15',
            'sueldo' => 'required|numeric|min:0',
            'fecha_contrato' => 'required|date',
            'carreras' => 'nullable|array',
            'carreras.*' => 'exists:carreras,id'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar usuario
            $docente->user->update([
                'name' => $request->nombre,
                'email' => $request->email
            ]);

            // Actualizar docente
            $docente->update([
                'codigo' => $request->codigo,
                'telefono' => $request->telefono,
                'sueldo' => $request->sueldo,
                'fecha_contrato' => $request->fecha_contrato
            ]);

            // Actualizar carreras (solo las permitidas)
            if ($request->has('carreras')) {
                // Filtrar solo las carreras permitidas
                $carrerasPermitidas = Carrera::whereIn('nombre', [
                    'Ingeniería en Sistemas',
                    'Ingeniería Informática',
                    'Ingeniería en Redes y Telecomunicaciones'
                ])->whereIn('id', $request->carreras)->pluck('id')->toArray();
                
                $docente->carreras()->sync($carrerasPermitidas);
            } else {
                $docente->carreras()->sync([]);
            }

            DB::commit();

            return redirect()->route('docentes.index')
                ->with('success', 'Docente actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el docente: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($codigo)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar docentes.');
        }

        $docente = Docente::findOrFail($codigo);

        try {
            DB::beginTransaction();

            // Eliminar relaciones
            $docente->carreras()->detach();
            
            // Eliminar usuario
            $docente->user->delete();
            
            // Eliminar docente
            $docente->delete();

            DB::commit();

            return redirect()->route('docentes.index')
                ->with('success', 'Docente eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el docente: ' . $e->getMessage());
        }
    }

    // Método auxiliar para obtener carreras permitidas
    private function getCarrerasPermitidas()
    {
        return Carrera::whereIn('nombre', [
            'Ingeniería en Sistemas',
            'Ingeniería Informática',
            'Ingeniería en Redes y Telecomunicaciones'
        ])->get();
    }
}