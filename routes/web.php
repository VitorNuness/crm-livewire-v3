<?php

use App\Livewire\Auth\{Login, Password, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\{Auth, Route};

Route::get('/auth/login', Login::class)->name('login');
Route::get('/auth/register', Register::class)->name('auth.register');
Route::get('/auth/logout', fn () => Auth::logout());
Route::get('/auth/password/recovery', Password\Recovery::class)->name('password.recovery');
Route::get('/password/reset', Password\Reset::class)->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('home');
});
