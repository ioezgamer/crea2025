<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstadisticasProgramaController;
use App\Http\Controllers\EstadisticasTutorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grupo principal de rutas que requieren autenticación y, en este caso, el permiso 'can:manage-roles'
// Si algunas rutas de tutores no necesitan 'can:manage-roles', considera un grupo anidado o moverlas.
Route::middleware(['auth', 'can:manage-roles'])->group(function () { // Asumiendo que todas estas rutas requieren 'can:manage-roles'
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/programas', [EstadisticasProgramaController::class, 'index'])->name('programas');
    
    // Rutas para Estadísticas de Tutores
    Route::get('/tutores', [EstadisticasTutorController::class, 'estadisticas'])->name('tutores');
    Route::get('/tutores-participantes', [EstadisticasTutorController::class, 'participantesPorTutor'])->name('tutores_participantes');
    // Nueva ruta para la funcionalidad AJAX de cargar lugares dinámicamente en la vista de estadísticas de tutores
    Route::get('/tutores/lugares-por-programa', [EstadisticasTutorController::class, 'fetchPlacesForTutorFilters'])->name('tutores.lugaresPorPrograma');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- GRUPO DE RUTAS PARA PARTICIPANTES ---
    // Estas rutas son para usuarios autenticados y verificados en general
    // Si estas rutas también requieren 'can:manage-roles', está bien aquí.
    // Si no, podrían estar en un grupo 'auth', 'verified' más general fuera de 'can:manage-roles'.
    Route::get('/participante/create', [ParticipanteController::class, 'create'])->name('participante.create');
    Route::post('/participante/store', [ParticipanteController::class, 'store'])->name('participante.store');
    Route::get('/participante', [ParticipanteController::class, 'index'])->name('participante.index');
    Route::get('/participante/lugares-por-programa', [ParticipanteController::class, 'getLugaresByPrograma'])->name('participante.lugaresPorPrograma');
    Route::get('/participante/por-grado/{grado}', [ParticipanteController::class, 'indexByGrade'])->name('participante.indexByGrade');
    Route::get('/participante/{participante}', [ParticipanteController::class, 'show'])->name('participante.show');
    Route::get('/participante/{id}/pdf', [ParticipanteController::class, 'exportPdf'])->name('participante.pdf');

    // --- RUTAS DE ASISTENCIA ---
    // Similar a participantes, verificar si 'can:manage-roles' es necesario para todas.
    Route::get('/asistencia', [AsistenciaController::class, 'create'])->name('asistencia.create');
    Route::post('/asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store');
    Route::post('/asistencia/guardar-individual', [AsistenciaController::class, 'storeIndividual'])->name('asistencia.storeIndividual');
    Route::get('/asistencia/opciones/lugares', [AsistenciaController::class, 'getLugaresEncuentro'])->name('asistencia.opciones.lugares');
    Route::get('/asistencia/opciones/grados', [AsistenciaController::class, 'getGrados'])->name('asistencia.opciones.grados');
    Route::get('/asistencia/opciones/participantes', [AsistenciaController::class, 'getParticipantesFiltrados'])->name('asistencia.opciones.participantes');
    Route::get('/asistencia/reporte', [AsistenciaController::class, 'reporte'])->name('asistencia.reporte');
    Route::get('/asistencia/reporte/pdf', [AsistenciaController::class, 'exportPdf'])->name('asistencia.exportPdf');
    
    // --- RUTAS PROTEGIDAS ADICIONALMENTE POR 'can:manage-roles' ---
    // Este grupo ya tiene 'can:manage-roles' del grupo padre, por lo que el middleware aquí es redundante
    // a menos que quieras añadir más middlewares específicos para este subgrupo.
    // Por simplicidad, he asumido que el middleware del grupo padre es suficiente.
    // Route::middleware('can:manage-roles')->group(function () { // Este middleware anidado es redundante si el padre ya lo tiene
        // Rutas de participante que requieren 'manage-roles'
        Route::get('/participante/{participante}/edit', [ParticipanteController::class, 'edit'])->name('participante.edit');
        Route::put('/participante/{participante}', [ParticipanteController::class, 'update'])->name('participante.update');
        Route::delete('/participante/{participante}', [ParticipanteController::class, 'destroy'])->name('participante.destroy');
        Route::post('/participante/toggle-activo', [ParticipanteController::class, 'toggleActivo'])->name('participante.toggle-activo');
        
        // **Rutas para crear usuarios (colocadas correctamente aquí)**
        Route::get('/roles/users/create', [RoleController::class, 'create'])->name('roles.user.create');
        Route::post('/roles/users', [RoleController::class, 'store'])->name('roles.user.store');

        // Rutas para gestión de roles y usuarios
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles/{user}', [RoleController::class, 'update'])->name('roles.update'); 
        Route::delete('/roles/{user}', [RoleController::class, 'destroy'])->name('roles.destroy');
        
        // NUEVAS RUTAS PARA APROBACIÓN DE USUARIOS
        Route::patch('/roles/users/{user}/approve', [RoleController::class, 'approve'])->name('roles.user.approve');
        Route::patch('/roles/users/{user}/unapprove', [RoleController::class, 'unapprove'])->name('roles.user.unapprove');
        
        // Rutas de exportación e importación para participantes
        Route::get('/participantes/export', [ParticipanteController::class, 'exportParticipantes'])->name('participantes.export');
        Route::get('/participantes/import', [ParticipanteController::class, 'showImportForm'])->name('participantes.import.form');
        Route::post('/participantes/import', [ParticipanteController::class, 'importParticipantes'])->name('participantes.import.store');
    // }); // Fin del grupo anidado redundante
});

require __DIR__.'/auth.php';
