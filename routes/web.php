<?php

use App\Livewire\Auth\{Login, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\{Auth, Route};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth/login', Login::class)->name('login');
Route::get('/auth/register', Register::class)->name('auth.register');
Route::get('/auth/logout', fn () => Auth::logout());

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('home');
});
