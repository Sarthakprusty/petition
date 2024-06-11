<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SignAuthorityController;
use App\Http\Controllers\AuthController;

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
    return redirect(\route('applications.index'));
});
/*
Route::get('applications',[App\Http\Controllers\ApplicationController::class,'index']);
Route::get('applications/{id}',[App\Http\Controllers\ApplicationController::class,'show']);
Route::post('applications',[App\Http\Controllers\ApplicationController::class,'store']);
*/
Route::group(['middleware' => ['web']], function () {
    Route::get('applications/search', [ApplicationController::class, 'search'])->name('application.search')->middleware('auth');
    Route::get('applications/indDetails', [ApplicationController::class, 'indDetails'])->name('application.indDetails')->middleware('auth');
    Route::get('applications/reportprint', [ApplicationController::class, 'reportprint'])->name('application.reportprint')->middleware('auth');
    Route::get('applications/dashboard', [ApplicationController::class, 'dashboard'])->name('applications.dashboard')->middleware('auth');

    Route::get('applications/{id}/acknowledgement', [ApplicationController::class, 'generateAcknowledgementLetter'])->name('application.acknowledgement')->middleware('auth');
    Route::get('applications/{id}/forwarded', [ApplicationController::class, 'generateForwardLetter'])->name('application.forward')->middleware('auth');
    Route::post('applications/{application_id}/update-status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus')->middleware('auth');
    Route::post('applications/update-print', [ApplicationController::class, 'updatePrint'])->name('applications.updatePrint')->middleware('auth');
    Route::get('applications/pullback', [ApplicationController::class, 'pullback'])->name('applications.pullback')->middleware('auth');


    Route::get('employees', [AuthController::class, 'employees'])->name('users.employees')->middleware('auth');
    Route::post('employees_data', [AuthController::class, 'save_employee'])->name('users.employees_save')->middleware('auth');


    // Route::get('/application/search', 'ApplicationController@search')->name('application.search');


    Route::resource('applications', ApplicationController::class)->middleware('auth');
    Route::view('api/login', 'login')->name('login');

    Route::resource('authority', SignAuthorityController::class)->middleware('auth');
    Route::post('authority/remove', [SignAuthorityController::class, 'removeOnly'])->name('authority.remove')->middleware('auth');

    Route::get('/error', function () {return view('error');});

    Route::resource('organizations', OrganizationController::class)->middleware('auth');
    Route::post('org/change', [OrganizationController::class, 'changeorganization'])->name('organizations.change')->middleware('auth');
    Route::get('organizations', [OrganizationController::class, 'ministries'])->name('organizations.ministries')->middleware('auth');
   
});
