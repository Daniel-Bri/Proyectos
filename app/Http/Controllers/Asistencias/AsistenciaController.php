<?php

namespace App\Http\Controllers\Asistencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupoMateriaHorario;
use App\Models\Asistencia;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AsistenciaController extends Controller
{
    /**
     * CU12 & CU13 - Vista principal con lista de clases
     */
    public function index()
    {
        $docenteId = auth()->user()->docente->codigo;
        $hoy = Carbon::now()->format('Y-m-d');
        $diaSemana = Carbon::now()->isoFormat('ddd');
        
        // Obtener clases del docente para hoy
        $clases = GrupoMateriaHorario::with([
                'grupoMateria.materia',
                'grupoMateria.grupo', 
                'horario',
                'aula',
                'asistencias' => function($query) use ($hoy) {
                    $query->whereDate('fecha', $hoy);
                }
            ])
            ->porDocente($docenteId)
            ->whereHas('horario', function($query) use ($diaSemana) {
                $query->where('dia', strtoupper(substr($diaSemana, 0, 3)));
            })
            ->get()
            ->map(function($clase) {
                $clase->estado_clase = $this->getEstadoClase($clase);
                $clase->tiempo_restante = $this->getTiempoRestante($clase);
                $clase->asistencia_registrada = $clase->asistencias->isNotEmpty();
                return $clase;
            })
            ->sortBy(function($clase) {
                return $clase->horario->hora_inicio;
            });

        return view('docente.asistencia.index', compact('clases'));
    }

    /**
     * CU12 - Mostrar formulario de código temporal
     */
    public function mostrarCodigo($id)
    {
        $clase = $this->validarAccesoClase($id);
        
        // Verificar si ya tiene asistencia registrada hoy
        if ($this->tieneAsistenciaRegistrada($clase->id)) {
            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with('info', 'Ya tienes asistencia registrada para esta clase.');
        }

        // Generar código temporal
        $codigo = $this->generarCodigoTemporal($clase->id);
        
        return view('docente.asistencia.codigo', compact('clase', 'codigo'));
    }

    /**
     * CU12 - Validar código temporal
     */
    public function validarCodigo(Request $request)
    {
        $request->validate([
            'clase_id' => 'required|exists:grupo_materia_horario,id',
            'codigo' => 'required|string|size:6'
        ]);

        $clase = $this->validarAccesoClase($request->clase_id);
        
        // Aquí iría la lógica de validación del código en la base de datos
        // Por ahora, simulamos que siempre es válido
        
        return $this->registrarAsistencia($clase, 'codigo');
    }

    /**
     * CU13 - Mostrar página de QR
     */
    public function mostrarQR($id)
    {
        $clase = $this->validarAccesoClase($id);
        
        // Verificar si ya tiene asistencia registrada hoy
        if ($this->tieneAsistenciaRegistrada($clase->id)) {
            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with('info', 'Ya tienes asistencia registrada para esta clase.');
        }

        // Generar token único para QR
        $token = Str::random(32);
        session(['qr_token_' . $clase->id => $token]);
        
        $qrUrl = route('docente.asistencia.qr.validar', [
            'token' => $token,
            'clase_id' => $clase->id
        ]);

        return view('docente.asistencia.qr', compact('clase', 'qrUrl', 'token'));
    }

    /**
     * CU13 - Generar imagen QR
     */
    public function generarQR($id)
    {
        $clase = $this->validarAccesoClase($id);
        $token = session('qr_token_' . $clase->id);
        
        if (!$token) {
            abort(404);
        }

        $qrUrl = route('docente.asistencia.qr.validar', [
            'token' => $token,
            'clase_id' => $clase->id
        ]);

        // Generar QR code
        return QrCode::size(300)->generate($qrUrl);
    }

    /**
     * CU13 - Validar QR escaneado
     */
    public function validarQR(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'clase_id' => 'required|exists:grupo_materia_horario,id'
        ]);

        $clase = $this->validarAccesoClase($request->clase_id);
        $sessionToken = session('qr_token_' . $clase->id);
        
        if ($sessionToken !== $request->token) {
            return response()->json(['error' => 'Token QR inválido'], 400);
        }

        // Limpiar token usado
        session()->forget('qr_token_' . $clase->id);
        
        return $this->registrarAsistencia($clase, 'qr');
    }

    /**
     * Registrar asistencia común
     */
    private function registrarAsistencia($clase, $metodo)
    {
        try {
            // Verificar horario válido (15 min antes - 15 min después)
            if (!$this->esHorarioValido($clase)) {
                return redirect()->back()
                    ->with('error', 'Fuera del horario permitido para marcar asistencia.');
            }

            // Registrar asistencia
            Asistencia::create([
                'fecha' => Carbon::now()->format('Y-m-d'),
                'hora_registro' => Carbon::now()->format('H:i:s'),
                'estado' => $this->determinarEstadoAsistencia($clase),
                'metodo' => $metodo,
                'id_grupo_materia_horario' => $clase->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Registrar en bitácora
            \App\Http\Controllers\Administracion\BitacoraController::registrar(
                'Registro de asistencia',
                'Asistencia',
                $clase->id,
                auth()->id(),
                request(),
                "Asistencia registrada via $metodo para " . $clase->grupoMateria->materia->nombre
            );

            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with('success', 'Asistencia registrada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Página de confirmación
     */
    public function confirmacion($id)
    {
        $clase = $this->validarAccesoClase($id);
        $asistencia = Asistencia::where('id_grupo_materia_horario', $clase->id)
            ->whereDate('fecha', Carbon::today())
            ->first();

        return view('docente.asistencia.confirmacion', compact('clase', 'asistencia'));
    }

    /**
     * Historial de asistencias
     */
    public function historial()
    {
        $docenteId = auth()->user()->docente->codigo;
        
        $asistencias = Asistencia::with(['grupoMateriaHorario.grupoMateria.materia', 'grupoMateriaHorario.horario'])
            ->whereHas('grupoMateriaHorario', function($query) use ($docenteId) {
                $query->where('id_docente', $docenteId);
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_registro', 'desc')
            ->paginate(20);

        return view('docente.asistencia.historial', compact('asistencias'));
    }

    /**
     * ========== MÉTODOS PRIVADOS DE APOYO ==========
     */

    private function validarAccesoClase($claseId)
    {
        $docenteId = auth()->user()->docente->codigo;
        
        $clase = GrupoMateriaHorario::with(['grupoMateria.materia', 'grupoMateria.grupo', 'horario', 'aula'])
            ->porDocente($docenteId)
            ->findOrFail($claseId);

        // Verificar que la clase es de hoy
        $diaSemana = Carbon::now()->isoFormat('ddd');
        if ($clase->horario->dia !== strtoupper(substr($diaSemana, 0, 3))) {
            abort(403, 'Esta clase no corresponde al día de hoy.');
        }

        return $clase;
    }

    private function getEstadoClase($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i', $clase->horario->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $clase->horario->hora_fin);

        if ($horaActual->between($horaInicio, $horaFin)) {
            return 'en_curso';
        } elseif ($horaActual->lt($horaInicio)) {
            return $horaInicio->diffInMinutes($horaActual) <= 30 ? 'proximo' : 'disponible';
        } else {
            return 'pasado';
        }
    }

    private function getTiempoRestante($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i', $clase->horario->hora_inicio);

        if ($horaActual->lt($horaInicio)) {
            $diff = $horaActual->diff($horaInicio);
            return "En {$diff->h}h {$diff->i}m";
        } elseif ($this->getEstadoClase($clase) === 'en_curso') {
            $horaFin = Carbon::createFromFormat('H:i', $clase->horario->hora_fin);
            $diff = $horaActual->diff($horaFin);
            return "Termina en {$diff->h}h {$diff->i}m";
        } else {
            return "Finalizada";
        }
    }

    private function tieneAsistenciaRegistrada($claseId)
    {
        return Asistencia::where('id_grupo_materia_horario', $claseId)
            ->whereDate('fecha', Carbon::today())
            ->exists();
    }

    private function generarCodigoTemporal($claseId)
    {
        // Generar código alfanumérico de 6 caracteres
        $codigo = strtoupper(Str::random(6));
        
        // Aquí guardarías el código en la base de datos con expiración
        // Por ahora lo retornamos directamente
        return $codigo;
    }

    private function esHorarioValido($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i', $clase->horario->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $clase->horario->hora_fin);

        // Permitir 15 minutos antes y 15 minutos después
        $margen = 15;
        $horaInicioPermitido = $horaInicio->copy()->subMinutes($margen);
        $horaFinPermitido = $horaFin->copy()->addMinutes($margen);

        return $horaActual->between($horaInicioPermitido, $horaFinPermitido);
    }

    private function determinarEstadoAsistencia($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i', $clase->horario->hora_inicio);

        // Si llega hasta 10 minutos después es "presente", después es "tardanza"
        return $horaActual->diffInMinutes($horaInicio) <= 10 ? 'presente' : 'tardanza';
    }
}