<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Administracion\BitacoraController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-roles')->only(['index', 'show']);
        $this->middleware('permission:crear-roles')->only(['create', 'store']);
        $this->middleware('permission:editar-roles')->only(['edit', 'update']);
        $this->middleware('permission:eliminar-roles')->only(['destroy']);
        $this->middleware('permission:asignar-permisos')->only(['asignarPermisos']);
    }

    // Muestra la lista de roles
    public function index(Request $request)
    {
        $query = Role::with('permissions', 'creador')
            ->withCount(['users', 'permissions'])
            ->orderBy('tipo')
            ->orderBy('name');

        // Filtros de búsqueda
        if ($request->filled('codigo')) {
            $query->where('id', $request->codigo);
        }

        if ($request->filled('nombre')) {
            $query->where('name', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $roles = $query->paginate(20);

        return view('admin.roles.index', compact('roles'));
    }

    // Muestra el formulario para crear un nuevo rol
    public function create()
    {
        $permisos = Permission::orderBy('name')->get()->groupBy(function($permiso) {
            $parts = explode('-', $permiso->name);
            return $parts[0] ?? 'general';
        });

        return view('admin.roles.create', compact('permisos'));
    }

    // Almacena un nuevo rol en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'tipo' => 'required|in:sistema,personalizado',
            'estado' => 'required|in:activo,inactivo',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $rol = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'description' => $request->description,
                'tipo' => $request->tipo,
                'estado' => $request->estado,
                'created_by' => auth()->id()
            ]);

            if ($request->has('permisos')) {
                $permisos = Permission::whereIn('id', $request->permisos)->get();
                $rol->syncPermissions($permisos);
            }

            // Registrar en bitácora
            BitacoraController::registrarCreacion(
                'Rol',
                $rol->id,
                auth()->id(),
                "Rol {$rol->name} creado con " . ($rol->permissions->count()) . " permisos"
            );

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Rol creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Muestra los detalles de un rol específico
    public function show(Role $role)
    {
        $role->load(['permissions', 'creador', 'users']);
        
        return view('admin.roles.show', compact('role'));
    }

    // Muestra el formulario para editar un rol
    public function edit(Role $role)
    {
        if ($role->esSistema() && !auth()->user()->hasRole('admin')) {
            abort(403, 'No puedes editar roles del sistema.');
        }

        $permisos = Permission::orderBy('name')->get()->groupBy(function($permiso) {
            $parts = explode('-', $permiso->name);
            return $parts[0] ?? 'general';
        });

        $rolPermisos = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permisos', 'rolPermisos'));
    }

    // Actualiza el rol en la base de datos
    public function update(Request $request, Role $role)
    {
        if ($role->esSistema() && !auth()->user()->hasRole('admin')) {
            abort(403, 'No puedes editar roles del sistema.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id)
            ],
            'description' => 'nullable|string|max:500',
            'tipo' => 'required|in:sistema,personalizado',
            'estado' => 'required|in:activo,inactivo',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $datosAnteriores = $role->toArray();

            $role->update([
                'name' => $request->name,
                'description' => $request->description,
                'tipo' => $request->tipo,
                'estado' => $request->estado
            ]);

            if ($request->has('permisos')) {
                $permisos = Permission::whereIn('id', $request->permisos)->get();
                $role->syncPermissions($permisos);
            } else {
                $role->syncPermissions([]);
            }

            // Registrar en bitácora
            BitacoraController::registrarActualizacion(
                'Rol',
                $role->id,
                auth()->id(),
                "Rol {$role->name} actualizado. Permisos: " . ($role->permissions->count())
            );

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Rol actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Elimina un rol
    public function destroy(Role $role)
    {
        if ($role->esSistema()) {
            return redirect()->back()
                ->with('error', 'No se pueden eliminar roles del sistema.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        try {
            $nombreRol = $role->name;
            
            // Registrar en bitácora antes de eliminar
            BitacoraController::registrarEliminacion(
                'Rol',
                $role->id,
                auth()->id(),
                "Rol {$nombreRol} eliminado"
            );

            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Rol eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }

    // Asignar permisos a un rol (API)
    public function asignarPermisos(Request $request, Role $role)
    {
        $request->validate([
            'permisos' => 'required|array',
            'permisos.*' => 'exists:permissions,id'
        ]);

        try {
            $permisos = Permission::whereIn('id', $request->permisos)->get();
            $role->syncPermissions($permisos);

            // Registrar en bitácora
            BitacoraController::registrarActualizacion(
                'Rol',
                $role->id,
                auth()->id(),
                "Permisos asignados al rol {$role->name}. Total: " . count($request->permisos)
            );

            return response()->json([
                'success' => true,
                'message' => 'Permisos asignados exitosamente.',
                'permisos_count' => $role->permissions->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar permisos: ' . $e->getMessage()
            ], 500);
        }
    }
}