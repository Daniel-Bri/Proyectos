<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Categoria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GestionAcademica;
use App\Models\GrupoMateria;
use App\Models\GrupoMateriaHorario;
use App\Models\Horario;
use App\Models\Aula;
use App\Http\Controllers\Administracion\BitacoraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 

class MateriaController extends Controller
{
    /**
     * Determinar la vista según el rol - ACTUALIZADO
     */
    private function getViewPath($viewName)
    {
        $user = Auth::user();
        
        Log::info('getViewPath llamado', [
            'user_role' => $user->getRoleNames()->first(),
            'viewName' => $viewName,
            'expected_view' => ($user->hasRole(['admin', 'coordinador']) ? "admin.materias.{$viewName}" : 
                               "docente.materias.{$viewName}")
        ]);
        
        if ($user->hasRole(['admin', 'coordinador'])) {
            return "admin.materias.{$viewName}";
        } else {
            return "docente.materias.{$viewName}";
        }
    }

    /**
     * Obtener el prefijo de ruta según el rol - ACTUALIZADO
     */
    private function getRoutePrefix()
    {
        $user = Auth::user();
        
        if ($user->hasRole(['admin', 'coordinador'])) {
            return 'admin';
        } else {
            return 'docente';
        }
    }

    /**
     * Verificar permisos para coordinador - ACTUALIZADO
     */
    private function checkCoordinadorPermission($materia = null)
    {
        $user = Auth::user();
        
        // Si es coordinador, permitir acceso sin restricciones
        if ($user->hasRole('coordinador')) {
            return true; // Coordinador tiene acceso completo como admin
        }
        
        return true; // Admin siempre tiene acceso
    }

