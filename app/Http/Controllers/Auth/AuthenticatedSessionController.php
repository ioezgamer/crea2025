<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Traits\RedirectsUsers; // <-- 1. Importar nuestro Trait

class AuthenticatedSessionController extends Controller
{
    use RedirectsUsers; // <-- 2. Usar nuestro Trait

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Este método valida y autentica al usuario
        $request->authenticate();

        // Regenera la sesión para seguridad
        session()->regenerate();

        // --- LÓGICA DE REDIRECCIÓN CORREGIDA ---
        // En lugar de redirigir a una ruta fija, obtenemos el usuario
        // y usamos nuestro Trait para decidir a dónde enviarlo.
        $user = Auth::user();
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
