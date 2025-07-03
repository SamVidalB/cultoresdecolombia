<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\LoginController;

use App\Http\Controllers\ComunaController;
use App\Http\Controllers\TallerController;
use App\Http\Controllers\CultorController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\RegistroTallerController;


Route::resource('comunas', ComunaController::class);
Route::get('/comunas/{comuna}/talleres', [ComunaController::class, 'talleres'])->name('comunas.talleres');
Route::post('/comunas/talleres', [ComunaController::class, 'storeTaller'])->name('comunas.storeTaller');


Route::resource('talleres', TallerController::class)->parameters([
    'talleres' => 'taller'
]);
Route::get('talleres/{taller}/participantes-inscritos', [TallerController::class, 'participantesInscritos'])->name('talleres.participantesInscritos');
Route::get('talleres/{taller}/participantes-export-excel', [TallerController::class, 'exportParticipantesExcel'])->name('talleres.participantes.export.excel');
Route::get('talleres/{taller}/participantes-export-pdf', [TallerController::class, 'exportParticipantesPdf'])->name('talleres.participantes.export.pdf');

Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');



Route::resource('cultores', CultorController::class);
Route::resource('participantes', ParticipanteController::class);

// Rutas para el registro público de participantes a talleres
Route::get('/registro-taller/{taller}', [RegistroTallerController::class, 'showRegistrationForm'])->name('registro.taller.form');
Route::post('/registro-taller/{taller}', [RegistroTallerController::class, 'registerParticipant'])->name('registro.taller.submit');






Route::get('/', function () {
    return view('layout');
});


// Route::get('/', function () {
//     if (Auth::check())
//         return redirect('/home'); 
        
//     return view('login');
// });

// Route::get('/login', function () {
//     if (Auth::check())
//         return redirect('/home'); 
        
//     return view('login');
// })->name('login');

// Route::post('/check', [LoginController::class, 'check']);
// Route::get('/logout', [LoginController::class, 'logout']);

// Route::get('/forgot-password', [LoginController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

// Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
// Route::post('/reset-password', [LoginController::class, 'reset'])->name('password.update');


Route::middleware(['auth'])->group(function () {
    
    // Route::get('/home', [LoginController::class, 'dashboard']);
    
    // Route::view('/profile', 'profile');
    // Route::post('/profile', [LoginController::class, 'profile']);
    // Route::post('/avatar', [LoginController::class, 'avatar']);

    
    // Route::middleware(['ceo'])->group(function () {

    // });


    // Route::middleware(['admin'])->group(function () {
        
    // });
    
      
    
});


use Illuminate\Support\Facades\Artisan;
Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    return "Caché cleared!";
});