    /**
     * Aplicar filtros de búsqueda a la consulta - MÉTODO FALTANTE AÑADIDO
     */
    private function aplicarFiltrosBusqueda($query, $request)
    {
        // Aplicar filtro de búsqueda por texto
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('sigla', 'LIKE', "%{$search}%")
                  ->orWhere('nombre', 'LIKE', "%{$search}%")
                  ->orWhereHas('categoria', function($q) use ($search) {
                      $q->where('nombre', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Aplicar filtro por semestre
        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        // Aplicar filtro por estado
        if ($request->filled('estado')) {
            if ($request->estado == 'activa') {
                $query->has('grupoMaterias');
            } elseif ($request->estado == 'sin_grupos') {
                $query->doesntHave('grupoMaterias');
            }
        }
    }

    /**
     * Display a listing of the resource - ACTUALIZADO
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        Log::info('=== ACCESO A MATERIAS INDEX ===', [
            'user_id' => $user->id,
            'user_role' => $user->getRoleNames()->first(),
            'is_admin_or_coordinador' => $user->hasRole(['admin', 'coordinador'])
        ]);
        
        // Validar parámetros de búsqueda para admin y coordinador
        if ($user->hasRole(['admin', 'coordinador'])) {
            try {
                $validated = $request->validate([
                    'search' => 'nullable|string|max:100',
                    'semestre' => 'nullable|integer|between:1,10',
                    'estado' => 'nullable|in:activa,sin_grupos',
                    'page' => 'nullable|integer|min:1'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Error de validación en búsqueda', ['errors' => $e->errors()]);
                return redirect()->route($this->getRoutePrefix() . '.materias.index')
                    ->with('warning', 'Algunos parámetros de búsqueda no eran válidos.');
            }
        }
        
        if ($user->hasRole(['admin', 'coordinador'])) {
            $query = Materia::with([
                'categoria:id,nombre',
                'grupoMaterias.grupo'
            ]);

            // Aplicar filtros de búsqueda para admin y coordinador
            $this->aplicarFiltrosBusqueda($query, $request);
            
            $materias = $query->orderBy('sigla')->paginate(10);
            
            Log::info('RESULTADOS PARA ADMIN/COORDINADOR', [
                'total_materias' => $materias->total(),
                'materias_count' => $materias->count(),
                'user_role' => $user->getRoleNames()->first()
            ]);
            
        } else {
            // Código para docente...
            $docente = $user->docente;
            
            if (!$docente) {
                $materias = collect();
                Log::warning('Usuario no tiene perfil de docente', ['user_id' => $user->id]);
            } else {
                $materias = Materia::with([
                    'categoria:id,nombre',
                    'grupoMaterias.grupo',
                    'grupoMaterias.horarios.horario',
                    'grupoMaterias.horarios.aula'
                ])
                ->whereHas('grupoMaterias.horarios', function($query) use ($docente) {
                    $query->where('id_docente', $docente->codigo);
                })
                ->orderBy('sigla')
                ->get();
            }
        }
        
        // Registrar en bitácora
        $this->registrarBitacoraSegura('Consulta', 'Materia', null, $user->id, 
            'Consultó listado de materias como ' . $user->getRoleNames()->first());
        
        $view = $this->getViewPath('index');
        
        if ($user->hasRole('docente')) {
            return view($view, compact('materias', 'docente'));
        }
        
        Log::info('VISTA A RENDERIZAR', [
            'view' => $view,
            'materias_count' => $materias->count(),
            'user_role' => $user->getRoleNames()->first()
        ]);
        
        return view($view, compact('materias'));
    }

    /**
     * Show the form for creating a new resource - ACTUALIZADO
     */
    public function create()
    {
        $user = Auth::user();
        
        // Verificar permisos
        $this->checkCoordinadorPermission();
        
        $categorias = Categoria::all();
        
        $view = $this->getViewPath('create');
        return view($view, compact('categorias'));
    }

    /**
     * Store a newly created resource in storage - ACTUALIZADO
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Verificar permisos
        $this->checkCoordinadorPermission();

        $request->validate([
            'sigla' => 'required|unique:materia,sigla|max:10',
            'nombre' => 'required|max:255',
            'semestre' => 'required|integer',
            'id_categoria' => 'required|exists:categoria,id'
        ]);

        try {
            DB::beginTransaction();
            
            $materia = Materia::create($request->all());
            
            // Registrar en bitácora - SEGURO
            $this->registrarBitacoraSegura('Creación', 'Materia', $materia->sigla, $user->id, 
                "Nueva materia creada por " . $user->getRoleNames()->first() . ": {$materia->sigla} - {$materia->nombre}");
            
            DB::commit();
            
            return redirect()->route($this->getRoutePrefix() . '.materias.index')
                ->with('success', 'Materia creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear la materia: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($sigla)
    {
        try {
            $materia = Materia::with([
                'categoria', 
                'grupoMaterias.grupo',
                'grupoMaterias.gestion',
                'grupoMaterias.horarios.horario',
                'grupoMaterias.horarios.aula',
                'grupoMaterias.horarios.docente'
            ])->findOrFail($sigla);

            // Verificar permisos de coordinador
            $this->checkCoordinadorPermission($materia);

            $user = Auth::user();
            // Registrar en bitácora - SEGURO
            $this->registrarBitacoraSegura('Consulta', 'Materia', $materia->sigla, $user->id, 
                "Consultó detalles de materia: {$materia->sigla}");

            $view = $this->getViewPath('show');
            return view($view, compact('materia'));
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar la materia: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource - ACTUALIZADO
     */
    public function edit($sigla)
    {
        $materia = Materia::findOrFail($sigla);
        
        // Verificar permisos
        $this->checkCoordinadorPermission($materia);

        $categorias = Categoria::all();

        $view = $this->getViewPath('edit');
        return view($view, compact('materia', 'categorias'));
    }

    /**
     * Update the specified resource in storage - ACTUALIZADO
     */
    public function update(Request $request, $sigla)
    {
        $materia = Materia::findOrFail($sigla);
        $user = Auth::user();
        
        // Verificar permisos
        $this->checkCoordinadorPermission($materia);

        $request->validate([
            'sigla' => 'required|max:10|unique:materia,sigla,' . $materia->sigla . ',sigla',
            'nombre' => 'required|max:255',
            'semestre' => 'required|integer',
            'id_categoria' => 'required|exists:categoria,id'
        ]);

        try {
            DB::beginTransaction();
            
            // Guardar datos antiguos para bitácora
            $datosAntiguos = $materia->toArray();
            $materia->update($request->all());
            
            // Registrar en bitácora - SEGURO
            $this->registrarBitacoraSegura('Actualización', 'Materia', $materia->sigla, $user->id, 
                "Materia actualizada por " . $user->getRoleNames()->first() . ": {$materia->sigla}. Cambios: " . $this->getCambios($datosAntiguos, $materia->toArray()));
            
            DB::commit();
            
            return redirect()->route($this->getRoutePrefix() . '.materias.index')
                ->with('success', 'Materia actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar la materia: ' . $e->getMessage())
                ->withInput();
        }
    }

/**
 * Remove the specified resource from storage - ACTUALIZADO
 */
public function destroy($sigla)
{
    $materia = Materia::findOrFail($sigla);
    $user = Auth::user();
    
    // Verificar permisos - Coordinador no puede eliminar
    if ($user->hasRole('coordinador')) {
        return redirect()->back()
            ->with('error', 'No tiene permisos para eliminar materias.');
    }
    
    // Verificar permisos
    $this->checkCoordinadorPermission($materia);

    try {
        // Verificar si tiene grupos asignados
        if ($materia->grupoMaterias()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la materia porque tiene grupos asignados.');
        }
        
        DB::beginTransaction();
        
        $materiaData = $materia->toArray();
        $materia->delete();
        
        // Registrar en bitácora - SEGURO
        $this->registrarBitacoraSegura('Eliminación', 'Materia', $sigla, $user->id, 
            "Materia eliminada por " . $user->getRoleNames()->first() . ": {$materiaData['sigla']} - {$materiaData['nombre']}");
        
        DB::commit();
        
        return redirect()->route($this->getRoutePrefix() . '.materias.index')
            ->with('success', 'Materia eliminada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Error al eliminar la materia: ' . $e->getMessage());
    }
}

    /**
     * Exportar materias a Excel (CSV) - VERSIÓN FUNCIONAL
     */
    public function export()
    {
        $user = Auth::user();
        
        try {
            // CONSULTA DIRECTA A LA BASE DE DATOS
            $materias = DB::table('materia as m')
                ->leftJoin('categoria as c', 'm.id_categoria', '=', 'c.id')
                ->leftJoin('grupo_materia as gm', 'm.sigla', '=', 'gm.sigla_materia')
                ->select(
                    'm.sigla',
                    'm.nombre',
                    'm.semestre',
                    'c.nombre as categoria_nombre',
                    DB::raw('COUNT(gm.id) as grupos_count'),
                    'm.created_at'
                )
                ->groupBy('m.sigla', 'm.nombre', 'm.semestre', 'c.nombre', 'm.created_at')
                ->orderBy('m.sigla')
                ->get();

            // VERIFICAR SI HAY MATERIAS
            if ($materias->isEmpty()) {
                return redirect()->route('admin.materias.index')
                    ->with('warning', 'No hay materias registradas para exportar.');
            }

            // CREAR ARCHIVO CSV
            $fileName = 'materias-' . date('Y-m-d_H-i') . '.csv';
            
            // Encabezados CSV
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            // Función para generar CSV
            $callback = function() use ($materias) {
                $file = fopen('php://output', 'w');
                
                // BOM para Excel (opcional, mejora compatibilidad)
                fwrite($file, "\xEF\xBB\xBF");
                
                // Encabezados
                fputcsv($file, [
                    'SIGLA',
                    'NOMBRE', 
                    'SEMESTRE',
                    'CATEGORÍA',
                    'GRUPOS ASIGNADOS',
                    'ESTADO',
                    'FECHA CREACIÓN'
                ]);
                
                // Datos
                foreach ($materias as $materia) {
                    fputcsv($file, [
                        $materia->sigla,
                        $materia->nombre,
                        $materia->semestre,
                        $materia->categoria_nombre ?? 'N/A',
                        $materia->grupos_count,
                        $materia->grupos_count > 0 ? 'ACTIVA' : 'SIN GRUPOS',
                        $materia->created_at ? date('d/m/Y H:i', strtotime($materia->created_at)) : ''
                    ]);
                }
                
                fclose($file);
            };

            // REGISTRAR EN BITÁCORA - SEGURO
            $this->registrarBitacoraSegura('Exportación', 'Materia', null, $user->id, 
                'Exportó listado de ' . $materias->count() . ' materias a Excel');

            // RETORNAR DESCARGA
            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            // REGISTRAR ERROR - SEGURO
            $this->registrarBitacoraSegura('Error', 'Materia', null, $user->id, 
                'Error al exportar materias: ' . $e->getMessage());
            
            return redirect()->route('admin.materias.index')
                ->with('error', 'Error al exportar las materias: ' . $e->getMessage());
        }
    }

    /**
     * Método seguro para registrar en bitácora (usa tu BitacoraController pero de forma segura)
     */
    private function registrarBitacoraSegura($accion, $entidad, $entidad_id, $usuario_id, $detalles = null)
    {
        try {
            // Si el ID no es numérico, usar null para evitar errores con bigint
            $entidad_id_segura = is_numeric($entidad_id) ? $entidad_id : null;
            
            // Construir la descripción completa
            $descripcion_completa = $detalles;
            if ($entidad_id && !is_numeric($entidad_id)) {
                $descripcion_completa .= " (Sigla: {$entidad_id})";
            }
            
            // Usar tu BitacoraController existente pero de forma segura
            BitacoraController::registrar(
                $accion,
                $entidad,
                $entidad_id_segura, // ID segura (numérica o null)
                $usuario_id,
                null,
                $descripcion_completa // Usar 'descripcion' que es el campo correcto
            );
            
        } catch (\Exception $e) {
            // Si falla el BitacoraController, usar inserción directa segura
            try {
                DB::table('auditorias')->insert([
                    'id_users' => $usuario_id,
                    'accion' => $accion,
                    'entidad' => $entidad,
                    'entidad_id' => $entidad_id_segura,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'descripcion' => $descripcion_completa, // Campo CORRECTO
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e2) {
                // Solo log el error, no interrumpas el flujo principal
                Log::error('Error en bitácora: ' . $e2->getMessage());
            }
        }
    }

    /**
     * Mostrar formulario para asignar AULAS a grupos existentes de materia
     */
    public function asignarAulas($sigla)
    {
        $materia = Materia::with([
            'grupoMaterias.grupo', 
            'grupoMaterias.horarios.horario',
            'grupoMaterias.horarios.aula',
            'grupoMaterias.horarios.docente'
        ])->findOrFail($sigla);
        
        $user = Auth::user();
        
        // Verificar permisos de coordinador
        $this->checkCoordinadorPermission($materia);

        // CORREGIDO: Usar 'grupoMaterias' en lugar de 'grupoMateria'
        $gruposMateria = $materia->grupoMaterias;
        
        $aulas = Aula::all();
        $horarios = Horario::all();
        $docentes = Docente::all();

        $view = $this->getViewPath('asignar-aulas');
        return view($view, compact('materia', 'gruposMateria', 'aulas', 'horarios', 'docentes'));
    }

    /**
     * Procesar asignación de AULAS a grupos existentes
     */
    public function storeAsignarAulas(Request $request, $sigla)
    {
        $materia = Materia::findOrFail($sigla);
        $user = Auth::user();
        
        // Verificar permisos de coordinador
        $this->checkCoordinadorPermission($materia);

        $request->validate([
            'id_grupo_materia' => 'required|exists:grupo_materia,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.id_horario_grupo' => 'required|exists:grupo_materia_horario,id',
            'horarios.*.id_horario' => 'required|exists:horario,id',
            'horarios.*.id_aula' => 'required|exists:aula,id'
            // ELIMINADO: horarios.*.codigo_docente ya no es requerido
        ]);

        try {
            DB::beginTransaction();

            // Verificar que el grupo materia pertenece a esta materia
            $grupoMateria = GrupoMateria::where('id', $request->id_grupo_materia)
                ->where('sigla_materia', $sigla)
                ->firstOrFail();

            // VALIDAR AULAS OCUPADAS
            foreach ($request->horarios as $horarioData) {
                $aulaOcupada = GrupoMateriaHorario::where('id_aula', $horarioData['id_aula'])
                    ->where('id_horario', $horarioData['id_horario'])
                    ->where('id_grupo_materia', '!=', $grupoMateria->id) // Excluir el grupo actual
                    ->first();

                if ($aulaOcupada) {
                    $aula = Aula::find($horarioData['id_aula']);
                    $horario = Horario::find($horarioData['id_horario']);
                    
                    return redirect()->back()
                        ->with('error', "El aula {$aula->nombre} ya está ocupada en el horario {$horario->dia} {$horario->hora_inicio}-{$horario->hora_fin}")
                        ->withInput();
                }
            }

            // Actualizar SOLO el aula para cada horario existente
            foreach ($request->horarios as $horarioData) {
                GrupoMateriaHorario::where('id', $horarioData['id_horario_grupo'])
                    ->where('id_grupo_materia', $grupoMateria->id)
                    ->update([
                        'id_aula' => $horarioData['id_aula']
                        // Mantener el docente original, no actualizarlo
                    ]);
            }

            // Registrar en bitácora
            $this->registrarBitacoraSegura('Asignación', 'Aulas', $grupoMateria->id, $user->id, 
                "Asignó aulas al grupo {$grupoMateria->grupo->nombre} de materia {$materia->sigla}");

            DB::commit();

            return redirect()->route('admin.materias.show', $sigla)
                ->with('success', 'Aulas asignadas al grupo exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al asignar aulas al grupo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar horarios de una materia
     */
    public function horarios($sigla)
    {
        $materia = Materia::with([
            'grupoMaterias.horarios.horario',
            'grupoMaterias.horarios.aula',
            'grupoMaterias.horarios.docente',
            'grupoMaterias.grupo',
            'grupoMaterias.gestion'
        ])->findOrFail($sigla);

        // Verificar permisos de coordinador
        $this->checkCoordinadorPermission($materia);

        $user = Auth::user();
        // Registrar en bitácora - SEGURO
        $this->registrarBitacoraSegura('Consulta', 'Horarios', $materia->sigla, $user->id, 
            "Consultó horarios de materia: {$materia->sigla}");

        $view = $this->getViewPath('horarios');
        return view($view, compact('materia'));
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

    /**
     * Obtener materias para docente
     */
    private function getMateriasForDocente($user)
    {
        $docente = $user->docente;
        
        if (!$docente) {
            return collect();
        }

        try {
            // CORREGIDO: Usar id_docente
            return Materia::whereHas('grupoMaterias.horarios', function($query) use ($docente) {
                $query->where('id_docente', $docente->codigo); // CORREGIDO AQUÍ
            })
            ->with(['categoria:id,nombre'])
            ->orderBy('sigla')
            ->get();

        } catch (\Exception $e) {
            Log::error('Error en getMateriasForDocente: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener horarios para API
     */
    public function getHorarios()
    {
        try {
            $horarios = Horario::all();
            return response()->json($horarios);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar horarios'], 500);
        }
    }

    /**
     * Obtener aulas para API
     */
    public function getAulas()
    {
        try {
            $aulas = Aula::all();
            return response()->json($aulas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar aulas'], 500);
        }
    }

    /**
     * Mostrar formulario para asignar grupo a materia
     */
    public function asignarGrupo($sigla)
    {
        $materia = Materia::findOrFail($sigla);
        
        // Verificar permisos de coordinador
        $this->checkCoordinadorPermission($materia);

        $grupos = Grupo::all();
        $gestiones = GestionAcademica::all();
        $docentes = Docente::all();
        $horarios = Horario::all();
        $aulas = Aula::all();

        $view = $this->getViewPath('asignar-grupo');
        return view($view, compact('materia', 'grupos', 'gestiones', 'docentes', 'horarios', 'aulas'));
    }

    /**
     * Procesar asignación de grupo a materia
     */
    public function storeAsignarGrupo(Request $request, $sigla)
    {
        $materia = Materia::findOrFail($sigla);
        $user = Auth::user();
        
        // Verificar permisos de coordinador
        $this->checkCoordinadorPermission($materia);

        $request->validate([
            'id_grupo' => 'required|exists:grupo,id',
            'id_gestion' => 'required|exists:gestion_academica,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.id_horario' => 'required|exists:horario,id',
            'horarios.*.id_aula' => 'required|exists:aula,id',
            'horarios.*.codigo_docente' => 'required|exists:docente,codigo'
        ]);

        try {
            DB::beginTransaction();

            // Crear grupo_materia
            $grupoMateria = GrupoMateria::create([
                'sigla_materia' => $sigla,
                'id_grupo' => $request->id_grupo,
                'id_gestion' => $request->id_gestion
            ]);

            // Crear horarios para el grupo_materia
            foreach ($request->horarios as $horarioData) {
                GrupoMateriaHorario::create([
                    'id_grupo_materia' => $grupoMateria->id,
                    'id_horario' => $horarioData['id_horario'],
                    'id_aula' => $horarioData['id_aula'],
                    'codigo_docente' => $horarioData['codigo_docente']
                ]);
            }

            // Registrar en bitácora
            $this->registrarBitacoraSegura('Asignación', 'GrupoMateria', $grupoMateria->id, $user->id, 
                "Asignó grupo {$grupoMateria->grupo->nombre} a materia {$materia->sigla}");

            DB::commit();

            return redirect()->route($this->getRoutePrefix() . '.materias.show', $sigla)
                ->with('success', 'Grupo asignado a la materia exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al asignar grupo a la materia: ' . $e->getMessage())
                ->withInput();
        }
    }
}