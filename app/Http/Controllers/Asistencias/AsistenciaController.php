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
                $clase->permite_asistencia = $this->permiteRegistrarAsistencia($clase);
                
                // Registrar automáticamente como ausente si la clase ya finalizó y no tiene asistencia
                if ($this->debeRegistrarAusente($clase)) {
                    $this->registrarAusenteAutomatico($clase);
                    $clase->asistencia_registrada = true;
                    $clase->estado_asistencia = 'ausente';
                } else {
                    $clase->estado_asistencia = $clase->asistencias->first()->estado ?? null;
                }
                
                return $clase;
            })
            ->sortBy(function($clase) {
                return $clase->horario->hora_inicio;
            });

        return view('docente.asistencia.index', compact('clases'));
    }

    /**
     * Verificar si permite registrar asistencia (máximo 45 minutos después del inicio)
     */
    private function permiteRegistrarAsistencia($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);
        
        // Permitir máximo 45 minutos después de la hora de inicio
        $limiteTiempo = $horaInicio->copy()->addMinutes(45);
        
        return $horaActual->lte($limiteTiempo) && !$this->tieneAsistenciaRegistrada($clase->id);
    }

    /**
     * Verificar si debe registrar automáticamente como ausente
     */
    private function debeRegistrarAusente($clase)
    {
        // Solo registrar como ausente si no tiene asistencia y la clase ya finalizó + 45 minutos
        if ($this->tieneAsistenciaRegistrada($clase->id)) {
            return false;
        }

        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);
        $limiteAusente = $horaInicio->copy()->addMinutes(45);
        
        return $horaActual->gt($limiteAusente);
    }

    /**
     * Registrar automáticamente como ausente
     */
    private function registrarAusenteAutomatico($clase)
    {
        // Verificar que no exista ya una asistencia para hoy
        if ($this->tieneAsistenciaRegistrada($clase->id)) {
            return;
        }

        Asistencia::create([
            'fecha' => Carbon::now()->format('Y-m-d'),
            'hora_registro' => Carbon::now()->format('H:i:s'),
            'estado' => 'ausente',
            'id_grupo_materia_horario' => $clase->id,
        ]);
    }

    /**
     * CU13 - Mostrar página de QR (con validación de tiempo)
     */
    public function mostrarQR($id)
    {
        $clase = $this->validarAccesoClase($id);
        
        // Verificar si ya tiene asistencia registrada hoy
        if ($this->tieneAsistenciaRegistrada($clase->id)) {
            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with('info', 'Ya tienes asistencia registrada para esta clase.');
        }

        // Verificar si permite registrar asistencia (tiempo límite)
        if (!$this->permiteRegistrarAsistencia($clase)) {
            return redirect()->route('docente.asistencia.index')
                ->with('error', 'El tiempo para registrar asistencia ha expirado. La asistencia se ha registrado automáticamente como ausente.');
        }

        return view('docente.asistencia.qr', compact('clase'));
    }

    /**
     * Mostrar código alternativo (con validación de tiempo)
     */
    public function mostrarCodigo($id)
    {
        $clase = $this->validarAccesoClase($id);
        
        // Verificar si ya tiene asistencia registrada hoy
        if ($this->tieneAsistenciaRegistrada($clase->id)) {
            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with('info', 'Ya tienes asistencia registrada para esta clase.');
        }

        // Verificar si permite registrar asistencia (tiempo límite)
        if (!$this->permiteRegistrarAsistencia($clase)) {
            return redirect()->route('docente.asistencia.index')
                ->with('error', 'El tiempo para registrar asistencia ha expirado. La asistencia se ha registrado automáticamente como ausente.');
        }

        // Generar código temporal SOLO con letras mayúsculas
        $codigo = $this->generarCodigoTemporal($clase->id);
        
        return view('docente.asistencia.codigo', compact('clase', 'codigo'));
    }

    /**
     * CU13 - Validar QR escaneado (con validación de tiempo)
     */
    public function validarQR(Request $request)
    {
        // Obtener parámetros decodificando posibles entidades HTML
        $token = $request->input('token');
        $claseId = $request->input('clase');
        
        // Si clase viene null, buscar parámetros alternativos (por el amp;)
        if (empty($claseId)) {
            $allParams = $request->all();
            
            // Buscar parámetro con amp; (como amp%3Bclase)
            foreach ($allParams as $key => $value) {
                if (str_contains($key, 'clase') || str_contains($key, 'amp')) {
                    $claseId = $value;
                    break;
                }
            }
            
            // También intentar desde la URL directamente
            if (empty($claseId)) {
                $url = $request->fullUrl();
                if (preg_match('/[&?](?:amp%3B)?clase=(\d+)/', $url, $matches)) {
                    $claseId = $matches[1];
                }
            }
        }

        // Verificar que los parámetros existen
        if (empty($token) || empty($claseId)) {
            return redirect()->route('docente.asistencia.index')
                ->with('error', 'QR inválido. Faltan parámetros.');
        }

        try {
            $clase = $this->validarAccesoClase($claseId);
            
            // Verificar si permite registrar asistencia (tiempo límite)
            if (!$this->permiteRegistrarAsistencia($clase)) {
                return redirect()->route('docente.asistencia.index')
                    ->with('error', 'El tiempo para registrar asistencia ha expirado. La asistencia se ha registrado automáticamente como ausente.');
            }

            $sessionToken = session('qr_token_' . $clase->id);
            
            if (empty($sessionToken)) {
                return redirect()->route('docente.asistencia.qr', $clase->id)
                    ->with('error', 'QR no generado correctamente. Por favor, genera un nuevo código.');
            }
            
            if ($sessionToken !== $token) {
                return redirect()->route('docente.asistencia.qr', $clase->id)
                    ->with('error', 'QR expirado. Por favor, genera un nuevo código.');
            }

            // Limpiar token usado
            session()->forget('qr_token_' . $clase->id);
            
            // Verificar si ya tiene asistencia registrada
            if ($this->tieneAsistenciaRegistrada($clase->id)) {
                return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                    ->with('info', 'Ya tienes asistencia registrada para esta clase.');
            }

            // Registrar asistencia
            $this->registrarAsistenciaDirecta($clase);
            
            // Redirigir DIRECTAMENTE a confirmación
            return redirect()->route('docente.asistencia.confirmacion', $clase->id)
                ->with([
                    'success' => '✅ Asistencia registrada correctamente via QR',
                    'clase_nombre' => $clase->grupoMateria->materia->nombre,
                    'grupo_nombre' => $clase->grupoMateria->grupo->nombre
                ]);

        } catch (\Exception $e) {
            return redirect()->route('docente.asistencia.index')
                ->with('error', 'Error al procesar QR: ' . $e->getMessage());
        }
    }

    /**
     * Validar código manual (con validación de tiempo)
     */
    public function validarCodigo(Request $request)
    {
        $request->validate([
            'clase_id' => 'required|exists:grupo_materia_horario,id',
            'codigo' => 'required|string|size:6',
            'codigo_confirmacion' => 'required|string|size:6'
        ]);

        $clase = $this->validarAccesoClase($request->clase_id);
        
        // Verificar si permite registrar asistencia (tiempo límite)
        if (!$this->permiteRegistrarAsistencia($clase)) {
            return redirect()->route('docente.asistencia.index')
                ->with('error', 'El tiempo para registrar asistencia ha expirado. La asistencia se ha registrado automáticamente como ausente.');
        }
        
        // Validación CASE-INSENSITIVE
        $codigoOriginal = strtoupper($request->codigo);
        $codigoConfirmacion = strtoupper($request->codigo_confirmacion);
        
        if ($codigoConfirmacion !== $codigoOriginal) {
            return redirect()->back()
                ->with('error', 'El código de confirmación no coincide. Por favor, verifica.')
                ->withInput();
        }
        
        return $this->registrarAsistencia($clase, 'codigo');
    }

    /**
     * Página de confirmación de asistencia
     */
    public function confirmacion($id)
    {
        $clase = $this->validarAccesoClase($id);
        
        // Obtener la última asistencia de hoy para esta clase
        $asistencia = Asistencia::where('id_grupo_materia_horario', $clase->id)
            ->whereDate('fecha', Carbon::today())
            ->latest()
            ->first();

        return view('docente.asistencia.confirmacion', compact('clase', 'asistencia'));
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
     * Obtener estado de la clase (actualizado)
     */
    private function getEstadoClase($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i:s', $clase->horario->hora_fin);

        // Límite para registrar asistencia (45 minutos después del inicio)
        $limiteAsistencia = $horaInicio->copy()->addMinutes(45);

        if ($horaActual->between($horaInicio, $horaFin)) {
            return 'en_curso';
        } elseif ($horaActual->lt($horaInicio)) {
            return $horaInicio->diffInMinutes($horaActual) <= 30 ? 'proximo' : 'disponible';
        } elseif ($horaActual->between($horaFin, $limiteAsistencia)) {
            return 'finalizada';
        } else {
            return 'expirada';
        }
    }

    /**
     * Obtener tiempo restante
     */
    private function getTiempoRestante($clase)
    {
        $horaActual = Carbon::now();
        $horaInicio = Carbon::createFromFormat('H:i:s', $clase->horario->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i:s', $clase->horario->hora_fin);

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

    /**
     * Generar datos para el QR
     */
    private function generarDatosQR($clase)
    {
        // Crear token único
        $token = Str::random(16);
        session(['qr_token_' . $clase->id => $token]);
        
        // Generar URL directa
        $validationUrl = url('/docente/asistencia/validar-qr-escaneado') . '?token=' . $token . '&clase=' . $clase->id;
        
        return $validationUrl;
    }

    /**
     * Generar QR básico de emergencia
     */
    private function generarQRBasico($id)
    {
        try {
            $clase = GrupoMateriaHorario::find($id);
            $token = Str::random(8);
            session(['qr_token_' . $clase->id => $token]);
            
            // Datos básicos para el QR
            $qrData = "ASIST-{$clase->id}-{$token}";
            
            $qrImage = QrCode::format('svg')
                ->size(250)
                ->margin(1)
                ->errorCorrection('M')
                ->generate($qrData);

            return response($qrImage)
                ->header('Content-Type', 'image/svg+xml');

        } catch (\Exception $e) {
            // Último recurso - QR con texto simple
            $texto = "Clase: " . ($clase->grupoMateria->materia->nombre ?? 'N/A');
            $qrImage = QrCode::format('svg')
                ->size(200)
                ->generate($texto);
                
            return response($qrImage)
                ->header('Content-Type', 'image/svg+xml');
        }
    }

    /**
     * CU13 - Generar QR REAL funcional
     */
    public function generarQR($id)
    {
        try {
            // Validar acceso
            $docenteId = auth()->user()->docente->codigo;
            $clase = GrupoMateriaHorario::with(['grupoMateria.materia', 'grupoMateria.grupo'])
                ->where('id_docente', $docenteId)
                ->findOrFail($id);

            // Generar datos para el QR
            $qrData = $this->generarDatosQR($clase);
            
            // GENERAR QR REAL COMO SVG (más compatible)
            $qrImage = QrCode::format('svg')
                ->size(280)
                ->margin(2)
                ->errorCorrection('H')
                ->backgroundColor(255, 255, 255)
                ->color(2, 103, 115)
                ->generate($qrData);

            return response($qrImage)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            // Fallback a QR básico
            return $this->generarQRBasico($id);
        }
    }

    /**
     * Generar código temporal (SOLO MAYÚSCULAS)
     */
    private function generarCodigoTemporal($claseId)
    {
        // Usar solo letras mayúsculas para evitar problemas
        $caracteres = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $codigo = '';
        
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        // Guardar en sesión para validación
        session(['codigo_temporal_' . $claseId => $codigo]);
        
        return $codigo;
    }
}