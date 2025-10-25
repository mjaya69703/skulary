<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blank', function () {
    $pages = 'Blank Page';
    $menus = 'Unknown Menu';

    return view('themes.blank-page', compact('pages', 'menus'));
});
