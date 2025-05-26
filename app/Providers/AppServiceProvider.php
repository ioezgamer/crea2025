<?php

namespace App\Providers;

use App\Models\Participante;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL; // <-- ASEGÚRATE DE TENER ESTA LÍNEA

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
        // Coloca esta lógica al principio del método boot o donde consideres apropiado.
        if ($this->app->environment('production') || $this->app->environment('staging') /* u otro nombre de entorno que uses en Railway */) {
            URL::forceScheme('https');
        }

        // Define the 'manage-roles' Gate
        Gate::define('manage-roles', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('create-user', function ($user) {
            return $user->role === 'admin';
        });

        // View Composer to inject navigation counts
        View::composer('layouts.navigation', function ($view) {
            $totalParticipants = Participante::count();
            $activeProgramsCount = Participante::distinct('programa')->count('programa');
            $meetingPlacesCount = Participante::distinct('lugar_de_encuentro_del_programa')->count('lugar_de_encuentro_del_programa');
            $tutorsCount = Participante::distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');
            $tutorsParticipantsCount = Participante::count(); // Esto parece ser lo mismo que totalParticipants
            $rolesCount = auth()->check() && auth()->user()->role === 'admin' ? \App\Models\User::count() : 0;

            $view->with(compact(
                'totalParticipants',
                'activeProgramsCount',
                'meetingPlacesCount',
                'tutorsCount',
                'tutorsParticipantsCount',
                'rolesCount'
            ));
        });
    }
}