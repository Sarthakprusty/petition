<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TemplateController;

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
/*
Route::get('applications',[App\Http\Controllers\ApplicationController::class,'index']);
Route::get('applications/{id}',[App\Http\Controllers\ApplicationController::class,'show']);
Route::post('applications',[App\Http\Controllers\ApplicationController::class,'store']);
*/
Route::group(['middleware' => ['web']], function () {
    Route::get('replies/pending', [ReplyController::class, 'getPending'])->name('reply.pending')->middleware('auth');
    Route::get('applications/{id}/reply', [ReplyController::class, 'create'])->name('reply.create')->middleware('auth');
    Route::post('applications/{id}/reply', [ReplyController::class, 'store'])->name('reply.store')->middleware('auth');


    Route::resource('applications', ApplicationController::class)->middleware('auth');
    Route::view('api/login', 'login')->name('login');


});
