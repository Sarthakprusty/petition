<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
// routes/api.php
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('is_logged_in', [AuthController::class, 'isLoggedIn']);
});

Route::get('forbidden',function (Request $request){
    return response($request->fullUrl(), 403)
        ->header('Content-Type', 'Application/json');
})->name('forbidden');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('applications', App\Http\Controllers\ApplicationController::class);

Route::resource('authority', App\Http\Controllers\SignAuthorityController::class);

Route::get('getFile/{filepath}',[App\Http\Controllers\ApplicationController::class,'getFile']);

Route::resource('organizations', App\Http\Controllers\OrganizationController::class);
Route::resource('grievances', App\Http\Controllers\GrievanceController::class);

Route::resource('states', App\Http\Controllers\StateController::class);
Route::get('org/{char}/IntOrOut', [App\Http\Controllers\OrganizationController::class, 'getOrgByIntOrExt']);

Route::post('reply/{id}', [App\Http\Controllers\ApplicationController::class, 'reply'])->name('reply');
Route::resource('statuses', App\Http\Controllers\StatusController::class);

