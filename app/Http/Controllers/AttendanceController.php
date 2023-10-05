<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

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

    public function edit(Request $request)
    {
        $this->attendanceValidate($request);

        // ! Authorized User
        $authorizedUser = Attendance::select('user_id')->where('id',$request->id)->first()->user_id;
        if(auth()->id() != $authorizedUser){
            Toastr::error("403 Forbitten, You are not an authorized user.", "Error Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }

        $attendance = Attendance::find($request->id);
        $attendance->status = $request->status;
        IF($request->status == "Absent"){
            $attendance->start = "00:00";
            $attendance->break = "00:00";
            $attendance->finish = "00:00";
        }else{
            $attendance->start = $request->start;
            $attendance->break = $request->break?? "00:00";
            $attendance->finish = $request->finish;
        }
        $attendance->save();

        Toastr::success("You have edited {$request->name} data!", "Success Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
        return redirect()->route('attendance');
    }

    public function search()
    {
        $attendances = Attendance::where('attendances.user_id',auth()->id())
                ->select('employees.name','employees.department','employees.location','employees.position','attendances.*')
                ->join('employees','attendances.employee_id','employees.id')
                ->when(request('department'), function ($query){
                    $department = request('department');
                    $query->where('employees.department','like','%'.$department.'%');
                })
                ->when(request('position'), function ($query){
                    $position = request('position');
                    $query->where('employees.position','like','%'.$position.'%');
                })
                ->when(request('name'), function ($query){
                    $name = request('name');
                    $query->where('employees.name','like','%'.$name.'%');
                })->get();

        return response()->json([
            'attendances' => $attendances
        ]);
    }

    private function attendanceValidate($request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required',
            'status' => "required",
            'start' => "date_format:H:i|before:finish",
            'break' => "date_format:H:i|nullable",
            'finish' => "date_format:H:i|after:start",
        ]);
    }
}
