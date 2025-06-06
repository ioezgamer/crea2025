<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-roles');
    }

    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('approval_status')) {
            if ($request->approval_status === 'pending') {
                $query->pendingApproval();
            } elseif ($request->approval_status === 'approved') {
                $query->approved();
            }
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = ['admin', 'editor', 'gestor', 'user'];

        $totalUsers = User::count();
        $approvedUsers = User::approved()->count();
        $pendingUsers = User::pendingApproval()->count();

        return view('roles.index', compact(
            'users',
            'roles',
            'totalUsers',
            'approvedUsers',
            'pendingUsers'
        ));
    }

    public function create(): View|RedirectResponse
    {
        if (Gate::denies('create-user')) {
            return redirect()->route('roles.index')->with('error', 'No tienes permiso para crear usuarios.');
        }
        $roles = ['admin', 'editor', 'gestor', 'user'];
        return view('roles.create_user', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        Log::info('RoleController@store: Solicitud recibida para crear usuario.', $request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,editor,gestor,user'],
            'approve_now' => ['nullable', 'boolean'],
        ]);

        $approvedAt = null;
        if ($request->role === 'admin' || ($request->boolean('approve_now') && Auth::user()->can('manage-roles'))) {
            $approvedAt = now();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'approved_at' => $approvedAt,
            ]);
            Log::info('RoleController@store: Usuario creado exitosamente.', ['user_id' => $user->id]);

            $message = 'Usuario creado exitosamente.';
            $message .= $approvedAt ? ' El usuario ha sido aprobado.' : ' El usuario está pendiente de aprobación.';
            return redirect()->route('roles.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->with('error', 'Error al crear el usuario: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id && Auth::user()->role === 'admin' && $request->role !== 'admin') {
             return redirect()->route('roles.index')->with('error', 'Un administrador no puede cambiar su propio rol a uno inferior.');
        }
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1 && $request->role !== 'admin') {
            return redirect()->route('roles.index')->with('error', 'No se puede cambiar el rol del único administrador.');
        }
        $request->validate(['role' => 'required|in:admin,editor,gestor,user']);

        try {
            $user->update(['role' => $request->role]);
            return redirect()->route('roles.index')->with('success', 'Rol de usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar rol para usuario ID {$user->id}: " . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Error al actualizar el rol del usuario.');
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar al único administrador.');
        }
        if (Auth::user()->id === $user->id) {
            return redirect()->route('roles.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }
        try {
            $user->delete();
            return redirect()->route('roles.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar usuario ID {$user->id}: " . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Error al eliminar el usuario.');
        }
    }

    public function approve(User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id && $user->isApproved()) {
             return redirect()->route('roles.index')->with('info', 'Este usuario ya está aprobado y no puedes cambiar tu propia aprobación si eres tú mismo.');
        }
        if ($user->isApproved()) {
            return redirect()->route('roles.index')->with('info', 'Este usuario ya está aprobado.');
        }
        try {
            $user->update(['approved_at' => now()]);
            return redirect()->route('roles.index')->with('success', 'Usuario aprobado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al aprobar usuario ID {$user->id}: " . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Error al aprobar el usuario.');
        }
    }

    public function unapprove(User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id && $user->role === 'admin') {
            $otherApprovedAdmins = User::approved()->where('role', 'admin')->where('id', '!=', $user->id)->count();
            if ($otherApprovedAdmins === 0) {
                 return redirect()->route('roles.index')->with('error', 'No se puede revocar la aprobación del único administrador activo.');
            }
        }
        if ($user->role === 'admin' && User::approved()->where('role', 'admin')->count() === 1 && $user->isApproved()) {
             return redirect()->route('roles.index')->with('error', 'No se puede revocar la aprobación del único administrador aprobado.');
        }
        if (!$user->isApproved()) {
            return redirect()->route('roles.index')->with('info', 'Este usuario no está aprobado actualmente.');
        }
        try {
            $user->update(['approved_at' => null]);
            return redirect()->route('roles.index')->with('success', 'Aprobación de usuario revocada.');
        } catch (\Exception $e) {
            Log::error("Error al revocar aprobación para usuario ID {$user->id}: " . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Error al revocar la aprobación del usuario.');
        }
    }
}
