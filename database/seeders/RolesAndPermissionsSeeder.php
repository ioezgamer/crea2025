<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resetear la caché de roles y permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definir Permisos (basados en las acciones de tus rutas)

        // Permisos Generales
        Permission::firstOrCreate(['name' => 'ver home']); // Para home.blade.php
        Permission::firstOrCreate(['name' => 'gestionar perfil propio']); // Para profile.edit, update, destroy

        // Permisos de Participantes
        Permission::firstOrCreate(['name' => 'ver lista participantes']);
        Permission::firstOrCreate(['name' => 'ver detalles participante']);
        Permission::firstOrCreate(['name' => 'crear participante']);
        Permission::firstOrCreate(['name' => 'editar participante']);
        Permission::firstOrCreate(['name' => 'eliminar participante']);
        Permission::firstOrCreate(['name' => 'exportar participantes']);
        Permission::firstOrCreate(['name' => 'importar participantes']);
        Permission::firstOrCreate(['name' => 'cambiar estado activo participante']);
        Permission::firstOrCreate(['name' => 'ver ficha pdf participante']);

        // Permisos de Asistencia
        Permission::firstOrCreate(['name' => 'registrar asistencia']);
        Permission::firstOrCreate(['name' => 'ver reportes asistencia']);
        Permission::firstOrCreate(['name' => 'exportar pdf asistencia']);

        // Permisos de Usuarios y Roles (Alto Nivel)
        Permission::firstOrCreate(['name' => 'gestionar usuarios y roles']); // Permiso maestro para el antiguo 'can:manage-roles'

        // Permisos de Dashboard y Estadísticas (Alto Nivel)
        Permission::firstOrCreate(['name' => 'acceder dashboard']);
        Permission::firstOrCreate(['name' => 'acceder metricas programas']);
        Permission::firstOrCreate(['name' => 'acceder metricas tutores']);


        // 3. Definir Roles
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleCoordinador = Role::firstOrCreate(['name' => 'Coordinador']);
        $roleFacilitador = Role::firstOrCreate(['name' => 'Facilitador']); // Anteriormente 'Maestro'
        $roleInvitado = Role::firstOrCreate(['name' => 'Invitado']); // Un rol de solo lectura


        // 4. Asignar Permisos a Roles

        // El Administrador tiene acceso a todo.
        $roleAdmin->syncPermissions(Permission::all());

        // El Coordinador tiene amplios permisos de gestión.
        $roleCoordinador->syncPermissions([
            'ver home',
            'gestionar perfil propio',
            'ver lista participantes',
            'ver detalles participante',
            'crear participante',
            'editar participante',
            'exportar participantes',
            'importar participantes',
            'cambiar estado activo participante',
            'ver ficha pdf participante',
            'registrar asistencia',
            'ver reportes asistencia',
            'exportar pdf asistencia',
            'acceder dashboard',
            'acceder metricas programas',
            'acceder metricas tutores',
            // Opcional: Podría gestionar usuarios pero no eliminar administradores
            // 'gestionar usuarios y roles',
        ]);

        // El Maestro tiene permisos centrados en sus participantes y la asistencia.
        $roleFacilitador->syncPermissions([
            'ver home',
            'gestionar perfil propio',
            'ver lista participantes', // Se puede refinar con Policies para que solo vea los suyos
            'ver detalles participante',
            'registrar asistencia',
            'ver reportes asistencia', // Podría ver solo sus reportes
        ]);

        // El rol Invitado solo puede ver información general.
        $roleInvitado->syncPermissions([
            'ver home',
            'gestionar perfil propio',
            'acceder dashboard', // Quizás un dashboard limitado
        ]);
    }
}
