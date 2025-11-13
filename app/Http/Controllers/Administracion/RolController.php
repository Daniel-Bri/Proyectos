<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Administracion\BitacoraController;

class RolController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para gestionar roles.');
        }

        $roles = Role::with('permissions')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $permisos = Permission::all();
        return view('admin.roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array'
        ]);

        $rol = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->permissions) {
            $rol->syncPermissions($request->permissions);
        }

        BitacoraController::registrarCreacion('Rol', $rol->id, auth()->id(), "Rol '{$rol->name}' creado.");

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $rol = Role::findOrFail($id);
        $permisos = Permission::all();
        $rolPermisos = $rol->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('rol', 'permisos', 'rolPermisos'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $rol = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $rol->id,
            'permissions' => 'array'
        ]);

        $rol->update(['name' => $request->name]);
        $rol->syncPermissions($request->permissions ?? []);

        BitacoraController::registrarActualizacion('Rol', $rol->id, auth()->id(), "Rol '{$rol->name}' actualizado.");

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $rol = Role::findOrFail($id);
        $rol->delete();

        BitacoraController::registrarEliminacion('Rol', $id, auth()->id(), "Rol '{$rol->name}' eliminado.");

        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    public function show($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $rol = Role::with('permissions')->findOrFail($id);
        return view('admin.roles.show', compact('rol'));
    }
}