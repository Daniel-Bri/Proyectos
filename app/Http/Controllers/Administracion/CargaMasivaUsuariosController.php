<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Docente;
use App\Models\GestionAcademica;
use App\Models\Carrera;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsuariosImport;

class CargaMasivaUsuariosController extends Controller
{
    public function index()
    {
        $gestiones = GestionAcademica::whereIn('estado', ['curso', 'activo'])->get();
        return view('cargaMasiva.index', compact('gestiones'));
    }

    public function preview(Request $request)
{
    try {
        // Validar archivo
        $request->validate([
            'archivo_usuarios' => 'required|file|mimes:csv,txt,xlsx,xls|max:1024',
            'id_gestion' => 'required|exists:gestion_academica,id'
        ]);

        $archivo = $request->file('archivo_usuarios');
        $extension = $archivo->getClientOriginalExtension();
        $datos = [];
        $errores = [];

        // Procesar según el tipo de archivo
        if (in_array($extension, ['xlsx', 'xls'])) {
            // Procesar archivo Excel
            $datos = $this->procesarExcel($archivo);
        } else {
            // Procesar archivo CSV
            $datos = $this->procesarCSV($archivo);
        }

        // Validar y procesar datos - CORREGIDO: Asegurar que password se genere
        $datosValidos = [];
        foreach ($datos as $index => $fila) {
            // Asegurar que todos los campos existan
            $filaCompleta = array_merge([
                'email' => '',
                'name' => '',
                'rol' => '',
                'codigo_docente' => '',
                'telefono' => '',
                'carrera' => '',
                'sueldo' => ''
            ], $fila);

            // Generar password automáticamente - AQUÍ ESTÁ LA CORRECCIÓN
            $filaCompleta['password'] = $this->generarPassword($filaCompleta);
            
            // Validar fila
            $errorFila = $this->validarFila($filaCompleta, $index + 1);
            if ($errorFila) {
                $errores[] = $errorFila;
            } else {
                $datosValidos[] = $filaCompleta; // Usar $datosValidos en lugar de modificar $datos
            }
        }

        // Reemplazar datos originales con datos válidos
        $datos = $datosValidos;

        if (empty($datos)) {
            return redirect()->back()->with('error', 'No se encontraron datos válidos en el archivo.');
        }

        return view('cargaMasiva.preview', [
            'datos' => $datos,
            'errores' => $errores,
            'totalRegistros' => count($datos),
            'totalErrores' => count($errores),
            'id_gestion' => $request->id_gestion,
            'nombreArchivo' => $archivo->getClientOriginalName()
        ]);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
    }
}

