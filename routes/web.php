<?php

use App\Http\Controllers\MicrosoftAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddendumController;

use App\Http\Controllers\GoogleDriveController;

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

Route::get('/', function () {
    return view('sigin');
});

// Route::resource('/files',AddendumController::class);//upload addendum to drive
Route::resource('/files',GoogleDriveController::class);

// MICROSOFT LOGIN
// Route::get('/',[MicrosoftAuthController::class,'signInForm'])->name('sigin.in');
Route::get('microsoft-oAuth',[MicrosoftAuthController::class,'microsoftOAuth'])->name('microsoft.oAuth');
Route::get('callback',[MicrosoftAuthController::class,'microsoftOAuthCallback'])->name('microsoft.oAuth.callback');