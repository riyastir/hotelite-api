<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HotelProfileController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\HomeController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register']);
Route::get('hotels', [HotelProfileController::class, 'index']);
Route::get('packages/all', [PackageController::class, 'all']);
Route::get('recommended', [HotelProfileController::class, 'getRecommended']);
Route::get('summary', [HomeController::class, 'index']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', [UserController::class, 'details']);
    Route::post('profile', [HotelProfileController::class, 'store']);
    Route::patch('profile', [HotelProfileController::class, 'update']);
    Route::get('profile', [HotelProfileController::class, 'show']);
    Route::get('package/{id}', [PackageController::class, 'show']);
    Route::post('package', [PackageController::class, 'store']);
    Route::patch('package/{id}', [PackageController::class, 'update']);
    Route::get('package', [PackageController::class, 'index']);
    Route::delete('package/{id}', [PackageController::class, 'destroy']);
    Route::post('logout',[UserController::class, 'logout']);
    Route::post('auth-check',[UserController::class, 'authCheck']);
});
