<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

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
});
