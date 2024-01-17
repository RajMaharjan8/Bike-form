<?php

use App\Http\Controllers\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('create', [ApiUserController::class, 'create']);
// Route::get('fetch/{id}', [ApiUserController::class, 'fetch']);

Route::middleware(['api.auth'])->group(function () {
    Route::post('fetch', [ApiUserController::class, 'fetch']);
    Route::get('getRegistered', [ApiUserController::class, 'getAllRegistered']);
});
// Route::post('fetch', [ApiUserController::class, 'fetch'])->middleware('api.auth');
// Route::get('getRegistered', [ApiUserController::class, 'getAllRegistered']);
// ->middleware('api.auth');
Route::post('apilogin', [ApiUserController::class, 'apiLogin']);
Route::post('forgetPasswordEmail', [ApiUserController::class, 'forgetPasswordEmail']);
Route::post('verifyOptForgetPassword', [ApiUserController::class, 'verifyOptForgetPassword']);
Route::post('changePassword', [ApiUserController::class, 'changePassword']);
Route::post('verifyUser', [ApiUserController::class, 'verifyOtpApi']);
Route::post('apilogout', [ApiUserController::class, 'apiLogout']);
