<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Brian2694\Toastr\Facades\Toastr;

class AttendanceController extends Controller
{
    public function attendance()
    {
        $attendances = Attendance::where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();
        $departments = Employee::select('department')->distinct()->get();
        $positions = Employee::select('position')->distinct()->get();
        return view('attendance',compact('attendances','departments','positions'));
    }

    public function createAll()
    {
        $employees = Employee::where('user_id',auth()->id())->get();
        $attendances = Attendance::where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();

        if(count($attendances) == 0){
            foreach($employees as $employee){
                $attendance = new Attendance();
                $attendance->user_id = $employee->user_id;
                $attendance->employee_id = $employee->id;
                $attendance->save();
            }
            Toastr::success("You have created all employees data for today!", "Success Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }else{
            Toastr::error("You have already created all employees data for today!", "Error Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }

    }

    public function deleteAll()
    {
        $attendances = Attendance::where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();

        if(count($attendances) == 0){
            Toastr::error("No employee data for today, Create first!", "Error Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }else{
            foreach($attendances as $attendance){
                $attendance->delete();
            }
            Toastr::success("You have deleted all employees data for today!", "Success Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }
    }
}
