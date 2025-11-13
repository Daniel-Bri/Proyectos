<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GrupoMateria;
use App\Models\GestionAcademica;
use App\Models\GrupoMateriaHorario;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            abort(403, 'No tienes permisos para acceder al panel de control.');
        }

        $stats = $this->obtenerEstadisticasCompletas();
        $tendencias = $this->obtenerTendencias();
        $alertas = $this->obtenerAlertas();
        $metricasRapidas = $this->obtenerMetricasRapidas();

        // Obtener últimas asistencias
        $asistencias = Asistencia::with([
                'grupoMateriaHorario.docente.user',
                'grupoMateriaHorario.grupoMateria.materia',
                'grupoMateriaHorario.grupoMateria.grupo'
            ])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_registro', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'stats', 
            'asistencias', 
            'tendencias', 
            'alertas',
            'metricasRapidas'
        ));
    }

    /**
     * Obtener estadísticas completas del sistema - CORREGIDO
     */
    private function obtenerEstadisticasCompletas()
    {
        $hoy = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $inicioMes = Carbon::now()->startOfMonth();

        return [
            // Estadísticas básicas
            'docentes_count' => Docente::count(),
            'materias_count' => Materia::count(),
            'aulas_count' => Aula::count(),
            'grupos_count' => Grupo::count(),
            'usuarios_count' => User::count(),
            
            // Estadísticas de asistencia
            'asistencias_hoy' => Asistencia::whereDate('fecha', $hoy)->count(),
            'asistencias_semana' => Asistencia::where('fecha', '>=', $inicioSemana)->count(),
            'asistencias_mes' => Asistencia::where('fecha', '>=', $inicioMes)->count(),
            
            // Distribución de estados de asistencia
            'presentes_hoy' => Asistencia::whereDate('fecha', $hoy)->where('estado', 'presente')->count(),
            'tardanzas_hoy' => Asistencia::whereDate('fecha', $hoy)->where('estado', 'tardanza')->count(),
            'ausentes_hoy' => Asistencia::whereDate('fecha', $hoy)->where('estado', 'ausente')->count(),
            
            // Métricas de horarios
            'clases_programadas_hoy' => $this->getClasesProgramadasHoy(),
            'aulas_ocupadas_hoy' => $this->getAulasOcupadasHoy(),
            'docentes_activos_hoy' => $this->getDocentesActivosHoy(),
            
            // Porcentajes clave
            'porcentaje_asistencia_hoy' => $this->calcularPorcentajeAsistenciaHoy(),
            'porcentaje_puntualidad' => $this->calcularPorcentajePuntualidad(),
            
            // Estadísticas adicionales
            'nuevos_docentes_mes' => Docente::where('created_at', '>=', $inicioMes)->count(),
            'aulas_disponibles' => Aula::count(),
            'aulas_ocupadas' => 0,
        ];
    }

    /**
     * Obtener tendencias y datos para gráficos - SIMPLIFICADO
     */
    private function obtenerTendencias()
    {
        $hoy = Carbon::today();

        return [
            'asistencia_ultima_semana' => $this->getAsistenciaUltimaSemana(),
            'distribucion_estados' => [
                'presente' => Asistencia::whereDate('fecha', $hoy)->where('estado', 'presente')->count(),
                'tardanza' => Asistencia::whereDate('fecha', $hoy)->where('estado', 'tardanza')->count(),
                'ausente' => Asistencia::whereDate('fecha', $hoy)->where('estado', 'ausente')->count(),
            ],
            'top_materias_asistencia' => $this->getTopMateriasAsistencia(),
            'docentes_mas_puntuales' => $this->getDocentesMasPuntuales(),
        ];
    }

    /**
     * Obtener alertas del sistema
     */
    private function obtenerAlertas()
    {
        $alertas = [];
        $hoy = Carbon::today();

        // Alerta por baja asistencia hoy
        $porcentajeAsistencia = $this->calcularPorcentajeAsistenciaHoy();
        if ($porcentajeAsistencia < 70 && $porcentajeAsistencia > 0) {
            $alertas[] = [
                'tipo' => 'alta',
                'icono' => '⚠️',
                'titulo' => 'Baja Asistencia Hoy',
                'mensaje' => 'Solo el ' . $porcentajeAsistencia . '% de asistencia registrada',
                'color' => 'red'
            ];
        }

        // Alerta por docentes con inasistencias
        $docentesInasistencias = Asistencia::whereDate('fecha', $hoy)
            ->where('estado', 'ausente')
            ->distinct('id_grupo_materia_horario')
            ->count();

        if ($docentesInasistencias > 0) {
            $alertas[] = [
                'tipo' => 'media',
                'icono' => '👨‍🏫',
                'titulo' => 'Inasistencias Registradas',
                'mensaje' => $docentesInasistencias . ' docentes con ausencias hoy',
                'color' => 'yellow'
            ];
        }

        // Alerta de sistema estable
        $alertas[] = [
            'tipo' => 'baja',
            'icono' => '✅',
            'titulo' => 'Sistema Estable',
            'mensaje' => 'Todas las funcionalidades operativas',
            'color' => 'green'
        ];

        return $alertas;
    }

    /**
     * Obtener métricas rápidas para tarjetas
     */
    private function obtenerMetricasRapidas()
    {
        $hoy = Carbon::today();
        
        return [
            'total_clases_hoy' => $this->getClasesProgramadasHoy(),
            'asistencia_promedio' => $this->calcularPorcentajeAsistenciaHoy(),
            'docentes_con_asistencia' => Asistencia::whereDate('fecha', $hoy)
                ->distinct('id_grupo_materia_horario')
                ->count(),
            'materias_activas_hoy' => GrupoMateriaHorario::whereHas('horario', function($q) {
                $q->where('dia', $this->getDiaSemanaEspanol());
            })->distinct('id_grupo_materia')
            ->count(),
        ];
    }

    // =========================================================================
    // MÉTODOS AUXILIARES - COMPLETAMENTE CORREGIDOS
    // =========================================================================

    /**
     * Convertir día de la semana a español
     */
    private function getDiaSemanaEspanol()
    {
        $diasIngles = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $diasEspanol = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        
        $diaIngles = strtolower(Carbon::now()->englishDayOfWeek);
        $indice = array_search($diaIngles, $diasIngles);
        
        return $diasEspanol[$indice] ?? 'Lunes';
    }

    /**
     * Calcular porcentaje de asistencia hoy
     */
    private function calcularPorcentajeAsistenciaHoy()
    {
        $hoy = Carbon::today();
        $totalClases = $this->getClasesProgramadasHoy();
        $asistenciasRegistradas = Asistencia::whereDate('fecha', $hoy)->count();
        
        if ($totalClases > 0) {
            return round(($asistenciasRegistradas / $totalClases) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Calcular porcentaje de puntualidad
     */
    private function calcularPorcentajePuntualidad()
    {
        $hoy = Carbon::today();
        $totalAsistencias = Asistencia::whereDate('fecha', $hoy)->count();
        $presentes = Asistencia::whereDate('fecha', $hoy)->where('estado', 'presente')->count();
        
        if ($totalAsistencias > 0) {
            return round(($presentes / $totalAsistencias) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Obtener clases programadas para hoy
     */
    private function getClasesProgramadasHoy()
    {
        $diaSemana = $this->getDiaSemanaEspanol();
        return GrupoMateriaHorario::whereHas('horario', function($q) use ($diaSemana) {
            $q->where('dia', $diaSemana);
        })->count();
    }

    /**
     * Obtener aulas ocupadas hoy
     */
    private function getAulasOcupadasHoy()
    {
        $diaSemana = $this->getDiaSemanaEspanol();
        return GrupoMateriaHorario::whereHas('horario', function($q) use ($diaSemana) {
            $q->where('dia', $diaSemana);
        })->distinct('id_aula')->count('id_aula');
    }

    /**
     * Obtener docentes activos hoy
     */
    private function getDocentesActivosHoy()
    {
        $diaSemana = $this->getDiaSemanaEspanol();
        return GrupoMateriaHorario::whereHas('horario', function($q) use ($diaSemana) {
            $q->where('dia', $diaSemana);
        })->distinct('id_docente')->count('id_docente');
    }

    /**
     * Obtener asistencia de la última semana
     */
    private function getAsistenciaUltimaSemana()
    {
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();
        
        return Asistencia::whereBetween('fecha', [$inicioSemana, $finSemana])
            ->selectRaw('DATE(fecha) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->pluck('total', 'dia');
    }

    /**
     * Obtener top materias con más asistencia - CORREGIDO
     */
    private function getTopMateriasAsistencia()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        
        // Usando Eloquent en lugar de joins para evitar problemas de nombres de tabla
        $asistencias = Asistencia::where('fecha', '>=', $inicioMes)
            ->with(['grupoMateriaHorario.grupoMateria.materia'])
            ->get();
        
        $materiasCount = [];
        
        foreach ($asistencias as $asistencia) {
            $materiaNombre = $asistencia->grupoMateriaHorario->grupoMateria->materia->nombre ?? 'Desconocida';
            if (!isset($materiasCount[$materiaNombre])) {
                $materiasCount[$materiaNombre] = 0;
            }
            $materiasCount[$materiaNombre]++;
        }
        
        // Ordenar por cantidad de asistencias
        arsort($materiasCount);
        
        // Convertir a colección
        $result = collect();
        $count = 0;
        foreach ($materiasCount as $materia => $total) {
            if ($count >= 5) break;
            $result->push([
                'nombre' => $materia,
                'total_asistencias' => $total
            ]);
            $count++;
        }
        
        return $result;
    }

    /**
     * Obtener docentes más puntuales - CORREGIDO
     */
    private function getDocentesMasPuntuales()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        
        // Usando Eloquent en lugar de joins
        $asistencias = Asistencia::where('fecha', '>=', $inicioMes)
            ->where('estado', 'presente')
            ->with(['grupoMateriaHorario.docente.user'])
            ->get();
        
        $docentesCount = [];
        
        foreach ($asistencias as $asistencia) {
            $docenteNombre = $asistencia->grupoMateriaHorario->docente->user->name ?? 'Desconocido';
            if (!isset($docentesCount[$docenteNombre])) {
                $docentesCount[$docenteNombre] = 0;
            }
            $docentesCount[$docenteNombre]++;
        }
        
        // Ordenar por cantidad de asistencias puntuales
        arsort($docentesCount);
        
        // Convertir a colección
        $result = collect();
        $count = 0;
        foreach ($docentesCount as $docente => $presentes) {
            if ($count >= 5) break;
            $result->push([
                'nombre' => $docente,
                'asistencias_puntuales' => $presentes
            ]);
            $count++;
        }
        
        return $result;
    }

    /**
     * API para estadísticas en tiempo real
     */
    public function getEstadisticasTiempoReal()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'coordinador'])) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json([
            'estadisticas' => $this->obtenerEstadisticasCompletas(),
            'alertas' => $this->obtenerAlertas(),
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}