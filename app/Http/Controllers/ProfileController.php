<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $user->fill($request->validated());

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // En lugar de 'status', usamos 'success' para el toast.
            return Redirect::route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return Redirect::route('profile.edit')->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            Auth::logout();
            $user->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            // Se redirige a la raíz, un mensaje flash aquí podría perderse si no hay un sistema para mostrarlo en la página de inicio.
            // Considera si un mensaje es necesario o si la redirección a '/' es suficiente.
            // Si se quiere un mensaje, la página de inicio también necesitaría leer 'sessionMessages'.
            return Redirect::to('/')->with('info', 'Tu cuenta ha sido eliminada.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar cuenta: ' . $e->getMessage(), ['user_id' => $user->id]);
            // Si la eliminación falla, el usuario podría seguir logueado o no.
            // Redirigir a una página de error o de vuelta al perfil con un mensaje.
            return Redirect::route('profile.edit')->with('error', 'No se pudo eliminar tu cuenta: ' . $e->getMessage());
        }
    }
}
