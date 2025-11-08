<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Docente; 
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Administracion\BitacoraController;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Si es coordinador, solo ver usuarios de su misma carrera
        if (Auth::user()->hasRole('coordinador')) {
            $users = User::with('roles')
                ->whereHas('carrera', function($query) {
                    $query->where('id', Auth::user()->carrera_id);
                })
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                })
                ->orderBy('name', 'asc')
                ->paginate(10);
        } else {
            // Admin ve todos los usuarios
            $users = User::with('roles')
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                })
                ->orderBy('name', 'asc')
                ->paginate(10);
        }

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Coordinador solo puede crear docentes
        if (Auth::user()->hasRole('coordinador')) {
            $roles = Role::where('name', 'docente')->get();
        } else {
            $roles = Role::all();
        }
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Obtener el ID del rol docente
    $docenteRole = Role::where('name', 'docente')->first();
    $docenteRoleId = $docenteRole ? $docenteRole->id : null;

    // Validaciones específicas por rol
    if (Auth::user()->hasRole('coordinador')) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array|size:1',
            'roles.*' => 'in:docente',
            // Campos requeridos para docente
            'codigo' => 'required|string|max:20|unique:docente',
            'telefono' => 'required|string|max:15',
            'sueldo' => 'required|numeric|min:0',
            'fecha_contrato' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_contrato',
        ]);
    } else {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            // Campos condicionales para docente
            'codigo' => $docenteRoleId && in_array($docenteRoleId, $request->roles ?? []) ? 'required|string|max:20|unique:docente' : 'nullable',
            'telefono' => $docenteRoleId && in_array($docenteRoleId, $request->roles ?? []) ? 'required|string|max:15' : 'nullable',
            'sueldo' => $docenteRoleId && in_array($docenteRoleId, $request->roles ?? []) ? 'required|numeric|min:0' : 'nullable',
            'fecha_contrato' => $docenteRoleId && in_array($docenteRoleId, $request->roles ?? []) ? 'required|date' : 'nullable',
            'fecha_final' => $docenteRoleId && in_array($docenteRoleId, $request->roles ?? []) ? 'required|date|after:fecha_contrato' : 'nullable',
        ]);
    }

    DB::transaction(function() use ($request, $docenteRoleId) {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_set' => true,
            'email_verified_at' => now(),
        ];

        // Si es coordinador, asignar la misma carrera
        if (Auth::user()->hasRole('coordinador')) {
            $userData['carrera_id'] = Auth::user()->carrera_id;
        }

        $user = User::create($userData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
            
            // ✅ CREAR DOCENTE SI EL ROL ES DOCENTE
            if ($docenteRoleId && in_array($docenteRoleId, $request->roles)) {
                // Validación adicional para asegurar que los campos no estén vacíos
                if (empty($request->codigo)) {
                    // Si llegamos aquí, es un error de validación que debería haberse capturado antes
                    \Log::error('Campo código vacío al crear docente', $request->all());
                    throw new \Exception('Error de validación: el campo código es requerido.');
                }

                Docente::create([
                    'codigo' => $request->codigo,
                    'telefono' => $request->telefono,
                    'sueldo' => $request->sueldo,
                    'fecha_contrato' => $request->fecha_contrato,
                    'fecha_final' => $request->fecha_final,
                    'id_users' => $user->id
                ]);
            }
        }

        // Registrar en bitácora
        BitacoraController::registrarCreacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Usuario {$user->name} creado por " . Auth::user()->name
        );
    });

    return redirect()->route('admin.users.index')
                    ->with('success', 'Usuario creado exitosamente.');
}
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Coordinador solo puede ver usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Coordinador solo puede editar usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        // Coordinador solo puede asignar rol docente
        if (Auth::user()->hasRole('coordinador')) {
            $roles = Role::where('name', 'docente')->get();
        } else {
            $roles = Role::all();
        }

        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
{
    // Coordinador solo puede editar usuarios de su carrera
    if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
        abort(403, 'No tienes permiso para editar este usuario.');
    }

    // Validaciones específicas por rol
    if (Auth::user()->hasRole('coordinador')) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array|size:1',
            'roles.*' => 'in:docente' // Solo puede asignar rol docente
        ]);
    } else {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);
    }

    DB::transaction(function() use ($request, $user) {
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Actualizar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_set'] = true;
        }

        $user->update($updateData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Usuario {$user->name} actualizado por " . Auth::user()->name
        );
    });

    return redirect()->route('admin.users.index')
                    ->with('success', 'Usuario actualizado exitosamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Coordinador NO puede eliminar usuarios
        if (Auth::user()->hasRole('coordinador')) {
            abort(403, 'No tienes permiso para eliminar usuarios.');
        }

        DB::transaction(function() use ($user) {
            $userName = $user->name;
            
            // ✅ NUEVO: Verificar si el usuario tiene un docente relacionado y eliminarlo
            if ($user->docente) {
                $docente = $user->docente;
                
                // Eliminar relaciones del docente
                $docente->carreras()->detach();
                
                // Eliminar el registro del docente
                $docente->delete();
                
                // Registrar en bitácora la eliminación del docente
                BitacoraController::registrarEliminacion(
                    'Docente', 
                    $docente->codigo, 
                    Auth::id(),
                    "Docente {$docente->codigo} eliminado automáticamente al eliminar usuario {$userName}"
                );
            }

            // Eliminar roles del usuario
            $user->roles()->detach();
            
            // Eliminar el usuario
            $user->delete();

            // Registrar en bitácora la eliminación del usuario
            BitacoraController::registrarEliminacion(
                'Usuario', 
                $user->id, 
                Auth::id(),
                "Usuario {$userName} eliminado por " . Auth::user()->name
            );
        });

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario y datos relacionados eliminados exitosamente.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, User $user)
    {
        // Coordinador solo puede resetear contraseñas de usuarios de su carrera
        if (Auth::user()->hasRole('coordinador') && $user->carrera_id !== Auth::user()->carrera_id) {
            abort(403, 'No tienes permiso para resetear la contraseña de este usuario.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_set' => true,
        ]);

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Contraseña actualizada para usuario {$user->name} por " . Auth::user()->name
        );

        return redirect()->route('admin.users.index')
                        ->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Update email verification status
     */
    public function updateVerification(User $user)
    {
        // Coordinador NO puede verificar emails
        if (Auth::user()->hasRole('coordinador')) {
            abort(403, 'No tienes permiso para verificar emails.');
        }

        $newStatus = $user->email_verified_at ? null : now();
        $statusText = $newStatus ? 'verificado' : 'pendiente';
        
        $user->update([
            'email_verified_at' => $newStatus
        ]);

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Estado de verificación actualizado a {$statusText} para usuario {$user->name}"
        );

        return redirect()->route('admin.users.show', $user)
                        ->with('success', "Estado de verificación actualizado a {$statusText}.");
    }

    /**
     * Generate temporal token
     */
    public function generateTemporalToken(User $user)
    {
        // Coordinador NO puede generar tokens
        if (Auth::user()->hasRole('coordinador')) {
            abort(403, 'No tienes permiso para generar tokens temporales.');
        }

        $temporalToken = Str::random(60);
        
        $user->update([
            'temporal_token' => Hash::make($temporalToken),
            'temporal_token_created_at' => now()
        ]);

        // Registrar en bitácora
        BitacoraController::registrarActualizacion(
            'Usuario', 
            $user->id, 
            Auth::id(),
            "Token temporal generado para usuario {$user->name}"
        );

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Token temporal generado exitosamente.')
                        ->with('temporal_token', $temporalToken);
    }
}