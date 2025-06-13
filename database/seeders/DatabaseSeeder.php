<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Importar Role

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, // Llama a tu seeder de Spatie primero
            // Otros seeders que puedas tener...
        ]);

        // Tu usuario de prueba existente
        $testUser = User::factory()->create([
            'name' => 'Ezequiel',
            'email' => 'ezequiel@creanicaragua.org',
            'password' => 'password123',
            'role' => 'Administrador', // Este campo 'role' string se mantiene por ahora
            'approved_at' => now(),
        ]);
        // Ahora asígnale el rol de Spatie también para consistencia
        $adminRole = Role::where('name', 'Administrador')->first();
        if ($adminRole) {
            $testUser->assignRole($adminRole);
        }
    }
}

