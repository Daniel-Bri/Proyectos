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

class MateriaController extends Controller
{
    /**
     * Determinar la vista según el rol
     */
    private function getViewPath($viewName)
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return "admin.materias.{$viewName}";
        } elseif ($user->hasRole('coordinador')) {
            return "coordinador.materias.{$viewName}";
        } else {
            return "docente.materias.{$viewName}";
        }
    }

    /**
     * Verificar permisos de coordinador (modificado)
     */
    private function checkCoordinadorPermission($materia = null)
    {
        $user = Auth::user();
        
        if ($user->hasRole('coordinador')) {
            // Coordinadores pueden ver todas las materias (sin filtro por carrera)
            if ($materia) {
                // Lógica alternativa si es necesaria
            }
        }
    }

/**
 * Display a listing of the resource.
 */
public function index()
{
    $user = Auth::user();
    
    if ($user->hasRole('admin')) {
        $materias = Materia::with([
            'categoria:id,nombre',
            'grupoMaterias.grupo'
        ])->orderBy('sigla')->paginate(10);
        
    } elseif ($user->hasRole('coordinador')) {
        // Coordinador ve todas las materias (sin filtro por carrera)
        $materias = Materia::with([
            'categoria:id,nombre',
            'grupoMaterias.grupo'
        ])->orderBy('sigla')->paginate(10);
    } else {
        // PARA DOCENTE - CORREGIDO: usar id_docente
        $docente = $user->docente;
        
        if (!$docente) {
            $materias = collect();
            \Log::warning('Usuario no tiene perfil de docente', ['user_id' => $user->id]);
        } else {
            // CORREGIDO: Usar id_docente en lugar de codigo_docente
            $materias = Materia::with([
                'categoria:id,nombre',
                'grupoMaterias.grupo',
                'grupoMaterias.horarios.horario',
                'grupoMaterias.horarios.aula'
            ])
            ->whereHas('grupoMaterias.horarios', function($query) use ($docente) {
                $query->where('id_docente', $docente->codigo); // CORREGIDO AQUÍ
            })
            ->orderBy('sigla')
            ->get();

            // Debug
            \Log::info('Materias asignadas al docente', [
                'docente_codigo' => $docente->codigo,
                'materias_count' => $materias->count(),
                'materias_siglas' => $materias->pluck('sigla')->toArray()
            ]);
        }
    }
    
    // Registrar en bitácora - SEGURO
    $this->registrarBitacoraSegura('Consulta', 'Materia', null, $user->id, 'Consultó listado de materias');
    
    $view = $this->getViewPath('index');
    
    // Para docente, pasar el docente también a la vista
    if ($user->hasRole('docente')) {
        return view($view, compact('materias', 'docente'));
    }
    
    return view($view, compact('materias'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        $categorias = Categoria::all();
        
        $view = $this->getViewPath('create');
        return view($view, compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

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
                "Nueva materia: {$materia->sigla} - {$materia->nombre}");
            
            DB::commit();
            
            return redirect()->route('admin.materias.index')
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
     * Show the form for editing the specified resource.
     */
    public function edit($sigla)
    {
        $materia = Materia::findOrFail($sigla);
        
        // Verificar permisos de coordinador
        $this->checkCoordinadorPermission($materia);

        $categorias = Categoria::all();

        $view = $this->getViewPath('edit');
        return view($view, compact('materia', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sigla)
    {
        $materia = Materia::findOrFail($sigla);
        $user = Auth::user();
        
        // Verificar permisos de coordinador
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
                "Actualizó materia: {$materia->sigla}. Cambios: " . $this->getCambios($datosAntiguos, $materia->toArray()));
            
            DB::commit();
            
            return redirect()->route('admin.materias.index')
                ->with('success', 'Materia actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar la materia: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sigla)
    {
        $materia = Materia::findOrFail($sigla);
        $user = Auth::user();
        
        // Verificar permisos de coordinador
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
                "Eliminó materia: {$materiaData['sigla']} - {$materiaData['nombre']}");
            
            DB::commit();
            
            return redirect()->route('admin.materias.index')
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
                \Log::error('Error en bitácora: ' . $e2->getMessage());
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
        \Log::error('Error en getMateriasForDocente: ' . $e->getMessage());
        return collect();
    }
}
}