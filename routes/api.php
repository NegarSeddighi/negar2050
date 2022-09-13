<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware(['auth:sanctum', 'activation'])->controller(UserController::class)->prefix("users")->name("users")->group(function () {
    Route::get('/', 'index')->name(".index")->middleware('role:manager|admin');
    Route::post('/', 'store')->name(".store")->middleware('role:manager|admin');
    Route::get('/{user}', 'show')->name(".show");
    Route::put('/{user}', 'update')->name(".update");
    Route::delete('/{user}', 'destroy')->name(".destroy")->middleware('role:manager|admin');
});

Route::controller(AuthController::class)->name("auth")->group(function () {
    Route::post('/login', 'login')->name(".login");
    Route::post('/register', 'register')->name(".register");
});


