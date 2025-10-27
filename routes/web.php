<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/login', [App\Http\Controllers\System\AuthController::class, 'login'])->name('auth.signin-index');
Route::get('/register', [App\Http\Controllers\System\AuthController::class, 'register'])->name('auth.signup-index');
Route::post('/login', [App\Http\Controllers\System\AuthController::class, 'handleSignIn'])->name('auth.signin-handle');
Route::post('/register', [App\Http\Controllers\System\AuthController::class, 'handleSignUp'])->name('auth.signup-handle');
Route::get('/forgot-password', [App\Http\Controllers\System\AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
Route::post('/forgot-password', [App\Http\Controllers\System\AuthController::class, 'sendResetLink'])->name('auth.send-reset-link');
Route::get('/reset-password/{token}', [App\Http\Controllers\System\AuthController::class, 'resetPassword'])->name('auth.reset-password');
Route::post('/reset-password', [App\Http\Controllers\System\AuthController::class, 'handleResetPassword'])->name('auth.handle-reset-password');
Route::get('/gateway/choose-role', [App\Http\Controllers\System\AuthController::class, 'chooseRole'])->name('auth.gateway-choose');
Route::post('/gateway/set-role', [App\Http\Controllers\System\AuthController::class, 'setRole'])->name('auth.gateway-set');
Route::get('/logout', [App\Http\Controllers\System\AuthController::class, 'logout'])->name('auth.logout');

Route::middleware(['auth', 'active_role:admin'])->prefix('admin')->as('admin.')->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard-index');
    Route::get('/blank', function () {
        $pages = 'Blank Page';
        $menus = 'Unknown Menu';

        return view('themes.blank-page', compact('pages', 'menus'));
    })->name('blank-index');

});

Route::middleware(['auth', 'active_role:guru'])->prefix('guru')->as('guru.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard-index');
    Route::get('/blank', function () {
        $pages = 'Blank Page';
        $menus = 'Unknown Menu';

        return view('themes.blank-page', compact('pages', 'menus'));
    })->name('blank-index');

});

Route::middleware(['auth', 'active_role:parents'])->prefix('parents')->as('parents.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Parents\DashboardController::class, 'index'])->name('dashboard-index');
    Route::get('/blank', function () {
        $pages = 'Blank Page';
        $menus = 'Unknown Menu';

        return view('themes.blank-page', compact('pages', 'menus'));
    })->name('blank-index');

});

Route::middleware(['auth', 'active_role:siswa'])->prefix('siswa')->as('siswa.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard-index');
    Route::get('/blank', function () {
        $pages = 'Blank Page';
        $menus = 'Unknown Menu';

        return view('themes.blank-page', compact('pages', 'menus'));
    })->name('blank-index');

});

Route::middleware(['auth', 'active_role:peserta-ppdb'])->prefix('peserta-ppdb')->as('peserta-ppdb.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard-index');
    Route::get('/blank', function () {
        $pages = 'Blank Page';
        $menus = 'Unknown Menu';

        return view('themes.blank-page', compact('pages', 'menus'));
    })->name('blank-index');

});