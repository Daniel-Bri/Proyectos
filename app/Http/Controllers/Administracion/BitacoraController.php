<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    // Muestra la lista de auditorías (bitácora)
    public function index(Request $request)
    {
        // Verificar permisos usando rol admin (solución temporal)
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para ver la bitácora.');
        }

        $query = Auditoria::with('user')
            ->orderByDesc('created_at');

        // Filtros
        if ($request->filled('accion')) {
            $query->where('accion', 'LIKE', '%' . $request->accion . '%');
        }

        if ($request->filled('entidad')) {
            $query->where('entidad', 'LIKE', '%' . $request->entidad . '%');
        }

        if ($request->filled('usuario')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->usuario . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->usuario . '%');
            });
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $auditorias = $query->paginate(20);

        return view('admin.bitacora.index', compact('auditorias'));
    }

    // Muestra el detalle de una acción de auditoría
    public function show($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para ver la bitácora.');
        }

        $auditoria = Auditoria::with('user')->findOrFail($id);

        return view('admin.bitacora.show', compact('auditoria'));
    }

    // Exportar bitácora a Excel
    public function exportar(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para exportar la bitácora.');
        }

        $auditorias = Auditoria::with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.bitacora.exportar', compact('auditorias'));
    }

    // Limpiar registros antiguos de la bitácora
    public function limpiar(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para limpiar la bitácora.');
        }

        $dias = $request->dias ?? 30; // Por defecto 30 días

        $fechaLimite = now()->subDays($dias);
        $eliminados = Auditoria::where('created_at', '<', $fechaLimite)->delete();

        return redirect()->route('admin.bitacora.index')
            ->with('success', "Se eliminaron $eliminados registros de la bitácora (más de $dias días).");
    }

    // Método estático para registrar acciones en la bitácora
    // En BitacoraController.php - CORREGIR el método registrar
    public static function registrar($accion, $entidad, $entidad_id = null, $usuario_id = null, $request = null, $detalles_extra = null)
    {
        try {
            $req = $request ?? request();
            
            // SOLUCIÓN: Si no hay usuario autenticado, usar un ID por defecto para logs del sistema
            $usuarioId = $usuario_id ?? (auth()->check() ? auth()->id() : 1);
            
            $auditoria = Auditoria::create([
                'id_users'   => $usuarioId,
                'accion'     => $accion,
                'entidad'    => $entidad,
                'entidad_id' => $entidad_id,
                'ip'         => self::obtenerIpReal($req),
                'user_agent' => self::formatearUserAgent($req->userAgent()),
                'descripcion' => $detalles_extra,
                'created_at' => now(),
            ]);

            return $auditoria;
            
        } catch (\Exception $e) {
            // Si hay error, solo logear pero no romper la aplicación
            return null;
        }
    }

    // Métodos helper para acciones comunes del sistema
    public static function registrarLogin($usuario_id, $request = null)
    {
        return self::registrar(
            'Inicio de sesión',
            'Usuario',
            $usuario_id,
            $usuario_id,
            $request,
            'Acceso al sistema'
        );
    }

    public static function registrarLogout($usuario_id, $request = null)
    {
        return self::registrar(
            'Cierre de sesión',
            'Usuario',
            $usuario_id,
            $usuario_id,
            $request,
            'Salida del sistema'
        );
    }

    public static function registrarCreacion($entidad, $entidad_id, $usuario_id = null, $detalles = null)
    {
        return self::registrar(
            'Creación',
            $entidad,
            $entidad_id,
            $usuario_id,
            null,
            $detalles ?? "Nuevo registro creado en $entidad"
        );
    }

    public static function registrarActualizacion($entidad, $entidad_id, $usuario_id = null, $detalles = null)
    {
        return self::registrar(
            'Actualización',
            $entidad,
            $entidad_id,
            $usuario_id,
            null,
            $detalles ?? "Registro actualizado en $entidad"
        );
    }

    public static function registrarEliminacion($entidad, $entidad_id, $usuario_id = null, $detalles = null)
    {
        return self::registrar(
            'Eliminación',
            $entidad,
            $entidad_id,
            $usuario_id,
            null,
            $detalles ?? "Registro eliminado de $entidad"
        );
    }

    public static function registrarImportacion($entidad, $usuario_id = null, $registros = 0)
    {
        return self::registrar(
            'Importación masiva',
            $entidad,
            null,
            $usuario_id,
            null,
            "Se importaron $registros registros en $entidad"
        );
    }

    /**
     * Obtiene la IP real del cliente, considerando proxies
     */
    private static function obtenerIpReal($request)
    {
        // Cloudflare
        if ($ip = $request->header('CF-Connecting-IP')) {
            return $ip;
        }
        
        // Nginx proxy o similar
        if ($ip = $request->header('X-Real-IP')) {
            return $ip;
        }
        
        // Proxy estándar (puede tener múltiples IPs)
        if ($forwarded = $request->header('X-Forwarded-For')) {
            $ips = array_map('trim', explode(',', $forwarded));
            // La primera IP es la del cliente original
            $ip = $ips[0];
            if ($ip !== '127.0.0.1' && $ip !== '::1') {
                return $ip;
            }
        }
        
        // Obtener IP del servidor
        $ip = $request->ip();
        
        // Si es localhost, intentar obtener la IP de red local
        if ($ip === '127.0.0.1' || $ip === '::1') {
            // En Windows, intentar obtener la IP local
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $output = shell_exec('ipconfig | findstr /i "IPv4"');
                if ($output && preg_match('/(\d+\.\d+\.\d+\.\d+)/', $output, $matches)) {
                    // Retornar la primera IP local encontrada
                    $localIp = $matches[1];
                    if ($localIp !== '127.0.0.1') {
                        return $localIp . ' (Local)';
                    }
                }
            } else {
                // En Linux/Mac
                $output = shell_exec("hostname -I | awk '{print $1}'");
                if ($output) {
                    $localIp = trim($output);
                    if ($localIp && $localIp !== '127.0.0.1') {
                        return $localIp . ' (Local)';
                    }
                }
            }
            
            return '127.0.0.1 (localhost)';
        }
        
        return $ip;
    }

    /**
     * Formatea el User Agent a texto legible
     */
    private static function formatearUserAgent($userAgent)
    {
        if (!$userAgent) {
            return 'Desconocido';
        }

        // Detectar navegador
        $navegador = 'Desconocido';
        $version = '';
        
        if (preg_match('/OPR\/(\d+)/', $userAgent, $matches)) {
            $navegador = 'Opera';
            $version = 'ver.' . $matches[1];
        } elseif (preg_match('/Edg\/(\d+)/', $userAgent, $matches)) {
            $navegador = 'Edge';
            $version = 'ver.' . $matches[1];
        } elseif (preg_match('/Chrome\/(\d+)/', $userAgent, $matches) && !strpos($userAgent, 'Edg')) {
            $navegador = 'Chrome';
            $version = 'ver.' . $matches[1];
        } elseif (preg_match('/Firefox\/(\d+)/', $userAgent, $matches)) {
            $navegador = 'Firefox';
            $version = 'ver.' . $matches[1];
        } elseif (preg_match('/Safari\/(\d+)/', $userAgent, $matches) && !strpos($userAgent, 'Chrome')) {
            $navegador = 'Safari';
            $version = 'ver.' . $matches[1];
        }

        // Detectar sistema operativo
        $so = 'Desconocido';
        
        if (preg_match('/Windows NT 10\.0/', $userAgent)) {
            $so = 'Windows 10/11';
        } elseif (preg_match('/Windows NT 6\.3/', $userAgent)) {
            $so = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6\.2/', $userAgent)) {
            $so = 'Windows 8';
        } elseif (preg_match('/Windows NT 6\.1/', $userAgent)) {
            $so = 'Windows 7';
        } elseif (preg_match('/Mac OS X/', $userAgent)) {
            $so = 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            $so = 'Linux';
        } elseif (preg_match('/Android/', $userAgent)) {
            $so = 'Android';
        } elseif (preg_match('/iPhone|iPad/', $userAgent)) {
            $so = 'iOS';
        }

        return "$navegador $version en $so";
    }
}
