<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
//use App\Models\Carrera;
//use App\Models\GestionAcademica;
use App\Models\Materia;
use App\Models\GrupoMateria;
use App\Http\Controllers\Administracion\BitacoraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    /**
     * Verificar acceso básico para admin y coordinador
     */
    private function checkAccess()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Usuario no autenticado');
        }
        
        // SOLUCIÓN: Permitir acceso a admin y coordinador
        if ($user->hasRole('admin') || $user->hasRole('coordinador')) {
            return true;
        }
        
        abort(403, 'No tienes permisos para acceder a esta sección');
    }

    /**
     * Determinar la vista según el rol - MODIFICADO
     */
    private function getViewPath($viewName)
    {
        $user = Auth::user();
        
        // Tanto admin como coordinador usan las vistas del admin
        if ($user->hasRole('admin') || $user->hasRole('coordinador')) {
            return "admin.grupos.{$viewName}";
        } else {
            return "docente.grupos.{$viewName}";
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $user = Auth::user();
        
        if ($user->hasRole('admin') || $user->hasRole('coordinador')) {
            $grupos = Grupo::with(['grupoMaterias.materia'])
                ->orderBy('nombre')
                ->paginate(10);
                
        } else {
            // Docente - grupos donde imparte materias
            $docente = $user->docente;
            
            if (!$docente) {
                $grupos = collect();
            } else {
                $grupos = Grupo::whereHas('grupoMaterias.horarios', function($query) use ($docente) {
                    $query->where('id_docente', $docente->codigo);
                })
                ->with(['grupoMaterias.materia'])
                ->orderBy('nombre')
                ->get();
            }
        }
        
        // Registrar en bitácora
        $this->registrarBitacoraSegura('Consulta', 'Grupo', null, $user->id, 'Consultó listado de grupos');
        
        $view = $this->getViewPath('index');
        
        if ($user->hasRole('docente')) {
            return view($view, compact('grupos', 'docente'));
        }
        
        return view($view, compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $user = Auth::user();
        
        $view = $this->getViewPath('create');
        return view($view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $user = Auth::user();

        $request->validate([
            'nombre' => 'required|max:50|unique:grupo,nombre',
            'gestion' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();
            
            $grupo = Grupo::create([
                'nombre' => $request->nombre,
                'gestion' => $request->gestion
            ]);
            
            // Registrar en bitácora
            $this->registrarBitacoraSegura('Creación', 'Grupo', $grupo->id, $user->id, 
                "Nuevo grupo: {$grupo->nombre} - Gestión: {$grupo->gestion}");
            
            DB::commit();
            
            return redirect()->route('admin.grupos.index')
                ->with('success', 'Grupo creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el grupo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        try {
            $grupo = Grupo::with([
                'grupoMaterias.materia',
                'grupoMaterias.horarios.horario',
                'grupoMaterias.horarios.aula',
                'grupoMaterias.horarios.docente'
            ])->findOrFail($id);

            $user = Auth::user();
            // Registrar en bitácora
            $this->registrarBitacoraSegura('Consulta', 'Grupo', $grupo->id, $user->id, 
                "Consultó detalles del grupo: {$grupo->nombre}");

            $view = $this->getViewPath('show');
            return view($view, compact('grupo'));
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $grupo = Grupo::findOrFail($id);

        $view = $this->getViewPath('edit');
        return view($view, compact('grupo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $grupo = Grupo::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'nombre' => 'required|max:50|unique:grupo,nombre,' . $grupo->id,
            'gestion' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();
            
            // Guardar datos antiguos para bitácora
            $datosAntiguos = $grupo->toArray();
            $grupo->update([
                'nombre' => $request->nombre,
                'gestion' => $request->gestion
            ]);
            
            // Registrar en bitácora
            $this->registrarBitacoraSegura('Actualización', 'Grupo', $grupo->id, $user->id, 
                "Actualizó grupo: {$grupo->nombre}. Cambios: " . $this->getCambios($datosAntiguos, $grupo->toArray()));
            
            DB::commit();
            
            return redirect()->route('admin.grupos.index')
                ->with('success', 'Grupo actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el grupo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $grupo = Grupo::findOrFail($id);
        $user = Auth::user();

        try {
            // Verificar si tiene materias asignadas
            if ($grupo->grupoMaterias()->exists()) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el grupo porque tiene materias asignadas.');
            }
            
            DB::beginTransaction();
            
            $grupoData = $grupo->toArray();
            $grupo->delete();
            
            // Registrar en bitácora
            $this->registrarBitacoraSegura('Eliminación', 'Grupo', $id, $user->id, 
                "Eliminó grupo: {$grupoData['nombre']}");
            
            DB::commit();
            
            return redirect()->route('admin.grupos.index')
                ->with('success', 'Grupo eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Asignar materias a un grupo
     */
    public function asignarMaterias($id)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $grupo = Grupo::with(['grupoMaterias.materia'])->findOrFail($id);
        
        // CORREGIDO: Eliminar el filtro por estado que no existe
        $materias = Materia::whereNotIn('sigla', $grupo->grupoMaterias->pluck('sigla_materia'))
            ->orderBy('nombre')
            ->get();

        $view = $this->getViewPath('asignar-materias');
        return view($view, compact('grupo', 'materias'));
    }

    /**
     * Procesar asignación de materias
     */
    public function storeAsignarMaterias(Request $request, $id)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $grupo = Grupo::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materia,sigla'
        ]);

        try {
            DB::beginTransaction();

            $materiasAsignadas = [];
            foreach ($request->materias as $siglaMateria) {
                $grupoMateria = GrupoMateria::create([
                    'id_grupo' => $grupo->id,
                    'sigla_materia' => $siglaMateria,
                    'id_gestion' => 1 // Ajusta según tu lógica de gestión
                ]);
                $materiasAsignadas[] = $siglaMateria;
            }

            // Registrar en bitácora
            $this->registrarBitacoraSegura('Asignación', 'Materias a Grupo', $grupo->id, $user->id, 
                "Asignó " . count($materiasAsignadas) . " materias al grupo {$grupo->nombre}");

            DB::commit();

            return redirect()->route('admin.grupos.show', $grupo->id)
                ->with('success', count($materiasAsignadas) . ' materias asignadas al grupo exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al asignar materias al grupo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remover materia de un grupo
     */
    public function removerMateria(Request $request, $idGrupo, $siglaMateria)
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $grupo = Grupo::findOrFail($idGrupo);
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $grupoMateria = GrupoMateria::where('id_grupo', $grupo->id)
                ->where('sigla_materia', $siglaMateria)
                ->firstOrFail();

            // Verificar si tiene horarios asignados
            if ($grupoMateria->horarios()->exists()) {
                return redirect()->back()
                    ->with('error', 'No se puede remover la materia porque tiene horarios asignados.');
            }

            $grupoMateria->delete();

            // Registrar en bitácora
            $this->registrarBitacoraSegura('Remoción', 'Materia de Grupo', $grupo->id, $user->id, 
                "Removió materia {$siglaMateria} del grupo {$grupo->nombre}");

            DB::commit();

            return redirect()->route('admin.grupos.show', $grupo->id)
                ->with('success', 'Materia removida del grupo exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al remover la materia del grupo: ' . $e->getMessage());
        }
    }

    /**
     * Exportar grupos a Excel
     */
    public function export()
    {
        $this->checkAccess(); // ← AGREGAR ESTA LÍNEA
        
        $user = Auth::user();
        
        try {
            $grupos = DB::table('grupo as g')
                ->leftJoin('grupo_materia as gm', 'g.id', '=', 'gm.id_grupo')
                ->select(
                    'g.id',
                    'g.nombre',
                    'g.gestion',
                    DB::raw('COUNT(gm.id) as materias_count'),
                    'g.created_at'
                )
                ->groupBy('g.id', 'g.nombre', 'g.gestion', 'g.created_at')
                ->orderBy('g.nombre')
                ->get();

            if ($grupos->isEmpty()) {
                return redirect()->route('admin.grupos.index')
                    ->with('warning', 'No hay grupos registrados para exportar.');
            }

            $fileName = 'grupos-' . date('Y-m-d_H-i') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($grupos) {
                $file = fopen('php://output', 'w');
                
                // Agregar BOM para UTF-8 (importante para Excel)
                fwrite($file, "\xEF\xBB\xBF");
                
                // Encabezados con separación correcta
                $encabezados = [
                    'ID',
                    'NOMBRE DEL GRUPO', 
                    'GESTIÓN ACADÉMICA',
                    'CANTIDAD DE MATERIAS',
                    'FECHA DE CREACIÓN',
                    'ESTADO'
                ];
                fputcsv($file, $encabezados, ';'); // Usar punto y coma como separador
                
                // Datos de los grupos
                foreach ($grupos as $grupo) {
                    $fila = [
                        $grupo->id,
                        $grupo->nombre,
                        $grupo->gestion,
                        $grupo->materias_count,
                        $grupo->created_at ? date('d/m/Y H:i', strtotime($grupo->created_at)) : '',
                        'ACTIVO' // Estado por defecto
                    ];
                    fputcsv($file, $fila, ';'); // Usar punto y coma como separador
                }
                
                fclose($file);
            };

            $this->registrarBitacoraSegura('Exportación', 'Grupo', null, $user->id, 
                'Exportó listado de ' . $grupos->count() . ' grupos a Excel');

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            $this->registrarBitacoraSegura('Error', 'Grupo', null, $user->id, 
                'Error al exportar grupos: ' . $e->getMessage());
            
            return redirect()->route('admin.grupos.index')
                ->with('error', 'Error al exportar los grupos: ' . $e->getMessage());
        }
    }

    /**
     * Método seguro para registrar en bitácora
     */
    private function registrarBitacoraSegura($accion, $entidad, $entidad_id, $usuario_id, $detalles = null)
    {
        try {
            $entidad_id_segura = is_numeric($entidad_id) ? $entidad_id : null;
            
            $descripcion_completa = $detalles;
            
            BitacoraController::registrar(
                $accion,
                $entidad,
                $entidad_id_segura,
                $usuario_id,
                null,
                $descripcion_completa
            );
            
        } catch (\Exception $e) {
            try {
                DB::table('auditorias')->insert([
                    'id_users' => $usuario_id,
                    'accion' => $accion,
                    'entidad' => $entidad,
                    'entidad_id' => $entidad_id_segura,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'descripcion' => $descripcion_completa,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e2) {
                \Log::error('Error en bitácora: ' . $e2->getMessage());
            }
        }
    }

    /**
     * Helper para obtener cambios en actualizaciones
     */
    private function getCambios($antes, $despues)
    {
        $cambios = [];
        foreach ($antes as $key => $valor) {
            if (isset($despues[$key]) && $antes[$key] != $despues[$key]) {
                $cambios[] = "$key: {$antes[$key]} → {$despues[$key]}";
            }
        }
        return implode(', ', $cambios);
    }
}