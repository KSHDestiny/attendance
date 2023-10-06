<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OverViewController;

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
    if(auth()->user()){
        return back();
    }
    return view('auth.login');
});

Auth::routes();

Route::resource('/employee',EmployeeController::class)->middleware('auth');

Route::middleware('auth')->group(function(){
    Route::get('/attendance',[AttendanceController::class,"attendance"])->name('attendance');
    Route::post('/attendance/create',[AttendanceController::class,"createAll"])->name('attendance.create');
    Route::post('/attendance/delete',[AttendanceController::class,"deleteAll"])->name('attendance.delete');
    Route::post('/attendance/edit',[AttendanceController::class,"edit"])->name('attendance.edit');
    Route::post('/attendance/search',[AttendanceController::class,"search"])->name('attendance.search');

    Route::get('/overview',[OverViewController::class,'index'])->name('overview');
    Route::get('/overall',[OverViewController::class,'overall'])->name('overall');
    Route::post('/overall',[OverViewController::class,'overallData'])->name('overall.data');

    Route::get('/lang/{lang}',[LanguageController::class,'translate']);
});
