<?php

use Illuminate\Support\Facades\Route;

// ----- php artisan queue:work ------//

Route::get('/', [\App\Http\Controllers\FrontController::class, 'inizio'])->name('inizio');

//------------------- nav bar ----------------------------//
Route::get('/verifiche', [\App\Http\Controllers\FrontController::class, 'verifiche'])->name('verifiche');
Route::get('/richiamare', [\App\Http\Controllers\FrontController::class, 'richiamare'])->name('richiamare');
Route::get('/clienti', [\App\Http\Controllers\FrontController::class, 'clienti'])->name('clienti');
Route::get('/cellulari', [\App\Http\Controllers\FrontController::class, 'cellulari'])->name('cellulari');
Route::get('/capStrutture', [\App\Http\Controllers\FrontController::class, 'capStrutture'])->name('capStrutture');
Route::get('/upload', [\App\Http\Controllers\UploadController::class, 'upload'])->name('upload');

Route::post('/estrai', [\App\Http\Controllers\FrontController::class, 'estrai'])->name('estrai');
Route::post('/rispostagemini', [\App\Http\Controllers\FrontController::class, 'rispostagemini'])->name('rispostagemini');

//------------------- upload --------------------------------//
Route::post('/uploadAnagrafichePost', [\App\Http\Controllers\UploadController::class, 'uploadAnagrafichePost'])->name('uploadAnagrafichePost');
Route::post('/uploadAppuntamentiPost', [\App\Http\Controllers\UploadController::class, 'uploadAppuntamentiPost'])->name('uploadAppuntamentiPost');
Route::post('/uploadTelefonatePost', [\App\Http\Controllers\UploadController::class, 'uploadTelefonatePost'])->name('uploadTelefonatePost');
Route::post('/uploadStrutturePost', [\App\Http\Controllers\UploadController::class, 'uploadStrutturePost'])->name('uploadStrutturePost');

//------------------- verifiche ------------------------------//
Route::get('/doppioni', [\App\Http\Controllers\FrontController::class, 'doppioni'])->name('doppioni');
Route::get('/senzaNumero', [\App\Http\Controllers\FrontController::class, 'senzaNumero'])->name('senzaNumero');

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
