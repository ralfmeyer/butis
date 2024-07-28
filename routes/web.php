<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

Route::view('stelle', 'stelle')
    ->middleware(['auth', 'verified'])
    ->name('stelle');    

    Route::view('mitarbeiter', 'mitarbeiter')
    ->middleware(['auth', 'verified'])
    ->name('mitarbeiter');    

    Route::view('kriterien', 'kriterien')
    ->middleware(['auth', 'verified'])
    ->name('kriterien');       
    
    Route::view('beurteilung', 'beurteilung')
    ->middleware(['auth', 'verified'])
    ->name('beurteilung');       

    
require __DIR__.'/auth.php';
