<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Previsualización</title>
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
</head>
<body class="bg-white">
    <!-- TABLA COMPACTA PARA PREVISUALIZACIÓN -->
    <div class="p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-deep-teal-50">
                    <tr>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Email</th>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Nombre</th>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Rol</th>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Código</th>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Teléfono</th>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Contraseña</th>
                        <th class="text-left py-2 px-3 text-deep-teal-800 font-semibold border-b">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($datos as $usuario)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-3 text-gray-700 text-xs">{{ $usuario['email'] }}</td>
                        <td class="py-2 px-3 text-gray-700 text-xs">{{ $usuario['name'] }}</td>
                        <td class="py-2 px-3">
                            @php
                                $rolColors = [
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'coordinador' => 'bg-blue-100 text-blue-800', 
                                    'docente' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="px-1 py-0.5 rounded text-xs font-medium {{ $rolColors[$usuario['rol']] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $usuario['rol'] }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-gray-700 font-mono text-xs">
                            {{ $usuario['codigo_docente'] ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-3 text-gray-700 text-xs">
                            {{ $usuario['telefono'] ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-3">
                            <code class="bg-gray-100 text-gray-700 px-1 py-0.5 rounded text-xs font-mono">
                                {{ $usuario['password'] }}
                            </code>
                        </td>
                        <td class="py-2 px-3">
                            <span class="px-1 py-0.5 bg-green-100 text-green-800 rounded text-xs font-medium">
                                ✅
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($datos) === 0)
        <div class="text-center py-8 text-gray-500 text-sm">
            <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>No hay registros válidos para mostrar.</p>
        </div>
        @endif

        <!-- RESUMEN COMPACTO -->
        <div class="mt-4 p-3 bg-deep-teal-50 rounded-lg border border-deep-teal-200">
            <div class="flex justify-between items-center text-xs text-deep-teal-800">
                <span class="font-medium">Total registros válidos: {{ count($datos) }}</span>
                <span class="text-green-600 font-medium">Listo para importar</span>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body { 
                margin: 0; 
                padding: 10px; 
                font-size: 10px; 
            }
            table { 
                page-break-inside: auto; 
            }
            tr { 
                page-break-inside: avoid; 
                page-break-after: auto; 
            }
        }
    </style>
</body>
</html>