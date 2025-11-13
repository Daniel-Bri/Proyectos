<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Usar firstOrCreate para evitar duplicados
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $coordinador = Role::firstOrCreate(['name' => 'coordinador', 'guard_name' => 'web']);
        $docente = Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'web']);

        // Opcional: Crear permisos básicos si no existen
        $permissions = [
            'ver-dashboard',
            'gestionar-docentes', 
            'gestionar-materias',
            'gestionar-horarios',
            'registrar-asistencia',
            'generar-reportes'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Asignar todos los permisos al admin (si no están asignados)
        $admin->givePermissionTo($permissions);
        
        $this->command->info('Roles y permisos configurados correctamente.');
    }
}