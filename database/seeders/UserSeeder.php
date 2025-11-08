<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usar firstOrCreate para evitar duplicados
        $admin = User::firstOrCreate(
            ['email' => 'admin@ficct.edu.bo'],
            [
                'name' => 'Admin Sistema',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        $docente1 = User::firstOrCreate(
            ['email' => 'jperez@ficct.edu.bo'],
            [
                'name' => 'Juan Perez',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        $docente2 = User::firstOrCreate(
            ['email' => 'mlopez@ficct.edu.bo'],
            [
                'name' => 'Maria Lopez', 
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        $coordinador = User::firstOrCreate(
            ['email' => 'coordinador@gmail.com'],
            [
                'name' => 'Coordinador', 
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar roles si usas Spatie Permission
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $admin->assignRole('admin');
            $docente1->assignRole('docente');
            $docente2->assignRole('docente');
            $coordinador->assignRole('coordinador');
        }
    }
}