   public function procesar(Request $request)
{
    try {

        $request->validate([
            'datos' => 'required|array',
            'id_gestion' => 'required|exists:gestion_academica,id'
        ]);

        // Decodificar los datos JSON - CORREGIDO
        $datos = [];
        foreach ($request->datos as $index => $jsonData) {
            
            if (is_array($jsonData)) {
                // Si ya es un array, usarlo directamente
                $datos[] = $jsonData;
            } else {
                // Si es JSON, decodificarlo
                $usuarioData = json_decode($jsonData, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $datos[] = $usuarioData;
                } 
            }
        }


        // Verificar que hay datos para procesar
        if (empty($datos)) {
            return redirect()->route('admin.carga-masiva.usuarios.index')
                ->with('error', 'No hay datos válidos para procesar.');
        }

        $resultados = [
            'exitosos' => 0,
            'errores' => [],
            'usuarios_creados' => []
        ];

        DB::beginTransaction();

        foreach ($datos as $index => $usuarioData) {
            try {
                // Validar nuevamente antes de crear
                $error = $this->validarFila($usuarioData, $index + 1);
                if ($error) {
                    $resultados['errores'][] = "Fila " . ($index + 1) . ": " . $error;
                    continue;
                }

                // Verificar si el usuario ya existe
                if (User::where('email', $usuarioData['email'])->exists()) {
                    $resultados['errores'][] = "Fila " . ($index + 1) . ": El email ya existe en el sistema: " . $usuarioData['email'];
                    continue;
                }

                // Crear usuario
                $usuario = User::create([
                    'name' => $usuarioData['name'],
                    'email' => $usuarioData['email'],
                    'password' => Hash::make($usuarioData['password']),
                    'email_verified_at' => now(),
                ]);

                // Asignar rol
                $usuario->assignRole($usuarioData['rol']);

                // Si es docente, crear registro en tabla docente
                if ($usuarioData['rol'] === 'docente' && !empty($usuarioData['codigo_docente'])) {
                    // Verificar si el código docente ya existe
                    if (Docente::where('codigo', $usuarioData['codigo_docente'])->exists()) {
                        $resultados['errores'][] = "Fila " . ($index + 1) . ": El código docente ya existe: " . $usuarioData['codigo_docente'];
                        // Eliminar el usuario creado
                        $usuario->delete();
                        continue;
                    }

                    Docente::create([
                        'codigo' => $usuarioData['codigo_docente'],
                        'fecha_contrato' => now(),
                        'sueldo' => !empty($usuarioData['sueldo']) ? floatval($usuarioData['sueldo']) : 0,
                        'telefono' => $usuarioData['telefono'] ?? null,
                        'id_users' => $usuario->id
                    ]);

                    // Asignar carrera si se especificó
                    if (!empty($usuarioData['carrera'])) {
                        $carrera = Carrera::where('nombre', 'like', '%' . $usuarioData['carrera'] . '%')->first();
                        if ($carrera) {
                            DB::table('docente_carrera')->insert([
                                'codigo_docente' => $usuarioData['codigo_docente'],
                                'id_carrera' => $carrera->id
                            ]);
                        }
                    }
                }

                $resultados['exitosos']++;
                $resultados['usuarios_creados'][] = [
                    'email' => $usuario->email,
                    'name' => $usuario->name,
                    'rol' => $usuarioData['rol'],
                    'codigo_docente' => $usuarioData['codigo_docente'] ?? 'N/A',
                    'carrera' => $usuarioData['carrera'] ?? 'N/A',
                    'sueldo' => $usuarioData['sueldo'] ?? 'N/A',
                    'password_generada' => $usuarioData['password']
                ];


            } catch (\Exception $e) {
                $resultados['errores'][] = "Fila " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        DB::commit();



        return view('cargaMasiva.resultados', [
            'resultados' => $resultados,
            'totalProcesados' => count($datos),
            'id_gestion' => $request->id_gestion
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.carga-masiva.usuarios.index')
            ->with('error', 'Error en el proceso de importación: ' . $e->getMessage());
    }
}

    public function descargarPlantilla($formato = 'csv')
    {
        if ($formato === 'excel') {
            return $this->descargarPlantillaExcel();
        }
        
        return $this->descargarPlantillaCSV();
    }


    private function descargarPlantillaCSV()
    {
        // Usar coma como separador para el nuevo formato
        $contenido = "email,name,rol,codigo_docente,telefono,carrera,sueldo\n";
        $contenido .= "docente1@ficct.edu.bo,Juan Pérez,docente,DOC001,78111662,Ingeniería en Sistemas,8000\n";
        $contenido .= "coordinador1@ficct.edu.bo,María López,coordinador,,78111663,,\n";
        $contenido .= "admin@ficct.edu.bo,Admin Sistema,admin,,78111664,,\n";
        $contenido .= "docente2@ficct.edu.bo,Carlos Rojas,docente,DOC002,78111665,Ingeniería Informática,7500\n";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="plantilla-usuarios.csv"',
        ];
        
        return response($contenido, 200, $headers);
    }

    private function descargarPlantillaExcel()
{
    try {
        // Crear contenido Excel básico usando formato XML
        $contenido = $this->generarContenidoExcel();
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="plantilla-usuarios.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        return response($contenido, 200, $headers);

    } catch (\Exception $e) {
        // Si falla, generar CSV como respaldo
        return $this->descargarPlantillaCSV();
    }
}

private function generarContenidoExcel()
{
    $html = '
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            td { mso-number-format:\\@; }
            .header { background-color: #3498DB; color: #FFFFFF; font-weight: bold; }
        </style>
    </head>
    <body>
        <table border="1">
            <tr class="header">
                <td>email</td>
                <td>name</td>
                <td>rol</td>
                <td>codigo_docente</td>
                <td>telefono</td>
                <td>carrera</td>
                <td>sueldo</td>
            </tr>
            <tr>
                <td>docente1@ficct.edu.bo</td>
                <td>Juan Pérez</td>
                <td>docente</td>
                <td>DOC001</td>
                <td>78111662</td>
                <td>Ingeniería en Sistemas</td>
                <td>8000</td>
            </tr>
            <tr>
                <td>coordinador1@ficct.edu.bo</td>
                <td>María López</td>
                <td>coordinador</td>
                <td></td>
                <td>78111663</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>admin@ficct.edu.bo</td>
                <td>Admin Sistema</td>
                <td>admin</td>
                <td></td>
                <td>78111664</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>docente2@ficct.edu.bo</td>
                <td>Carlos Rojas</td>
                <td>docente</td>
                <td>DOC002</td>
                <td>78111665</td>
                <td>Ingeniería Informática</td>
                <td>7500</td>
            </tr>
        </table>
    </body>
    </html>';

    return $html;
}

    private function procesarCSV($archivo)
    {
        $contenido = file_get_contents($archivo->getRealPath());
        $lineas = explode("\n", $contenido);
        
        // Remover BOM si existe
        $lineas[0] = preg_replace('/^\xEF\xBB\xBF/', '', $lineas[0]);
        
        $datos = [];
        
        // Detectar separador
        $separador = $this->detectarSeparador($lineas[0]);
        $encabezados = str_getcsv($lineas[0], $separador);
        
        // Validar encabezados mínimos
        $encabezadosMinimos = ['email', 'name', 'rol'];
        if (count(array_intersect($encabezadosMinimos, $encabezados)) < 3) {
            throw new \Exception('El archivo no tiene el formato correcto. Los encabezados deben incluir: email, name, rol');
        }

        // Procesar cada línea
        for ($i = 1; $i < count($lineas); $i++) {
            if (empty(trim($lineas[$i]))) continue;
            
            $fila = str_getcsv($lineas[$i], $separador);
            if (count($fila) < 3) continue;
            
            $datosFila = array_combine($encabezados, array_pad($fila, count($encabezados), ''));
            $datos[] = $datosFila;
        }

        return $datos;
    }

    private function procesarExcel($archivo)
{
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $datos = [];

        // Obtener encabezados de la primera fila usando la sintaxis correcta
        $encabezados = [];
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $cell = $sheet->getCell([$col, 1]);
            $valor = $cell->getValue();
            if (!empty($valor)) {
                $encabezados[] = strtolower(trim($valor));
            } else {
                break;
            }
        }

        // Validar encabezados mínimos
        $encabezadosMinimos = ['email', 'name', 'rol'];
        if (count(array_intersect($encabezadosMinimos, $encabezados)) < 3) {
            throw new \Exception('El archivo Excel no tiene el formato correcto. Los encabezados deben incluir: email, name, rol');
        }

        // Procesar filas de datos
        $highestRow = $sheet->getHighestRow();
        
        for ($fila = 2; $fila <= $highestRow; $fila++) {
            $filaDatos = [];
            $cell = $sheet->getCell([1, $fila]);
            $email = $cell->getValue();
            
            // Solo procesar si tiene email (primera columna obligatoria)
            if (empty($email)) {
                continue;
            }
            
            for ($col = 1; $col <= count($encabezados); $col++) {
                $cell = $sheet->getCell([$col, $fila]);
                $valor = $cell->getValue();
                $filaDatos[$encabezados[$col-1]] = $valor ?? '';
            }

            $datos[] = $filaDatos;
        }

        return $datos;

    } catch (\Exception $e) {
        throw new \Exception('Error al procesar archivo Excel: ' . $e->getMessage());
    }
}

    private function validarFila($fila, $numeroFila)
    {
        $camposObligatorios = ['email', 'name', 'rol'];
        foreach ($camposObligatorios as $campo) {
            if (empty($fila[$campo] ?? '')) {
                return "Campo '$campo' es obligatorio";
            }
        }

        if (!filter_var($fila['email'], FILTER_VALIDATE_EMAIL)) {
            return "Email no válido: " . $fila['email'];
        }

        if (User::where('email', $fila['email'])->exists()) {
            return "El email ya existe en el sistema: " . $fila['email'];
        }

        $rolesPermitidos = ['admin', 'coordinador', 'docente'];
        if (!in_array($fila['rol'], $rolesPermitidos)) {
            return "Rol no válido. Debe ser: " . implode(', ', $rolesPermitidos);
        }

        if ($fila['rol'] === 'docente') {
            if (empty($fila['codigo_docente'])) {
                return "El código docente es obligatorio para el rol 'docente'";
            }
            
            if (Docente::where('codigo', $fila['codigo_docente'])->exists()) {
                return "El código docente ya existe: " . $fila['codigo_docente'];
            }
        }

        return null;
    }

    private function generarPassword($datosFila)
    {
        if ($datosFila['rol'] === 'docente' && !empty($datosFila['codigo_docente'])) {
            $iniciales = $this->obtenerIniciales($datosFila['name']);
            return $datosFila['codigo_docente'] . $iniciales;
        }
        
        $nombre = preg_replace('/[^a-zA-Z]/', '', $datosFila['name']);
        $iniciales = strtoupper(substr($nombre, 0, 3));
        return $iniciales . '123';
    }

    private function obtenerIniciales($nombreCompleto)
    {
        $palabras = explode(' ', trim($nombreCompleto));
        $iniciales = '';
        
        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $iniciales .= strtoupper(substr($palabra, 0, 1));
            }
        }
        
        return $iniciales;
    }

    private function detectarSeparador($primeraLinea)
    {
        // Contar ocurrencias de diferentes separadores
        $puntoYComa = substr_count($primeraLinea, ';');
        $coma = substr_count($primeraLinea, ',');
        $tabulador = substr_count($primeraLinea, "\t");
        
        // Usar el separador más común
        if ($puntoYComa > $coma && $puntoYComa > $tabulador) {
            return ';';
        } elseif ($coma > $puntoYComa && $coma > $tabulador) {
            return ',';
        } elseif ($tabulador > 0) {
            return "\t";
        } else {
            return ','; // Por defecto usar coma para el nuevo formato
        }
    }
}