<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/editor', function () {
    return view('editor');
});

Route::get('/test', function () {
    return view('test');
});
