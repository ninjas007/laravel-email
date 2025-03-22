<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

// group middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/home',  [HomeController::class, 'index'])->name('home');

    // lists
    Route::resource('lists', ContactListController::class);

    // contacts
    Route::resource('contacts', ContactController::class);
});

Route::resource('user', UserController::class);
Route::get('/emails', [App\Http\Controllers\EmailController::class, 'index']);
