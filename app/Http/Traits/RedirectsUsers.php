<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

trait RedirectsUsers
{
    /**
     * Redirige al usuario a la vista correspondiente según su rol principal.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        // El rol con los permisos más altos debe ir primero.
        if ($user->hasRole('Administrador')) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('Coordinador')) {
            return redirect()->route('participante.index'); // Un coordinador podría ir a la lista de participantes
        }

        if ($user->hasRole('Maestro')) {
            return redirect()->route('asistencia.create'); // Un maestro podría ir directamente a tomar asistencia
        }

        // Redirección por defecto para 'Invitado' o cualquier otro rol
        return redirect()->route('home');
    }
}
