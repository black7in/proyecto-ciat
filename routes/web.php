<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Clientes\Index as ClientesIndex;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/clientes', ClientesIndex::class)->name('clientes.index');
});

use App\Livewire\Muestras\Index as MuestrasIndex;

Route::middleware(['auth'])->group(function () {
    Route::get('/muestras', MuestrasIndex::class)->name('muestras.index');
});