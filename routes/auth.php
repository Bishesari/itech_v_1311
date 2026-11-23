<?php

Route::livewire('/register', 'pages::auth.register')->name('register');
Route::livewire('/reset-pass', 'pages::auth.reset-pass')->name('reset-password');

Route::middleware(['auth'])->group(function () {
    Route::livewire('roles/select', 'pages::auth.select-role')->name('roles.select');
});
