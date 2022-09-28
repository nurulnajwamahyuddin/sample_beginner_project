<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',  [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register');
Route::post('/register/code', [App\Http\Controllers\Auth\RegisterController::class, 'registerCode'])->name('register.code');

Route::resource('/address',\App\Http\Controllers\UserAddressController::class);
Route::post('/city',[App\Http\Controllers\UserAddressController::class, 'getCity'])->name('city');
