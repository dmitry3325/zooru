<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', '\App\Http\Controllers\Auth\LoginController@login');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/login/validate', '\App\Http\Controllers\Auth\LoginController@validateLogin');
Route::post('/registration', '\App\Http\Controllers\Auth\RegisterController@create');