<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');

// Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('fields', 'pages::fields.index')->name('fields.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
