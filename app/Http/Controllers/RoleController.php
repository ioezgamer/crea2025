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
use Spatie\Permission\Models\Role; // <-- AÑADIDO: Importar el modelo Role de Spatie

class RoleController extends Controller
{
    public function __construct()
    {
        // Este middleware ahora dependerá del Gate 'manage-roles' que usa Spatie
        $this->middleware('can:manage-roles');
    }

    public function index(Request $request): View
    {
        $query = User::query()->with('roles'); // Cargar la relación de roles de Spatie para eficiencia

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

        if ($request->filled('role')) { // Nuevo: Filtrar por rol de Spatie
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = Role::orderBy('name')->pluck('name')->all(); // <-- CORREGIDO: Obtener roles de la tabla de Spatie

        $totalUsers = User::count();
        $approvedUsers = User::approved()->count();
        $pendingUsers = User::pendingApproval()->count();

        return view('roles.index', compact(
            'users',
            'roles', // Se pasa la lista de roles de Spatie
            'totalUsers',
            'approvedUsers',
            'pendingUsers'
        ));
    }

    // CORREGIDO: Nombre del método para coincidir con la ruta roles.user.create
    public function create(): View
    {
        Gate::authorize('manage-roles'); // Reemplaza 'create-user' para consistencia
        $roles = Role::orderBy('name')->pluck('name')->all(); // Obtener roles de Spatie para el dropdown
        return view('roles.create_user', compact('roles'));
    }

    // CORREGIDO: Nombre del método para coincidir con la ruta roles.user.store
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-roles');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'], // <-- CORREGIDO: Validar contra la tabla 'roles' de Spatie
            'approve_now' => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'approved_at' => $request->boolean('approve_now') ? now() : null,
            // El campo 'role' antiguo se puede dejar nulo o manejarlo como un rol por defecto si es necesario
        ]);

        $user->assignRole($request->role); // <-- CORREGIDO: Asignar rol usando Spatie

        return redirect()->route('roles.index')->with('success', 'Usuario creado exitosamente.'); // Estandarizado a 'success'
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // Evitar que un usuario se quite a sí mismo el rol de Administrador si es el único
        if (Auth::user()->id === $user->id && $user->hasRole('Administrador')) {
            $adminRole = Role::where('name', 'Administrador')->first();
            if ($adminRole && $adminRole->users()->count() === 1) {
                 return back()->with('error', 'No puedes quitarte el rol de Administrador porque eres el único.');
            }
        }

        $user->syncRoles([$request->role]); // <-- CORREGIDO: Sincronizar (asignar) el nuevo rol usando Spatie

        return back()->with('success', 'Rol del usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Evitar que un usuario se elimine a sí mismo
        if (Auth::user()->id === $user->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Evitar eliminar al único administrador
        if ($user->hasRole('Administrador')) {
            $adminRole = Role::where('name', 'Administrador')->first();
            if ($adminRole && $adminRole->users()->count() === 1) {
                return back()->with('error', 'No se puede eliminar al único administrador.');
            }
        }

        $user->delete();

        return redirect()->route('roles.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->approved_at) {
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
        // CORREGIDO: Usar hasRole para la lógica
        if ($user->hasRole('Administrador') && Role::where('name', 'Administrador')->first()->users()->whereNotNull('approved_at')->count() === 1 && $user->approved_at) {
             return redirect()->route('roles.index')->with('error', 'No se puede revocar la aprobación del único administrador aprobado.');
        }

        if (!$user->approved_at) {
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
