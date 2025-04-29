<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Models\Participante;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalParticipants = Participante::count();
    $participantsByProgram = Participante::groupBy('programa')
        ->selectRaw('programa, count(*) as count')
        ->pluck('count', 'programa')
        ->toArray();
    $participantsByPlace = Participante::groupBy('lugar_de_encuentro_del_programa')
        ->selectRaw('lugar_de_encuentro_del_programa, count(*) as count')
        ->pluck('count', 'lugar_de_encuentro_del_programa')
        ->toArray();

    return view('dashboard', compact('totalParticipants', 'participantsByProgram', 'participantsByPlace'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/programas', function () {
    $selectedProgram = request('programa');
    $query = Participante::query();

    $programs = Participante::distinct()->pluck('programa')->toArray();

    if ($selectedProgram) {
        $query->where('programa', $selectedProgram);
    }

    $totalParticipants = $query->count();
    $participantsByProgram = Participante::groupBy('programa')
        ->selectRaw('programa, count(*) as count')
        ->pluck('count', 'programa')
        ->toArray();
    $participantsByGrade = $query->clone()->groupBy('grado_p')
        ->selectRaw('grado_p, count(*) as count')
        ->pluck('count', 'grado_p')
        ->toArray();
    $participantsByGender = $query->clone()->groupBy('genero')
        ->selectRaw('genero, count(*) as count')
        ->pluck('count', 'genero')
        ->toArray();
    $averageAge = $query->avg('edad_p');
    $participantsByAgeGroup = $query->clone()->selectRaw('
        CASE 
            WHEN edad_p < 10 THEN \'Menor a 10 años\'
            WHEN edad_p BETWEEN 10 AND 14 THEN \'10-14 años\'
            WHEN edad_p BETWEEN 15 AND 18 THEN \'15-18 años\'
            ELSE \'Mayor a 18 años\'
        END as age_group, 
        count(*) as count')
        ->groupByRaw('
        CASE 
            WHEN edad_p < 10 THEN \'Menor a 10 años\'
            WHEN edad_p BETWEEN 10 AND 14 THEN \'10-14 años\'
            WHEN edad_p BETWEEN 15 AND 18 THEN \'15-18 años\'
            ELSE \'Mayor a 18 años\'
        END')
        ->pluck('count', 'age_group')
        ->toArray();

    return view('programas', compact(
        'totalParticipants',
        'participantsByProgram',
        'participantsByGrade',
        'participantsByGender',
        'averageAge',
        'participantsByAgeGroup',
        'programs',
        'selectedProgram'
    ));
})->middleware(['auth', 'verified'])->name('programas');

Route::middleware('auth')->group(function () {
    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::middleware('can:manage-roles')->group(function () {
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Rutas de participantes
    Route::get('/participante/create', [ParticipanteController::class, 'create'])->name('participante.create');
    Route::post('/participante/store', [ParticipanteController::class, 'store'])->name('participante.store');
    Route::get('/participante', [ParticipanteController::class, 'index'])->name('participante.index');
    Route::get('/participante/{participante}', [ParticipanteController::class, 'show'])->name('participante.show');
    Route::get('/participante/{participante}/edit', [ParticipanteController::class, 'edit'])->name('participante.edit')->middleware('can:manage-roles');
    Route::middleware('can:manage-roles')->group(function () {
        Route::put('/participante/{participante}', [ParticipanteController::class, 'update'])->name('participante.update');
        Route::delete('/participante/{participante}', [ParticipanteController::class, 'destroy'])->name('participante.destroy');
    });

    // Rutas de asistencia
    Route::get('/asistencia', [AsistenciaController::class, 'create'])->name('asistencia.create');
    Route::post('/asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store');

    // Rutas de roles (solo para admins)
    Route::middleware('can:manage-roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles/{user}', [RoleController::class, 'update'])->name('roles.update');
    });
});

require __DIR__.'/auth.php';