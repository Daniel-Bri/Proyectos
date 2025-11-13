<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Masiva de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'deep-teal': {
                            25: '#f0f7f7',
                            50: '#e0f0f0',
                            100: '#c4e4e4',
                            200: '#9dd1d1',
                            300: '#6fb6b6',
                            400: '#3ca6a6',
                            500: '#026773',
                            600: '#024954',
                            700: '#012e36',
                            800: '#01242a',
                            900: '#011a1f',
                        },
                        'cream': {
                            50: '#fdf8f4',
                            100: '#faf1ea',
                            200: '#f2e3d5',
                            300: '#e8d5c4',
                            400: '#ddc7b3',
                            500: '#d4baa2',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #012E40 0%, #024959 50%, #026773 100%);
        }
        .gradient-header {
            background: linear-gradient(135deg, #012E40 0%, #024959 100%);
        }
        .gradient-card {
            background: linear-gradient(135deg, #012E40 0%, #024959 100%);
        }
        .drop-zone {
            border: 2px dashed #3ca6a6;
            transition: all 0.3s ease;
        }
        .drop-zone.dragover {
            border-color: #026773;
            background-color: #f0f7f7;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 gradient-header shadow-lg border-b border-deep-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-cream-200 rounded-lg grid place-items-center">
                        <svg class="w-5 h-5 text-deep-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-cream-200">Carga Masiva de Usuarios</h1>
                        <p class="text-xs text-cream-300">Importación de usuarios desde archivo Excel/CSV</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin/dashboard') }}"
                       class="inline-flex items-center gap-2 bg-cream-200 text-deep-teal-700 px-3 py-2 rounded-lg font-medium hover:bg-cream-300 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="py-6">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- ALERTAS -->
            @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- TARJETA PRINCIPAL -->
            <div class="gradient-card rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-cream-200 mb-6">📁 Cargar Archivo de Usuarios</h2>
                
                <form action="{{ route('admin.carga-masiva.usuarios.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- GESTIÓN ACADÉMICA -->
                    <div>
                        <label class="block text-sm font-medium text-cream-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Gestión Académica
                        </label>
                        <select name="id_gestion" class="w-full bg-deep-teal-600 text-cream-200 rounded-lg border border-deep-teal-400 p-3 focus:ring-2 focus:ring-cream-200 focus:border-transparent" required>
                            <option value="">Seleccionar gestión...</option>
                            @foreach($gestiones as $gestion)
                                <option value="{{ $gestion->id }}">{{ $gestion->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ZONA DE CARGA -->
                    <div>
                        <label class="block text-sm font-medium text-cream-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Archivo Excel/CSV
                        </label>
                        
                        <div class="drop-zone border-2 border-dashed border-deep-teal-400 rounded-lg p-8 text-center transition-all duration-300 hover:border-deep-teal-300 hover:bg-deep-teal-600/50"
                             id="dropZone">
                            <input type="file" name="archivo_usuarios" id="archivoInput" 
                                   class="hidden" accept=".csv,.txt,.xlsx,.xls" required>
                            
                            <div class="space-y-3">
                                <svg class="w-12 h-12 text-deep-teal-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <div>
                                    <button type="button" onclick="document.getElementById('archivoInput').click()" 
                                            class="bg-cream-200 text-deep-teal-700 px-4 py-2 rounded-lg font-medium hover:bg-cream-300 transition">
                                        Seleccionar archivo
                                    </button>
                                    <p class="text-cream-300 text-sm mt-2">o arrastra y suelta el archivo aquí</p>
                                </div>
                                <p class="text-cream-400 text-xs">
                                    Formatos aceptados: Excel (.xlsx, .xls), CSV (.csv, .txt) (Máximo 1MB)
                                </p>
                            </div>
                        </div>
                        
                        <div id="nombreArchivo" class="text-cream-200 text-sm mt-2 hidden">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Archivo seleccionado: <span id="fileName" class="font-medium"></span>
                        </div>
                    </div>

                    <!-- BOTONES DE ACCIÓN -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4 border-t border-deep-teal-400">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('admin.carga-masiva.usuarios.plantilla', ['formato' => 'csv']) }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition shadow-md flex items-center justify-center gap-2 text-center text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Plantilla CSV
                            </a>
                            
                            <a href="{{ route('admin.carga-masiva.usuarios.plantilla', ['formato' => 'excel']) }}"
                               class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition shadow-md flex items-center justify-center gap-2 text-center text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Plantilla Excel
                            </a>
                        </div>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Continuar a Previsualización
                        </button>
                    </div>
                </form>
            </div>

            <!-- INFORMACIÓN DEL FORMATO -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-deep-teal-700 mb-4">📋 Formato del Archivo</h3>
                
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">💡 Importante sobre el formato</h4>
                    <p class="text-blue-700 text-sm">
                        <strong>Para CSV:</strong> Use coma (,) como separador de columnas.<br>
                        <strong>Para Excel:</strong> Cada columna debe estar en su propia casilla. Descargue la plantilla Excel para el formato correcto.
                    </p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-deep-teal-50">
                            <tr>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Columna</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Descripción</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Obligatorio</th>
                                <th class="text-left py-3 px-4 text-deep-teal-800 font-semibold">Ejemplo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">email</td>
                                <td class="py-3 px-4 text-gray-600">Correo electrónico institucional</td>
                                <td class="py-3 px-4 text-gray-600"><span class="text-red-500">✓</span> Sí</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">usuario@ficct.edu.bo</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">name</td>
                                <td class="py-3 px-4 text-gray-600">Nombre completo del usuario</td>
                                <td class="py-3 px-4 text-gray-600"><span class="text-red-500">✓</span> Sí</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">Juan Pérez</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">rol</td>
                                <td class="py-3 px-4 text-gray-600">Rol del usuario</td>
                                <td class="py-3 px-4 text-gray-600"><span class="text-red-500">✓</span> Sí</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">docente</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">codigo_docente</td>
                                <td class="py-3 px-4 text-gray-600">Código único del docente</td>
                                <td class="py-3 px-4 text-gray-600">Solo para rol docente</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">DOC001</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">telefono</td>
                                <td class="py-3 px-4 text-gray-600">Número de teléfono</td>
                                <td class="py-3 px-4 text-gray-600">Opcional</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">78111662</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">carrera</td>
                                <td class="py-3 px-4 text-gray-600">Carrera del docente</td>
                                <td class="py-3 px-4 text-gray-600">Opcional</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">Ingeniería en Sistemas</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-700 font-medium">sueldo</td>
                                <td class="py-3 px-4 text-gray-600">Sueldo del docente</td>
                                <td class="py-3 px-4 text-gray-600">Opcional</td>
                                <td class="py-3 px-4 text-gray-600 font-mono text-xs">8000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Ejemplo visual del formato -->
                <div class="mt-6">
                    <h4 class="font-semibold text-deep-teal-700 mb-3">📝 Ejemplo del formato correcto:</h4>
                    <div class="bg-gray-800 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                        <div>email,name,rol,codigo_docente,telefono,carrera,sueldo</div>
                        <div>docente1@ficct.edu.bo,Juan Pérez,docente,DOC001,78111662,Ingeniería en Sistemas,8000</div>
                        <div>coordinador1@ficct.edu.bo,María López,coordinador,,78111663,,</div>
                        <div>admin@ficct.edu.bo,Admin Sistema,admin,,78111664,,</div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">🔐 Generación Automática de Contraseñas</h4>
                    <div class="space-y-2 text-sm text-green-700">
                        <div><span class="font-medium">Para docentes:</span> CódigoDocente + Iniciales → <code class="bg-green-100 px-2 py-1 rounded">DOC001JP</code></div>
                        <div><span class="font-medium">Para otros roles:</span> Iniciales + 123 → <code class="bg-green-100 px-2 py-1 rounded">MAR123</code></div>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <!-- FOOTER -->
    <footer class="gradient-header border-t border-deep-teal-700 py-6 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-cream-300 text-sm">
                <p>Sistema de Gestión de Horarios - {{ date('Y') }}</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('archivoInput');
            const fileName = document.getElementById('fileName');
            const nombreArchivoDiv = document.getElementById('nombreArchivo');

            // Manejar selección de archivo
            fileInput.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    mostrarNombreArchivo(this.files[0].name);
                }
            });

            // Manejar drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropZone.classList.add('dragover');
            }

            function unhighlight() {
                dropZone.classList.remove('dragover');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    fileInput.files = files;
                    mostrarNombreArchivo(files[0].name);
                }
            }

            function mostrarNombreArchivo(nombre) {
                fileName.textContent = nombre;
                nombreArchivoDiv.classList.remove('hidden');
            }

            // Validación del formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const gestion = document.querySelector('select[name="id_gestion"]').value;
                const archivo = fileInput.files[0];
                
                if (!gestion) {
                    e.preventDefault();
                    alert('Por favor seleccione una gestión académica');
                    return;
                }
                
                if (!archivo) {
                    e.preventDefault();
                    alert('Por favor seleccione un archivo');
                    return;
                }
                
                // Validar extensión
                const extension = archivo.name.split('.').pop().toLowerCase();
                if (!['csv', 'txt', 'xlsx', 'xls'].includes(extension)) {
                    e.preventDefault();
                    alert('Por favor seleccione un archivo Excel o CSV válido');
                    return;
                }
            });
        });
    </script>
</body>
</html>