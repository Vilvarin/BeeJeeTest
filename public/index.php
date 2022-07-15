<?php

require_once '../vendor/autoload.php';

use App\App;
use App\Route;

// Register routes
Route::get('/', '\App\Controllers\TodoController::index');
Route::post('/', '\App\Controllers\TodoController::store');
Route::post('/update', '\App\Controllers\TodoController::update');
Route::get('/login', '\App\Controllers\AuthController::index');
Route::post('/login', '\App\Controllers\AuthController::login');
Route::get('/logout', '\App\Controllers\AuthController::logout');

App::run();
