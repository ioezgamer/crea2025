<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstadisticasProgramaController;
use App\Http\Controllers\EstadisticasTutorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;

// --- Rutas Públicas ---
Route::get('/', function () {
    return view('welcome');
});

// --- Rutas Generales para Usuarios Autenticados ---
Route::middleware(['auth'])->group(function () {
    // Página de inicio general
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Gestión del Perfil Propio
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard y Estadísticas (principalmente lectura)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:acceder dashboard');
    Route::get('/programas', [EstadisticasProgramaController::class, 'index'])->name('programas')->middleware('permission:acceder metricas programas');
    Route::get('/programas/lugares', [EstadisticasProgramaController::class, 'getLugaresForPrograma'])->name('programas.lugares')->middleware('permission:acceder metricas programas');
    Route::get('/tutores', [EstadisticasTutorController::class, 'estadisticas'])->name('tutores')->middleware('permission:acceder metricas tutores');
    Route::get('/tutores-participantes', [EstadisticasTutorController::class, 'participantesPorTutor'])->name('tutores_participantes')->middleware('permission:acceder metricas tutores');
    Route::get('/tutores/lugares-por-programa', [EstadisticasTutorController::class, 'fetchPlacesForTutorFilters'])->name('tutores.lugaresPorPrograma')->middleware('permission:acceder metricas tutores');
});


// --- GRUPOS DE RUTAS POR RECURSO ---

// --- Gestión de Participantes ---
Route::middleware(['auth'])->prefix('participante')->name('participante.')->group(function () {
    Route::get('/', [ParticipanteController::class, 'index'])->name('index')->middleware('permission:ver lista participantes');
    Route::get('/consulta', [ParticipanteController::class, 'consulta'])->name('consulta')->middleware('permission:ver lista participantes');
    Route::get('/create', [ParticipanteController::class, 'create'])->name('create')->middleware('permission:crear participante');
    Route::post('/store', [ParticipanteController::class, 'store'])->name('store')->middleware('permission:crear participante');
    Route::get('/por-grado/{grado}', [ParticipanteController::class, 'indexByGrade'])->name('indexByGrade')->middleware('permission:ver lista participantes');

    // Rutas para un participante específico
    Route::get('/{participante}', [ParticipanteController::class, 'show'])->name('show')->middleware('permission:ver detalles participante');
    Route::get('/{participante}/edit', [ParticipanteController::class, 'edit'])->name('edit')->middleware('permission:editar participante');
    Route::put('/{participante}', [ParticipanteController::class, 'update'])->name('update')->middleware('permission:editar participante');
    Route::delete('/{participante}', [ParticipanteController::class, 'destroy'])->name('destroy')->middleware('permission:eliminar participante');

    // Rutas de acciones específicas
    Route::get('/{id}/pdf', [ParticipanteController::class, 'exportPdf'])->name('pdf')->middleware('permission:ver ficha pdf participante');
    Route::post('/toggle-activo', [ParticipanteController::class, 'toggleActivo'])->name('toggle-activo')->middleware('permission:cambiar estado activo participante');

    // Rutas AJAX (se pueden mantener aquí si son específicas de este controlador)
    Route::get('/ajax/lugares-por-programa', [ParticipanteController::class, 'getLugaresByPrograma'])->name('lugaresPorPrograma');
});

// Rutas de Importación/Exportación Masiva (permisos más altos)
Route::middleware(['auth'])->prefix('participantes')->name('participantes.')->group(function() {
    Route::get('/export', [ParticipanteController::class, 'exportParticipantes'])->name('export')->middleware('permission:exportar participantes');
    Route::get('/import', [ParticipanteController::class, 'showImportForm'])->name('import.form')->middleware('permission:importar participantes');
    Route::post('/import', [ParticipanteController::class, 'importParticipantes'])->name('import.store')->middleware('permission:importar participantes');
});


// --- Gestión de Asistencia ---
Route::middleware(['auth'])->prefix('asistencia')->name('asistencia.')->group(function () {
    // Registro de asistencia
    Route::get('/', [AsistenciaController::class, 'create'])->name('create')->middleware('permission:registrar asistencia');
    Route::post('/', [AsistenciaController::class, 'store'])->name('store')->middleware('permission:registrar asistencia');
    Route::post('/guardar-individual', [AsistenciaController::class, 'storeIndividual'])->name('storeIndividual')->middleware('permission:registrar asistencia');

    // Reportes de asistencia
    Route::get('/reporte', [AsistenciaController::class, 'reporte'])->name('reporte')->middleware('permission:ver reportes asistencia');
    Route::get('/reporte/pdf', [AsistenciaController::class, 'exportPdf'])->name('exportPdf')->middleware('permission:exportar pdf asistencia');

    // Rutas AJAX para opciones de filtros
    Route::get('/opciones/lugares', [AsistenciaController::class, 'getLugaresEncuentro'])->name('opciones.lugares');
    Route::get('/opciones/grados', [AsistenciaController::class, 'getGrados'])->name('opciones.grados');
    Route::get('/opciones/participantes', [AsistenciaController::class, 'getParticipantesFiltrados'])->name('opciones.participantes');
});


// --- Gestión de Usuarios y Roles (requieren el permiso más alto) ---
Route::middleware(['auth', 'permission:gestionar usuarios y roles'])->prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::delete('/{user}', [RoleController::class, 'destroy'])->name('destroy');
    Route::post('/{user}', [RoleController::class, 'update'])->name('update'); // Para actualizar rol

    // Subgrupo para acciones sobre 'users' para mayor claridad
    Route::prefix('users')->name('user.')->group(function() {
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::patch('/{user}/approve', [RoleController::class, 'approve'])->name('approve');
        Route::patch('/{user}/unapprove', [RoleController::class, 'unapprove'])->name('unapprove');
    });
});


// --- Archivo de Rutas de Autenticación ---
require __DIR__.'/auth.php';
