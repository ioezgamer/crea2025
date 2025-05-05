<?php

namespace App\Providers;

use App\Models\Asistencia;
use App\Models\Participante;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

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
        $this->registerPolicies();

        // Define the 'manage-roles' Gate
        Gate::define('manage-roles', function ($user) {
            return $user->role === 'admin';
        });

        // View Composer to inject navigation counts
        View::composer('layouts.navigation', function ($view) {
            // Total Participants (for Dashboard)
            $totalParticipants = Participante::count();

            // Active Programs (for Programas)
            $activeProgramsCount = Participante::distinct('programa')->count('programa');

            // Meeting Places (for Participante Index)
            $meetingPlacesCount = Participante::distinct('lugar_de_encuentro_del_programa')->count('lugar_de_encuentro_del_programa');

            // Tutors (for Tutores)
            $tutorsCount = Participante::distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');

            // Tutors and Participants (for Tutores y Participantes)
            $tutorsParticipantsCount = Participante::count(); // Example: total participants linked to tutors

            // Roles (for Roles, only for admins)
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