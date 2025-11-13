<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /* -----------------------------------------------------------------
         | PERMISOS PARA SISTEMA DE GESTIÓN DE DOCENTES Y HORARIOS
         -----------------------------------------------------------------*/
        $permissions = [
            // Módulo: Usuarios y Autenticación
            'gestionar-usuarios',
            'ver-usuarios',
            'crear-usuarios', 
            'editar-usuarios',
            'eliminar-usuarios',
            'activar-usuarios',

            // Módulo: Roles y Permisos
            'gestionar-roles',
            'ver-roles',
            'crear-roles',
            'editar-roles',
            'eliminar-roles',
            'asignar-roles',
            'gestionar-permisos',

            // Módulo: Docentes
            'gestionar-docentes',
            'ver-docentes',
            'crear-docentes',
            'editar-docentes',
            'eliminar-docentes',
            'ver-perfil-docente',
            'editar-perfil-docente',

            // Módulo: Materias
            'gestionar-materias',
            'ver-materias',
            'crear-materias',
            'editar-materias',
            'eliminar-materias',

            // Módulo: Horarios
            'gestionar-horarios',
            'ver-horarios',
            'crear-horarios',
            'editar-horarios',
            'eliminar-horarios',
            'asignar-horarios',
            'ver-horario-personal',

            // Módulo: Aulas
            'gestionar-aulas',
            'ver-aulas',
            'crear-aulas',
            'editar-aulas',
            'eliminar-aulas',

            // Módulo: Grupos
            'gestionar-grupos',
            'ver-grupos',
            'crear-grupos',
            'editar-grupos',
            'eliminar-grupos',

            // Módulo: Asistencias
            'gestionar-asistencias',
            'ver-asistencias',
            'registrar-asistencias',
            'editar-asistencias',
            'reporte-asistencias',

            // Módulo: Categorías de Docentes
            'gestionar-categorias',
            'ver-categorias',
            'crear-categorias',
            'editar-categorias',
            'eliminar-categorias',

            // Módulo: Carreras
            'gestionar-carreras',
            'ver-carreras',
            'crear-carreras',
            'editar-carreras',
            'eliminar-carreras',

            // Dashboard y Reportes
            'ver-dashboard-admin',
            'ver-dashboard-coordinador',
            'ver-dashboard-docente',
            'generar-reportes',
            'ver-reportes',

            // Configuración del Sistema
            'configurar-sistema',
            'ver-configuracion',
            'editar-configuracion',
        ];

        /* -----------------------------------------------------------------
         | Registrar permisos únicos
         -----------------------------------------------------------------*/
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        /* -----------------------------------------------------------------
         | ROLES DEL SISTEMA EDUCATIVO
         -----------------------------------------------------------------*/
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $coordinador = Role::firstOrCreate(['name' => 'coordinador']);
        $docente = Role::firstOrCreate(['name' => 'docente']);

        /* -----------------------------------------------------------------
         | ASIGNAR PERMISOS POR ROL
         -----------------------------------------------------------------*/
        
        // 1) ADMIN - Todos los permisos
        $admin->syncPermissions(Permission::all());

        // 2) COORDINADOR - Gestión académica
        $coordinador->syncPermissions([
            // Usuarios (solo ver)
            'ver-usuarios',
            
            // Docentes
            'gestionar-docentes',
            'ver-docentes',
            'crear-docentes',
            'editar-docentes',
            
            // Materias
            'gestionar-materias',
            'ver-materias',
            'crear-materias',
            'editar-materias',
            
            // Horarios
            'gestionar-horarios',
            'ver-horarios',
            'crear-horarios',
            'editar-horarios',
            'asignar-horarios',
            
            // Aulas
            'gestionar-aulas',
            'ver-aulas',
            'crear-aulas',
            'editar-aulas',
            
            // Grupos
            'gestionar-grupos',
            'ver-grupos',
            'crear-grupos',
            'editar-grupos',
            
            // Asistencias
            'gestionar-asistencias',
            'ver-asistencias',
            'reporte-asistencias',
            
            // Categorías
            'ver-categorias',
            
            // Carreras
            'gestionar-carreras',
            'ver-carreras',
            'crear-carreras',
            'editar-carreras',
            
            // Dashboard y Reportes
            'ver-dashboard-coordinador',
            'generar-reportes',
            'ver-reportes',
        ]);

        // 3) DOCENTE - Acceso limitado a su información
        $docente->syncPermissions([
            // Perfil personal
            'ver-perfil-docente',
            'editar-perfil-docente',
            
            // Horarios personales
            'ver-horario-personal',
            'ver-horarios',
            
            // Asistencias (solo registrar las propias)
            'registrar-asistencias',
            'ver-asistencias',
            
            // Materias y grupos (solo ver)
            'ver-materias',
            'ver-grupos',
            
            // Dashboard personal
            'ver-dashboard-docente',
        ]);

        /* -----------------------------------------------------------------
         | CREAR USUARIO ADMIN POR DEFECTO
         -----------------------------------------------------------------*/
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sistema.edu'],
            [
                'name' => 'Administrador Sistema',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'password_set' => true,
            ]
        );
        
        $adminUser->assignRole('admin');

        /* -----------------------------------------------------------------
         | CREAR USUARIO COORDINADOR POR DEFECTO
         -----------------------------------------------------------------*/
        $coordinadorUser = User::firstOrCreate(
            ['email' => 'coordinador@sistema.edu'],
            [
                'name' => 'Coordinador Académico',
                'password' => Hash::make('Coordinador123*'),
                'email_verified_at' => now(),
                'password_set' => true,
            ]
        );
        
        $coordinadorUser->assignRole('coordinador');
    }
}