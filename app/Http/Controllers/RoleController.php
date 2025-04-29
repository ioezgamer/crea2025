<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-roles');
    }

    /**
     * Mostrar la lista de usuarios con sus roles.
     */
    public function index(): View
    {
        $users = User::select('id', 'name', 'email', 'role')->get();
        $roles = ['admin', 'editor', 'user'];

        return view('roles.index', compact('users', 'roles'));
    }

    /**
     * Actualizar el rol de un usuario específico.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:admin,editor,user',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('roles.index')->with('status', 'role-updated');
    }
}