<?php

// Contenido de app/Http/Controllers/RoleController.php
// No se realizaron cambios funcionales en este archivo, ya que la estructura del método store() es correcta.
// Se añaden comentarios para enfatizar puntos de depuración.

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Asegúrate que Gate está correctamente configurado en AuthServiceProvider
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Para depuración

class RoleController extends Controller
{
    public function __construct()
    {
        // Este middleware se aplica a TODOS los métodos en este controlador.
        // Si hay un problema con la Gate 'manage-roles', podría causar errores inesperados.
        // Asegúrate que la lógica en app/Providers/AuthServiceProvider.php para 'manage-roles' es correcta
        // y no lanza excepciones no controladas.
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
        return view('roles.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        // Este método muestra el formulario. Si puedes ver el formulario,
        // la Gate 'manage-roles' permitió el acceso hasta aquí para la solicitud GET.
        $roles = ['admin', 'editor', 'gestor', 'user'];
        return view('roles.create_user', compact('roles')); 
    }

    public function store(Request $request): RedirectResponse
    {
        // Si obtienes un 404 aquí, significa que la solicitud POST a '/roles/users'
        // no está siendo manejada correctamente por esta ruta/método.
        // Pasos de depuración cruciales:
        // 1. Limpiar caché de rutas: `php artisan route:clear`
        // 2. Revisar logs de Laravel: `storage/logs/laravel.log` para errores detallados.
        // 3. Verificar la configuración del servidor web (.htaccess para Apache, config de Nginx).
        // 4. Asegurar que no haya errores en la lógica de la Gate 'manage-roles'.

        // Puedes añadir un log para verificar si la solicitud llega aquí:
        Log::info('RoleController@store: Solicitud recibida para crear usuario.', $request->all());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,editor,gestor,user'],
            'approve_now' => ['nullable', 'boolean'], 
        ]);

        $approvedAt = null; 

        if ($request->role === 'admin') {
            $approvedAt = now();
        } 
        elseif ($request->boolean('approve_now')) {
            $approvedAt = now();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'approved_at' => $approvedAt,
        ]);
       dd($request->all()); // Esto mostrará los datos de la solicitud y detendrá la ejecución
        Log::info('RoleController@store: Usuario creado exitosamente.', ['user_id' => $user->id]);

        return redirect()->route('roles.index')->with('status', 'user-created');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id && Auth::user()->role === 'admin' && $request->role !== 'admin') {
             return redirect()->route('roles.index')->with('error', 'Un administrador no puede cambiar su propio rol a uno inferior.');
        }
        
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1 && $request->role !== 'admin') {
            return redirect()->route('roles.index')->with('error', 'No se puede cambiar el rol del único administrador.');
        }

        $request->validate([
            'role' => 'required|in:admin,editor,gestor,user',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('roles.index')->with('status', 'role-updated');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            if (User::where('role', 'admin')->count() === 1) {
                 return redirect()->route('roles.index')->with('error', 'No se puede eliminar al único administrador.');
            }
        }
        if (Auth::user()->id === $user->id) {
            return redirect()->route('roles.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();
        return redirect()->route('roles.index')->with('status', 'user-deleted');
    }

    public function approve(User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id && $user->isApproved()) {
             return redirect()->route('roles.index')->with('info', 'Este usuario ya está aprobado.');
        }
        $user->update(['approved_at' => now()]);
        return redirect()->route('roles.index')->with('status', 'user-approved');
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

        $user->update(['approved_at' => null]);
        return redirect()->route('roles.index')->with('status', 'user-approval-revoked');
    }
}