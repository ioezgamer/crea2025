<?php

namespace App\Providers;

use App\Models\Participante;
use Illuminate\Support\ServiceProvider;
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
            $tutorsParticipantsCount = Participante::count();
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