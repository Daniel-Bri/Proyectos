<?php

namespace App\Http\Controllers\Asistencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupoMateriaHorario;
use App\Models\Asistencia;
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
        
        // Mapeo de días en español a formato de base de datos
        $diasMap = [
            'Monday' => 'LUN',
            'Tuesday' => 'MAR', 
            'Wednesday' => 'MIE',
            'Thursday' => 'JUE',
            'Friday' => 'VIE',
            'Saturday' => 'SAB',
            'Sunday' => 'DOM'
        ];
        
        $diaSemanaIngles = Carbon::now()->englishDayOfWeek;
        $diaSemana = $diasMap[$diaSemanaIngles] ?? 'LUN';

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
            ->whereHas('horario', function($query) use ($diaSemana) {
                $query->where('dia', $diaSemana);
            })
            ->where('id_docente', $docenteId)
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

        // Generar datos para el QR
        $qrData = $this->generarDatosQR($clase);
        
        return view('docente.asistencia.qr', compact('clase', 'qrData'));
    }

    /**
     * CU13 - Generar QR REAL funcional
     */
    public function generarQR($id)
    {
        try {
            \Log::info("=== GENERANDO QR REAL ===");
            
            // Validar acceso
            $docenteId = auth()->user()->docente->codigo;
            $clase = GrupoMateriaHorario::with(['grupoMateria.materia', 'grupoMateria.grupo'])
                ->where('id_docente', $docenteId)
                ->findOrFail($id);

            // Generar datos para el QR
            $qrData = $this->generarDatosQR($clase);
            
            \Log::info("Datos QR: " . $qrData);

            // GENERAR QR REAL COMO PNG
            $qrImage = QrCode::format('png')
                ->size(280)
                ->margin(2)
                ->errorCorrection('H')
                ->backgroundColor(255, 255, 255)
                ->color(2, 103, 115)
                ->generate($qrData);

            \Log::info("✅ QR PNG generado exitosamente");

            return response($qrImage)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            \Log::error("ERROR generando QR PNG: " . $e->getMessage());
            
            // Fallback a SVG
            return $this->generarQRSVG($id);
        }
    }

    /**
     * Generar datos para el QR
     */
    private function generarDatosQR($clase)
    {
        // Crear token único
        $token = Str::random(16);
        session(['qr_token_' . $clase->id => $token]);
        
        // Crear URL de validación
        $validationUrl = route('docente.asistencia.qr.validar', [
            't' => $token,
            'c' => $clase->id,
            'ts' => time()
        ]);
        
        return $validationUrl;
    }

    /**
     * Generar QR como SVG (fallback)
     */
    private function generarQRSVG($id)
    {
        try {
            $clase = GrupoMateriaHorario::with(['grupoMateria.materia', 'grupoMateria.grupo'])->find($id);
            $qrData = $this->generarDatosQR($clase);
            
            // GENERAR QR REAL COMO SVG
            $qrImage = QrCode::format('svg')
                ->size(280)
                ->margin(2)
                ->errorCorrection('H')
                ->backgroundColor(255, 255, 255)
                ->color(2, 103, 115)
                ->generate($qrData);

            return response($qrImage)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');

        } catch (\Exception $e) {
            \Log::error("ERROR en QR SVG: " . $e->getMessage());
            
            // Último recurso
            return $this->generarQRMinimo($id);
        }
    }

    /**
     * QR mínimo de emergencia
     */
    private function generarQRMinimo($id)
    {
        $clase = GrupoMateriaHorario::find($id);
        $token = Str::random(8);
        session(['qr_token_' . $clase->id => $token]);
        
        // Datos mínimos
        $qrData = "ASIST-{$clase->id}-{$token}";
        
        $qrImage = QrCode::format('svg')
            ->size(250)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($qrData);

        return response($qrImage)
            ->header('Content-Type', 'image/svg+xml');
    }

    /**
     * CU13 - Validar QR escaneado
     */
    public function validarQR(Request $request)
    {
        $request->validate([
            't' => 'required|string', // token
            'c' => 'required|exists:grupo_materia_horario,id' // clase_id
        ]);

        try {
            $clase = $this->validarAccesoClase($request->c);
            $sessionToken = session('qr_token_' . $clase->id);
            
            if ($sessionToken !== $request->t) {
                return response()->json([
                    'success' => false,
                    'error' => 'Token QR inválido o expirado'
                ], 400);
            }

            // Limpiar token usado
            session()->forget('qr_token_' . $clase->id);
            
            // Registrar asistencia
            $this->registrarAsistenciaDirecta($clase);
            
            return response()->json([
                'success' => true,
                'message' => '✅ Asistencia registrada correctamente',
                'clase' => $clase->grupoMateria->materia->nombre,
                'grupo' => $clase->grupoMateria->grupo->nombre
            ]);

        } catch (\Exception $e) {
            \Log::error("Error validando QR: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al validar QR: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de confirmación de asistencia
     */
    public function confirmacion($id)
    {
        $clase = $this->validarAccesoClase($id);
        return view('docente.asistencia.confirmacion', compact('clase'));
    }

    /**
     * Registrar asistencia directamente
     */
    private function registrarAsistenciaDirecta($clase)
    {
        Asistencia::create([
            'fecha' => Carbon::now()->format('Y-m-d'),
            'hora_registro' => Carbon::now()->format('H:i:s'),
            'estado' => $this->determinarEstadoAsistencia($clase),
            'id_grupo_materia_horario' => $clase->id,
        ]);
    }

    /**
     * Validar acceso a la clase
     */
    private function validarAccesoClase($claseId)
    {
        $docenteId = auth()->user()->docente->codigo;
        
        $clase = GrupoMateriaHorario::with(['grupoMateria.materia', 'grupoMateria.grupo', 'horario', 'aula'])
            ->where('id_docente', $docenteId)
            ->findOrFail($claseId);

        // Mapeo de días para validación
        $diasMap = [
            'Monday' => 'LUN',
            'Tuesday' => 'MAR',
            'Wednesday' => 'MIE', 
            'Thursday' => 'JUE',
            'Friday' => 'VIE',
            'Saturday' => 'SAB',
            'Sunday' => 'DOM'
        ];
        
        $diaSemanaIngles = Carbon::now()->englishDayOfWeek;
        $diaHoy = $diasMap[$diaSemanaIngles] ?? 'LUN';

        // Verificar que la clase es de hoy
        if ($clase->horario->dia !== $diaHoy) {
            abort(403, 'Esta clase no corresponde al día de hoy.');
        }

        return $clase;
    }

    /**
     * Obtener estado de la clase
     */
    private function getEstadoClase($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i:s', $clase->horario->hora_fin);

        if ($horaActual->between($horaInicio, $horaFin)) {
            return 'en_curso';
        } elseif ($horaActual->lt($horaInicio)) {
            return $horaInicio->diffInMinutes($horaActual) <= 30 ? 'proximo' : 'disponible';
        } else {
            return 'pasado';
        }
    }

    /**
     * Obtener tiempo restante
     */
    private function getTiempoRestante($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);

        if ($horaActual->lt($horaInicio)) {
            $diff = $horaActual->diff($horaInicio);
            return "En {$diff->h}h {$diff->i}m";
        } elseif ($this->getEstadoClase($clase) === 'en_curso') {
            $horaFin = Carbon::createFromFormat('H:i:s', $clase->horario->hora_fin);
            $diff = $horaActual->diff($horaFin);
            return "Termina en {$diff->h}h {$diff->i}m";
        } else {
            return "Finalizada";
        }
    }

    /**
     * Verificar si ya tiene asistencia registrada
     */
    private function tieneAsistenciaRegistrada($claseId)
    {
        return Asistencia::where('id_grupo_materia_horario', $claseId)
            ->whereDate('fecha', Carbon::today())
            ->exists();
    }

    /**
     * Registrar asistencia
     */
    private function registrarAsistencia($clase, $metodo)
    {
        try {
            // Verificar horario válido
            if (!$this->esHorarioValido($clase)) {
                return redirect()->back()
                    ->with('error', 'Fuera del horario permitido para marcar asistencia.');
            }

            // Registrar asistencia
            Asistencia::create([
                'fecha' => Carbon::now()->format('Y-m-d'),
                'hora_registro' => Carbon::now()->format('H:i:s'),
                'estado' => $this->determinarEstadoAsistencia($clase),
                'id_grupo_materia_horario' => $clase->id,
            ]);

            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with('success', 'Asistencia registrada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si es horario válido
     */
    private function esHorarioValido($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i:s', $clase->horario->hora_fin);

        $margen = 15;
        $horaInicioPermitido = $horaInicio->copy()->subMinutes($margen);
        $horaFinPermitido = $horaFin->copy()->addMinutes($margen);

        return $horaActual->between($horaInicioPermitido, $horaFinPermitido);
    }

    /**
     * Determinar estado de asistencia
     */
    private function determinarEstadoAsistencia($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);

        return $horaActual->diffInMinutes($horaInicio) <= 10 ? 'presente' : 'tardanza';
    }
}