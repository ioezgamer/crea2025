<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DashboardController; // <-- Importar
use App\Http\Controllers\EstadisticasProgramaController; // <-- Importar
use App\Http\Controllers\EstadisticasTutorController; // <-- Importar
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/programas', [EstadisticasProgramaController::class, 'index'])->name('programas');
    Route::get('/tutores', [EstadisticasTutorController::class, 'estadisticas'])->name('tutores');
    Route::get('/tutores-participantes', [EstadisticasTutorController::class, 'participantesPorTutor'])->name('tutores_participantes');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/participante/create', [ParticipanteController::class, 'create'])->name('participante.create');
    Route::post('/participante/store', [ParticipanteController::class, 'store'])->name('participante.store');
    Route::get('/participante', [ParticipanteController::class, 'index'])->name('participante.index');
    Route::get('/participante/por-grado/{grado}', [ParticipanteController::class, 'indexByGrade'])->name('participante.indexByGrade');
    Route::get('/participante/{participante}', [ParticipanteController::class, 'show'])->name('participante.show');
    Route::get('/participante/{id}/pdf', [ParticipanteController::class, 'exportPdf'])->name('participante.pdf');

    Route::get('/asistencia', [AsistenciaController::class, 'create'])->name('asistencia.create');
    Route::post('/asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store');
     Route::get('/asistencia', [App\Http\Controllers\AsistenciaController::class, 'create'])->name('asistencia.create');
    // NUEVA RUTA para guardar asistencia individual (AJAX)
    Route::post('/asistencia/guardar-individual', [App\Http\Controllers\AsistenciaController::class, 'storeIndividual'])->name('asistencia.storeIndividual');
    
    // NUEVAS RUTAS para obtener opciones de filtro dinámicas
    Route::get('/asistencia/opciones/lugares', [App\Http\Controllers\AsistenciaController::class, 'getLugaresEncuentro'])->name('asistencia.opciones.lugares');
    Route::get('/asistencia/opciones/grados', [App\Http\Controllers\AsistenciaController::class, 'getGrados'])->name('asistencia.opciones.grados');
    Route::get('/asistencia/opciones/participantes', [App\Http\Controllers\AsistenciaController::class, 'getParticipantesFiltrados'])->name('asistencia.opciones.participantes');
    Route::get('/asistencia/reporte', [AsistenciaController::class, 'reporte'])->name('asistencia.reporte');
    Route::get('/asistencia/reporte/pdf', [AsistenciaController::class, 'exportPdf'])->name('asistencia.exportPdf');

    Route::middleware('can:manage-roles')->group(function () {
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/participante/{participante}/edit', [ParticipanteController::class, 'edit'])->name('participante.edit');
        Route::put('/participante/{participante}', [ParticipanteController::class, 'update'])->name('participante.update');
        Route::delete('/participante/{participante}', [ParticipanteController::class, 'destroy'])->name('participante.destroy');
        Route::post('/participante/toggle-activo', [ParticipanteController::class, 'toggleActivo'])->name('participante.toggle-activo');
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles/{user}', [RoleController::class, 'update'])->name('roles.update'); 
        Route::delete('/roles/{user}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
});

require __DIR__.'/auth.php';