<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemController;

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
        Route::post('/delete', [AddressController::class, 'delete']);
        Route::post('/view/{id}', [AddressController::class, 'singleview']);
    });
    Route::post('/contactform', [AuthController::class, 'contactform']);
    Route::post('/rebootpassword', [AuthController::class, 'rebootpassword'])->name('rebootpassword');
    Route::post('/change', [AuthController::class, 'change'])->name('change');
    Route::post('/view', [AuthController::class, 'view'])->name('authview');
});

Route::group(['prefix' => 'favorite', 'middleware' => 'api_auth'], function () {
    Route::post('/add', [FavoriteController::class, 'add']);
    Route::post('/view', [FavoriteController::class, 'view']);
    Route::post('/delete', [FavoriteController::class, 'view']);
});

Route::group(['prefix' => 'item'], function () {
    Route::post('/', [ItemController::class, 'items']);
    Route::get('/guide', [ItemController::class, 'singleview']);
    Route::post('/{id}', [ItemController::class, 'singleview']);
});

Route::get('/cabinet', [IndexController::class, 'cabinet']);
Route::get('/view', [IndexController::class, 'view']);

Route::group(['prefix' => 'order', 'middleware' => 'api_auth'], function () {
    Route::post('/', [OrderController::class, 'view']);
    Route::post('/create', [OrderController::class, 'create']);
    Route::post('/{id}', [OrderController::class, 'singleview']);
});

