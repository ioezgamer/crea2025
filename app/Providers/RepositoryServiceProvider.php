<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ParticipanteRepository;
use App\Services\ParticipanteService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Binding del repositorio y servicio
        $this->app->bind(ParticipanteRepository::class, function($app) {
            return new ParticipanteRepository(
                $app->make(\App\Models\Participante::class)
            );
        });

        $this->app->bind(ParticipanteService::class, function($app) {
            return new ParticipanteService(
                $app->make(ParticipanteRepository::class),
                $app->make('db')
            );
        });
    }

    public function boot() {}
}
