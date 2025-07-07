<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Participante;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
// <-- AÑADIDO: Importar el modelo Role si se necesita

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS en producción o en tu entorno de Railway
        if ($this->app->environment('production') || $this->app->environment('staging')) {
            URL::forceScheme('https');
        }

        // --- GATES DE AUTORIZACIÓN (usando Spatie) ---
        // Se asegura de que incluso si usas `can:manage-roles` en tus rutas/vistas,
        // la lógica subyacente utilice el sistema de permisos de Spatie.

        // El Gate 'manage-roles' ahora verifica un permiso de Spatie
        Gate::define('manage-roles', function (User $user) {
            // Un usuario puede gestionar roles si tiene el permiso 'gestionar usuarios y roles'
            return $user->hasPermissionTo('gestionar usuarios y roles');
        });

        // Este Gate es redundante si 'manage-roles' ya cubre la creación, pero lo mantenemos si lo usas explícitamente.
        Gate::define('create-user', function (User $user) {
            return $user->hasPermissionTo('gestionar usuarios y roles'); // O un permiso más específico como 'crear usuario'
        });


        // --- VIEW COMPOSER PARA DATOS GLOBALES EN LA NAVEGACIÓN ---
        // Inyecta variables en 'layouts.navigation' cada vez que se renderiza.
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // --- CORREGIDO: Lógica para el contador de la sección de usuarios/roles ---
                // Solo los usuarios con el permiso adecuado verán el conteo.
                $managedUsersCount = 0;
                if ($user->hasPermissionTo('gestionar usuarios y roles')) {
                    $managedUsersCount = User::count(); // El conteo de todos los usuarios para el admin
                }

                // --- AÑADIDO: Lógica para el sistema de notificaciones ---
                // Obtiene las 5 notificaciones no leídas más recientes para el dropdown
                $unreadNotifications = $user->unreadNotifications()->take(5)->get();
                // Obtiene el conteo total para el badge de la campana
                $unreadNotificationsCount = $user->unreadNotifications()->count();
$activeProgramsCount = Participante::whereNotNull('programa')
    ->pluck('programa')
    ->flatMap(function ($item) {
        return collect(explode(',', $item))
            ->map(fn($p) => trim(Str::lower($p))); // limpieza
    })
    ->unique()
    ->count();
                // Pasar todas las variables a la vista de navegación
                $view->with([
                    'totalParticipants' => Participante::count(),
                    'activeProgramsCount' => $activeProgramsCount, // <-- CORREGIDO Y RENOMBRADO
                    'managedUsersCount' => $managedUsersCount, // <-- CORREGIDO Y RENOMBRADO
                    'unreadNotifications' => $unreadNotifications, // <-- AÑADIDO
                    'unreadNotificationsCount' => $unreadNotificationsCount, // <-- AÑADIDO
                ]);

            } else {
                // Proporcionar valores por defecto para usuarios no autenticados (invitados)
                $view->with([
                    'totalParticipants' => 0,
                    'activeProgramsCount' => 0,
                    'managedUsersCount' => 0,
                    'unreadNotifications' => collect(),
                    'unreadNotificationsCount' => 0,
                ]);
            }
        });
    }
}
