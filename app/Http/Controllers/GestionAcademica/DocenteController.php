<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\User;
use App\Models\Materia;
use App\Models\Carrera;
use App\Models\Asistencia;
use App\Models\Aula;
use App\Models\GestionAcademica;
use App\Models\Grupo;
use App\Models\GrupoMateria;
use App\Models\GrupoMateriaHorario;
use App\Models\Horario;
use App\Http\Controllers\Administracion\BitacoraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DocenteController extends Controller
{
 public function index()
{
    // Verificar autenticación primero
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página.');
    }

    $user = auth()->user();
    
    if ($user->hasRole('admin')) {
        $docentes = Docente::with(['user', 'carreras'])->paginate(10);
    } else if ($user->hasRole('coordinador')) {
        // SOLUCIÓN CORREGIDA: No hay facultad_id en users, así que mostramos todos los docentes
        // o implementamos una lógica de filtrado alternativa si es necesaria
        
        $docentes = Docente::with(['user', 'carreras'])->paginate(10);
        
        // Si necesitas filtrar por alguna otra relación, podrías usar:
        // $docentes = Docente::whereHas('carreras', function($query) use ($user) {
        //     // Aquí pondrías la lógica de filtrado si existe relación con facultad
        // })->with(['user', 'carreras'])->paginate(10);
        
    } else {
        abort(403, 'No tienes permisos para ver esta página.');
    }

    // Registrar en bitácora
    BitacoraController::registrar('Consulta', 'Docente', null, $user->id, null, 'Consultó lista de docentes');
    
    return view('admin.docentes.index', compact('docentes'));
}
    public function create()
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

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
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'codigo' => 'required|string|max:20|unique:docente',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:15',
            'sueldo' => 'required|numeric|min:0',
            'fecha_contrato' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_contrato',
            'carreras' => 'nullable|array',
            'carreras.*' => 'exists:carrera,id'
        ]);

        try {
            DB::beginTransaction();

            // Crear usuario
            $user = User::create([
                'name' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make('password123'),
                'password_set' => false,
                'email_verified_at' => now(),
            ]);

            // Crear docente
            $docente = Docente::create([
                'codigo' => $request->codigo,
                'telefono' => $request->telefono,
                'sueldo' => $request->sueldo,
                'fecha_contrato' => $request->fecha_contrato,
                'fecha_final' => $request->fecha_final,
                'id_users' => $user->id
            ]);

            // Asignar carreras
            if ($request->has('carreras')) {
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

            // Registrar en bitácora
            BitacoraController::registrarCreacion('Docente', $docente->codigo, auth()->id(), "Docente {$request->nombre} creado con código {$request->codigo}");

            \Log::info('Docente creado:', [
                'user_id' => $user->id,
                'docente_codigo' => $docente->codigo,
                'email' => $user->email
            ]);

            return redirect()->route('docentes.index')
                ->with('success', 'Docente registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear docente:', ['error' => $e->getMessage()]);
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Docente', null, auth()->id(), null, "Error al crear docente: {$e->getMessage()}");
            
            return redirect()->back()
                ->with('error', 'Error al registrar el docente: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($codigo)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $docente = Docente::with(['user', 'carreras'])->findOrFail($codigo);
        
        // Registrar en bitácora
        BitacoraController::registrar('Consulta', 'Docente', $codigo, auth()->id(), null, "Consultó detalles del docente {$docente->user->name}");
        
        // Obtener materias del docente si existe la relación
        $materiasDelDocente = collect();
        
        return view('admin.docentes.show', compact('docente', 'materiasDelDocente'));
    }

    public function edit($codigo)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

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
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $docente = Docente::findOrFail($codigo);

        $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('docente')->ignore($docente->codigo, 'codigo')
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
            'fecha_final' => 'required|date|after:fecha_contrato',
            'carreras' => 'nullable|array',
            'carreras.*' => 'exists:carrera,id'
        ]);

        try {
            DB::beginTransaction();

            // Guardar datos antiguos para bitácora
            $datosAntiguos = [
                'codigo' => $docente->codigo,
                'nombre' => $docente->user->name,
                'email' => $docente->user->email,
                'telefono' => $docente->telefono,
                'sueldo' => $docente->sueldo,
                'fecha_contrato' => $docente->fecha_contrato,
                'fecha_final' => $docente->fecha_final
            ];

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
                'fecha_contrato' => $request->fecha_contrato,
                'fecha_final' => $request->fecha_final
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

            // Registrar en bitácora
            $detalles = "Docente actualizado: " . $this->generarDetallesCambios($datosAntiguos, $request->all());
            BitacoraController::registrarActualizacion('Docente', $docente->codigo, auth()->id(), $detalles);

            return redirect()->route('docentes.index')
                ->with('success', 'Docente actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Docente', $codigo, auth()->id(), null, "Error al actualizar docente: {$e->getMessage()}");
            
            return redirect()->back()
                ->with('error', 'Error al actualizar el docente: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($codigo)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar docentes.');
        }

        $docente = Docente::findOrFail($codigo);
        $nombreDocente = $docente->user->name;

        try {
            DB::beginTransaction();

            // Eliminar relaciones
            $docente->carreras()->detach();
            
            // Eliminar usuario
            $docente->user->delete();
            
            // Eliminar docente
            $docente->delete();

            DB::commit();

            // Registrar en bitácora
            BitacoraController::registrarEliminacion('Docente', $codigo, auth()->id(), "Docente {$nombreDocente} eliminado");

            return redirect()->route('docentes.index')
                ->with('success', 'Docente eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Docente', $codigo, auth()->id(), null, "Error al eliminar docente: {$e->getMessage()}");
            
            return redirect()->back()
                ->with('error', 'Error al eliminar el docente: ' . $e->getMessage());
        }
    }

    // =============================================
    // MÉTODOS PARA CARGA HORARIA
    // =============================================
   public function cargaHoraria($codigo)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $docente = Docente::with(['user', 'carreras'])->findOrFail($codigo);
    
    // Registrar en bitácora
    BitacoraController::registrar('Consulta', 'Carga Horaria', $codigo, auth()->id(), null, "Consultó carga horaria del docente {$docente->user->name}");
    
    // SOLUCIÓN CORREGIDA: Obtener grupos a través de grupo_materia_horario
    $gruposAsignados = GrupoMateria::whereHas('horarios', function($query) use ($docente) {
        $query->where('id_docente', $docente->codigo)
              ->where('estado_aula', 'ocupado');
    })
    ->with([
        'materia', 
        'grupo', 
        'gestion', 
        'horarios' => function($query) use ($docente) {
            $query->where('id_docente', $docente->codigo)
                  ->where('estado_aula', 'ocupado')
                  ->with(['horario', 'aula', 'asistencias']);
        }
    ])
    ->get();

    // Obtener TODOS los grupos existentes (para mostrar en la vista)
    $gruposExistentes = GrupoMateria::with(['materia', 'grupo', 'gestion'])
    ->get()
    ->groupBy(function($item) {
        return $item->materia->sigla . ' - ' . $item->grupo->nombre . ' - ' . $item->gestion->semestre;
    });

    // Calcular horas por materia y totales
    $cargaPorMateria = [];
    $totalHorasSemana = 0;

    foreach ($gruposAsignados as $grupoMateria) {
        $horasMateria = 0;
        
        foreach ($grupoMateria->horarios as $horarioAsignado) {
            if ($horarioAsignado->horario) {
                $horaInicio = Carbon::parse($horarioAsignado->horario->hora_inicio);
                $horaFin = Carbon::parse($horarioAsignado->horario->hora_fin);
                $horasMateria += $horaInicio->diffInHours($horaFin);
            }
        }

        $materiaKey = $grupoMateria->materia->sigla . '_' . $grupoMateria->id_gestion;
        
        if (!isset($cargaPorMateria[$materiaKey])) {
            $cargaPorMateria[$materiaKey] = [
                'materia' => $grupoMateria->materia,
                'gestion' => $grupoMateria->gestion,
                'horas_semana' => 0,
                'grupos' => []
            ];
        }
        
        $cargaPorMateria[$materiaKey]['horas_semana'] += $horasMateria;
        $cargaPorMateria[$materiaKey]['grupos'][] = $grupoMateria;
        $totalHorasSemana += $horasMateria;
    }

    // Obtener datos para formulario de asignación
    $aulas = Aula::all();
    $gestiones = GestionAcademica::all();
    $grupos = Grupo::all();
    $materias = Materia::all();

    return view('admin.docentes.carga-horaria', compact(
        'docente', 
        'cargaPorMateria',
        'totalHorasSemana',
        'aulas',
        'gestiones',
        'grupos',
        'materias',
        'gruposAsignados',
        'gruposExistentes'
    ));
}

    public function asignarGrupo(Request $request, $codigo)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $docente = Docente::findOrFail($codigo);

        $request->validate([
            'materia_sigla' => 'required|string|exists:materia,sigla',
            'id_gestion' => 'required|exists:gestion_academica,id',
            'id_grupo' => 'required|exists:grupo,id',
            'aula_id' => 'required|exists:aula,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.dia' => 'required|string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio'
        ]);

        try {
            DB::beginTransaction();

            // Verificar si ya existe el grupo
            $grupoMateriaExistente = GrupoMateria::where([
                'sigla_materia' => $request->materia_sigla,
                'id_grupo' => $request->id_grupo,
                'id_gestion' => $request->id_gestion
            ])->first();

            if ($grupoMateriaExistente) {
                $grupoMateria = $grupoMateriaExistente;
            } else {
                $grupoMateria = GrupoMateria::create([
                    'sigla_materia' => $request->materia_sigla,
                    'id_grupo' => $request->id_grupo,
                    'id_gestion' => $request->id_gestion
                ]);
            }

            // Verificar límite de horas (40 horas/semana)
            $horasTotales = $this->calcularHorasDocente($docente->codigo, $request->id_gestion);
            $nuevasHoras = 0;

            foreach ($request->horarios as $horarioData) {
                $horaInicio = Carbon::parse($horarioData['hora_inicio']);
                $horaFin = Carbon::parse($horarioData['hora_fin']);
                $nuevasHoras += $horaInicio->diffInHours($horaFin);
            }

            if ($horasTotales + $nuevasHoras > 40) {
                // Registrar intento fallido en bitácora
                BitacoraController::registrar('Error', 'Carga Horaria', $codigo, auth()->id(), null, "Intento de exceder límite de horas: {$horasTotales}h actuales + {$nuevasHoras}h nuevas");
                
                return redirect()->back()
                    ->with('error', "El docente excedería el límite de 40 horas/semana. Horas actuales: {$horasTotales}h, Nuevas horas: {$nuevasHoras}h")
                    ->withInput();
            }

            // Función para mapear días a formato de 3 letras
            $mapearDia = function($diaCompleto) {
                $diasMap = [
                    'Lunes' => 'LUN',
                    'Martes' => 'MAR',
                    'Miércoles' => 'MIE',
                    'Jueves' => 'JUE',
                    'Viernes' => 'VIE',
                    'Sábado' => 'SAB'
                ];
                return $diasMap[$diaCompleto] ?? 'LUN';
            };

            // Crear el PRIMER horario
            $primerHorarioData = $request->horarios[0];
            $horarioParaAsistencia = Horario::create([
                'dia' => $mapearDia($primerHorarioData['dia']),
                'hora_inicio' => $primerHorarioData['hora_inicio'],
                'hora_fin' => $primerHorarioData['hora_fin']
            ]);

            // CORREGIDO: Usar un estado válido ('presente', 'ausente' o 'justificado')
            Asistencia::create([
                'fecha' => now()->toDateString(),
                'hora_registro' => now()->toTimeString(),
                'estado' => 'presente',
                'codigo_docente' => $docente->codigo,
                'id_grupo_materia' => $grupoMateria->id,
                'id_horario' => $horarioParaAsistencia->id
            ]);

            // Asignar TODOS los horarios
            foreach ($request->horarios as $index => $horarioData) {
                if ($index === 0) {
                    GrupoMateriaHorario::create([
                        'id_grupo_materia' => $grupoMateria->id,
                        'id_horario' => $horarioParaAsistencia->id,
                        'id_aula' => $request->aula_id
                    ]);
                    continue;
                }
                
                // Para los demás horarios
                $horario = Horario::create([
                    'dia' => $mapearDia($horarioData['dia']),
                    'hora_inicio' => $horarioData['hora_inicio'],
                    'hora_fin' => $horarioData['hora_fin']
                ]);

                GrupoMateriaHorario::create([
                    'id_grupo_materia' => $grupoMateria->id,
                    'id_horario' => $horario->id,
                    'id_aula' => $request->aula_id
                ]);
            }

            DB::commit();

            // Registrar en bitácora
            $detallesHorarios = count($request->horarios) . " horarios asignados para {$request->materia_sigla}";
            BitacoraController::registrar('Asignación', 'Carga Horaria', $codigo, auth()->id(), null, "Grupo asignado al docente: {$detallesHorarios}");

            return redirect()->route('admin.docentes.carga-horaria', $docente->codigo)
                ->with('success', 'Grupo y horario asignados exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Carga Horaria', $codigo, auth()->id(), null, "Error al asignar grupo: {$e->getMessage()}");
            
            return redirect()->back()
                ->with('error', 'Error al asignar grupo y horario: ' . $e->getMessage())
                ->withInput();
        }
    }

    // MÉTODO ELIMINAR GRUPO FALTANTE
    public function eliminarGrupo($codigo, $grupoMateriaId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $docente = Docente::findOrFail($codigo);
        $grupoMateria = GrupoMateria::findOrFail($grupoMateriaId);

        try {
            DB::beginTransaction();

            // Eliminar asistencias relacionadas
            Asistencia::where('id_grupo_materia', $grupoMateria->id)
                     ->where('codigo_docente', $docente->codigo)
                     ->delete();

            // Eliminar relaciones en grupo_materia_horario
            GrupoMateriaHorario::where('id_grupo_materia', $grupoMateria->id)->delete();
            
            // Eliminar el grupo materia
            $grupoMateria->delete();

            DB::commit();

            // Registrar en bitácora
            BitacoraController::registrar('Eliminación', 'Carga Horaria', $codigo, auth()->id(), null, "Grupo eliminado de carga horaria: ID {$grupoMateriaId}");

            return redirect()->route('admin.docentes.carga-horaria', $docente->codigo)
                ->with('success', 'Grupo eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Registrar error en bitácora
            BitacoraController::registrar('Error', 'Carga Horaria', $codigo, auth()->id(), null, "Error al eliminar grupo: {$e->getMessage()}");
            
            return redirect()->back()
                ->with('error', 'Error al eliminar el grupo: ' . $e->getMessage());
        }
    }

    // MÉTODO AUXILIAR PARA CALCULAR HORAS
    private function calcularHorasDocente($docenteCodigo, $gestionId = null)
    {
        $query = GrupoMateria::whereHas('asistencias', function($query) use ($docenteCodigo) {
            $query->where('codigo_docente', $docenteCodigo);
        })->with(['horarios.horario']);

        if ($gestionId) {
            $query->where('id_gestion', $gestionId);
        }

        $gruposAsignados = $query->get();
        $totalHoras = 0;

        foreach ($gruposAsignados as $grupoMateria) {
            foreach ($grupoMateria->horarios as $horarioAsignado) {
                if ($horarioAsignado->horario) {
                    $horaInicio = Carbon::parse($horarioAsignado->horario->hora_inicio);
                    $horaFin = Carbon::parse($horarioAsignado->horario->hora_fin);
                    $totalHoras += $horaInicio->diffInHours($horaFin);
                }
            }
        }

        return $totalHoras;
    }

    // Dashboard de gestión docente
    public function dashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Estadísticas
        $totalDocentes = Docente::count();
        $docentesConCarga = Docente::whereHas('asistencias')->count();
        $docentesSinCarga = $totalDocentes - $docentesConCarga;
        $docentesActivos = Docente::where('fecha_final', '>=', now())->count();

        // Registrar en bitácora
        BitacoraController::registrar('Consulta', 'Dashboard Docentes', null, auth()->id(), null, 'Consultó dashboard de docentes');

        return view('admin.docentes.dashboard', compact(
            'totalDocentes',
            'docentesConCarga', 
            'docentesSinCarga',
            'docentesActivos'
        ));
    }

    // =============================================
    // MÉTODO PARA QUE EL DOCENTE VEA SU PROPIA CARGA HORARIA (SIN PARÁMETRO)
    // =============================================
 public function miCargaHoraria()
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Obtener el docente autenticado
    $user = auth()->user();
    $docente = $user->docente;

    if (!$docente) {
        return redirect()->route('dashboard')
            ->with('error', 'No se encontró información del docente.');
    }

    // Cargar relaciones necesarias
    $docente->load(['user', 'carreras']);

    // SOLUCIÓN CORREGIDA: Obtener grupos a través de grupo_materia_horario
    $gruposAsignados = GrupoMateria::whereHas('horarios', function($query) use ($docente) {
        $query->where('id_docente', $docente->codigo)
              ->where('estado_aula', 'ocupado');
    })
    ->with([
        'materia', 
        'grupo', 
        'gestion', 
        'horarios' => function($query) use ($docente) {
            $query->where('id_docente', $docente->codigo)
                  ->where('estado_aula', 'ocupado')
                  ->with(['horario', 'aula']);
        }
    ])
    ->get();

    // Calcular horas por materia y totales
    $cargaPorMateria = [];
    $totalHorasSemana = 0;

    foreach ($gruposAsignados as $grupoMateria) {
        $horasMateria = 0;
        
        foreach ($grupoMateria->horarios as $horarioAsignado) {
            if ($horarioAsignado->horario) {
                $horaInicio = Carbon::parse($horarioAsignado->horario->hora_inicio);
                $horaFin = Carbon::parse($horarioAsignado->horario->hora_fin);
                $horasMateria += $horaInicio->diffInHours($horaFin);
            }
        }

        $materiaKey = $grupoMateria->materia->sigla . '_' . $grupoMateria->id_gestion;
        
        if (!isset($cargaPorMateria[$materiaKey])) {
            $cargaPorMateria[$materiaKey] = [
                'materia' => $grupoMateria->materia,
                'gestion' => $grupoMateria->gestion,
                'horas_semana' => 0,
                'grupos' => []
            ];
        }
        
        $cargaPorMateria[$materiaKey]['horas_semana'] += $horasMateria;
        $cargaPorMateria[$materiaKey]['grupos'][] = $grupoMateria;
        $totalHorasSemana += $horasMateria;
    }

    // Registrar en bitácora
    BitacoraController::registrar('Consulta', 'Mi Carga Horaria', $docente->codigo, $user->id, null, "Consultó su propia carga horaria");

    return view('docente.carga-horaria', compact(
        'docente', 
        'cargaPorMateria',
        'totalHorasSemana',
        'gruposAsignados'
    ));
}

    // =============================================
    // MÉTODO PARA QUE EL DOCENTE VEA SU PERFIL
    // =============================================
    public function perfil()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $docente = $user->docente;

        if (!$docente) {
            return redirect()->route('dashboard')
                ->with('error', 'No se encontró información del docente.');
        }

        $docente->load(['user', 'carreras']);

        return view('docente.perfil', compact('docente'));
    }

