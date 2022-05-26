<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forgot', [AuthController::class, 'forgot'])->name('forgot');
    Route::post('/code', [AuthController::class, 'code'])->name('code');
    Route::post('/changePassword', [AuthController::class, 'changePassword'])->name('changePassword');
});

Route::group(['prefix' => 'auth', 'middleware' => "api_auth"], function () {
    Route::group(['prefix' => 'address'], function () {
        Route::post('/view', [AddressController::class, 'view']);
        Route::post('/add', [AddressController::class, 'add']);
        Route::post('/change', [AddressController::class, 'change']);
        Route::post('/view/{id}', [AddressController::class, 'singleview']);
    });
    Route::post('/rebootpassword', [AuthController::class, 'rebootpassword'])->name('rebootpassword');
    Route::post('/change', [AuthController::class, 'change'])->name('change');
    Route::post('/view', [AuthController::class, 'view'])->name('authview');
});


