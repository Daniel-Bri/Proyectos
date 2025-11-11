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
        // ✅ SOLO ADMIN puede acceder a la gestión de usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $search = $request->get('search');
        
        // Admin ve todos los usuarios
        $users = User::with('roles')
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ SOLO ADMIN puede crear usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ SOLO ADMIN puede crear usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        // Obtener el ID del rol docente
        $docenteRole = Role::where('name', 'docente')->first();
        $docenteRoleId = $docenteRole ? $docenteRole->id : null;

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

        DB::transaction(function() use ($request, $docenteRoleId) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_set' => true,
                'email_verified_at' => now(),
            ];

            $user = User::create($userData);

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
                
                // ✅ CREAR DOCENTE SI EL ROL ES DOCENTE
                if ($docenteRoleId && in_array($docenteRoleId, $request->roles)) {
                    if (empty($request->codigo)) {
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
        // ✅ SOLO ADMIN puede ver usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para ver usuarios.');
        }

        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // ✅ SOLO ADMIN puede editar usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para editar usuarios.');
        }

        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // ✅ SOLO ADMIN puede editar usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para editar usuarios.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

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
        // ✅ SOLO ADMIN puede eliminar usuarios
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar usuarios.');
        }

        DB::transaction(function() use ($user) {
            $userName = $user->name;
            
            // Verificar si el usuario tiene un docente relacionado y eliminarlo
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
        // ✅ SOLO ADMIN puede resetear contraseñas
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para resetear contraseñas.');
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
        // ✅ SOLO ADMIN puede verificar emails
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para verificar emails.');
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
        // ✅ SOLO ADMIN puede generar tokens
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para generar tokens temporales.');
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