public function cambiarPassword(Request $request)
{
    $user = Auth::user();
    
    $validator = Validator::make($request->all(), [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->with('error', 'Por favor corrige los errores en el formulario.');
    }

    // Verificar contraseña actual
    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->back()
            ->with('error', 'La contraseña actual es incorrecta.');
    }

    // Actualizar contraseña
    $user->password = Hash::make($request->new_password);
    $user->password_set = true;
    $user->save();

    // Registrar en bitácora si tienes este sistema
     BitacoraController::registrarActualizacion('Usuario', $user->id, $user->id, 'Cambió su contraseña');

    // Redirigir según el rol
    if ($user->hasRole('docente')) {
        return redirect()->route('docente.perfil')
            ->with('success', 'Contraseña actualizada exitosamente.');
    } else {
        return redirect()->route('dashboard')
            ->with('success', 'Contraseña actualizada exitosamente.');
    }
}

    // =============================================
    // MÉTODOS AUXILIARES PARA BITÁCORA
    // =============================================

    /**
     * Genera detalles de cambios para la bitácora
     */
    private function generarDetallesCambios($datosAntiguos, $datosNuevos)
    {
        $cambios = [];
        
        if ($datosAntiguos['codigo'] != $datosNuevos['codigo']) {
            $cambios[] = "Código: {$datosAntiguos['codigo']} → {$datosNuevos['codigo']}";
        }
        
        if ($datosAntiguos['nombre'] != $datosNuevos['nombre']) {
            $cambios[] = "Nombre: {$datosAntiguos['nombre']} → {$datosNuevos['nombre']}";
        }
        
        if ($datosAntiguos['email'] != $datosNuevos['email']) {
            $cambios[] = "Email: {$datosAntiguos['email']} → {$datosNuevos['email']}";
        }
        
        if ($datosAntiguos['telefono'] != $datosNuevos['telefono']) {
            $cambios[] = "Teléfono: {$datosAntiguos['telefono']} → {$datosNuevos['telefono']}";
        }
        
        if ($datosAntiguos['sueldo'] != $datosNuevos['sueldo']) {
            $cambios[] = "Sueldo: {$datosAntiguos['sueldo']} → {$datosNuevos['sueldo']}";
        }

        return empty($cambios) ? 'Sin cambios detectados' : implode(', ', $cambios);
    }

    /**
     * Método seguro para registrar en bitácora que maneja errores
     */
    private function registrarBitacoraSegura($accion, $entidad, $entidad_id = null, $usuario_id = null, $detalles = null)
    {
        try {
            BitacoraController::registrar($accion, $entidad, $entidad_id, $usuario_id, null, $detalles);
        } catch (\Exception $e) {
            \Log::error('Error al registrar en bitácora: ' . $e->getMessage());
        }
    }
}