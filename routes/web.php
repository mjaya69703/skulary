<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blank', function () {
    $pages = 'Blank Page';
    $menus = 'Unknown Menu';

    return view('themes.blank-page', compact('pages', 'menus'));
})->name('blank-index');

Route::get('/login', function () {
    $pages = 'Authentication';
    $menus = 'Auth Menu';

    return view('themes.auth.signin-index', compact('pages', 'menus'));
})->name('auth.login');
Route::get('/register', function () {
    $pages = 'Authentication';
    $menus = 'Auth Menu';

    return view('themes.auth.signup-index', compact('pages', 'menus'));
})->name('auth.register');

Route::post('/login', [App\Http\Controllers\System\AuthController::class, 'handleSignIn'])->name('auth.handle-signin');
Route::post('/register', [App\Http\Controllers\System\AuthController::class, 'handleSignUp'])->name('auth.handle-signup');
