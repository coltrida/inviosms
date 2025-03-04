<?php

use Illuminate\Support\Facades\Route;

// ----- php artisan queue:work               ------//
// ----- php artisan queue:work --timeout=0   --------------- //

Route::get('/', [\App\Http\Controllers\FrontController::class, 'inizio'])->name('inizio');

//------------------- nav bar ----------------------------//
Route::get('/verifiche', [\App\Http\Controllers\FrontController::class, 'verifiche'])->name('verifiche');
Route::get('/richiamare', [\App\Http\Controllers\FrontController::class, 'richiamare'])->name('richiamare');
Route::get('/clienti', [\App\Http\Controllers\FrontController::class, 'clienti'])->name('clienti');
Route::get('/cellulari', [\App\Http\Controllers\FrontController::class, 'cellulari'])->name('cellulari');
Route::get('/capStrutture', [\App\Http\Controllers\FrontController::class, 'capStrutture'])->name('capStrutture');
Route::get('/upload', [\App\Http\Controllers\UploadController::class, 'upload'])->name('upload');

Route::post('/estraiuno', [\App\Http\Controllers\FrontController::class, 'estraiuno'])->name('estraiuno');
Route::post('/estraidue', [\App\Http\Controllers\FrontController::class, 'estraidue'])->name('estraidue');
Route::post('/estraitre', [\App\Http\Controllers\FrontController::class, 'estraitre'])->name('estraitre');
Route::post('/estraiquattro', [\App\Http\Controllers\FrontController::class, 'estraiquattro'])->name('estraiquattro');

//------------------- upload --------------------------------//
Route::post('/uploadAnagrafichePost', [\App\Http\Controllers\UploadController::class, 'uploadAnagrafichePost'])->name('uploadAnagrafichePost');
Route::post('/uploadAppuntamentiPost', [\App\Http\Controllers\UploadController::class, 'uploadAppuntamentiPost'])->name('uploadAppuntamentiPost');
Route::post('/uploadTelefonatePost', [\App\Http\Controllers\UploadController::class, 'uploadTelefonatePost'])->name('uploadTelefonatePost');
Route::post('/uploadStrutturePost', [\App\Http\Controllers\UploadController::class, 'uploadStrutturePost'])->name('uploadStrutturePost');

//------------------- caricaFile --------------------------------//
Route::get('/caricaStrutture', [\App\Http\Controllers\UploadController::class, 'caricaStrutture'])->name('caricaStrutture');

//------------------- clienti ------------------------------//
Route::get('/clientiAppuntamenti/{idClient}', [\App\Http\Controllers\FrontController::class, 'clientiAppuntamenti'])->name('clienti.appuntamenti');
Route::get('/clientiTelefonate/{idClient}', [\App\Http\Controllers\FrontController::class, 'clientiTelefonate'])->name('clienti.telefonate');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
