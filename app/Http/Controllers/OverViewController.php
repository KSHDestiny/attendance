<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class OverViewController extends Controller
{
    public function index()
    {
        $onTime = Attendance::with('employee')->select('employee_id','status')->where('status','On time')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();
        $absent = Attendance::with('employee')->select('employee_id','status')->where('status','Absent')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();
        $late = Attendance::with('employee')->select('employee_id','status')->where('status','Late')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();

        // * ChartJs
        $piejs = $this->chartjs("pie", ['On Time', 'Late', "Absent"], null, ['green', 'orange', 'red'], ['darkgreen', 'darkorange', 'darkred'], null,  null, null, [$onTime->count(), $late->count(), $absent->count()]);
        $barjs = $this->chartjs("bar", ['On Time', 'Late', "Absent"], null, ['green', 'orange', 'red'], ['darkgreen', 'darkorange', 'darkred'], null,  null, null, [$onTime->count(), $late->count(), $absent->count()]);

        return view('overview',compact('onTime','absent','late','piejs','barjs'));
    }

    public function overall()
    {
        $employees = Employee::select('name')->where('user_id',auth()->id())->get();
        return view('overall',compact('employees'));
    }

    public function overallData(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        // * Wrong Name
        $employeeData = Employee::where('name', $request->name)->where('user_id',auth()->id())->get()->count();
        if($employeeData == 0){
            $name = htmlentities($request->name);
            Toastr::error("There is no employee named $name!", "Error Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }

        // * ChartJs Data
        $onTime = $this->getStatus($request->name, "On time");
        $late = $this->getStatus($request->name, "Late");
        $absent = $this->getStatus($request->name, "Absent");
        $totalDay = $onTime + $late + $absent;

        $onTimePer = ($onTime / $totalDay) * 100;
        $latePer = ($late / $totalDay) * 100;
        $absentPer = ($absent / $totalDay) * 100;

        // * ChartJs
        $linejs = $this->chartjs("line", ['On Time', 'Late', "Absent"], $request->name, "rgba(38, 185, 154, 0.31)", null, "rgba(38, 185, 154, 0.7)",  "#fff", "rgba(220,220,220,1)", [$onTimePer, $latePer, $absentPer] );
        $barjs = $this->chartjs("bar", ['On Time', 'Late', "Absent"], $request->name, "rgba(38, 185, 154, 0.31)", null, "rgba(38, 185, 154, 0.7)",  "#fff", "rgba(220,220,220,1)", [$onTime, $late, $absent] );

        $employees = Employee::select('name')->where('user_id',auth()->id())->get();
        $name = $request->name;

        return view('overall', compact('linejs','barjs','employees','name'));
    }

    private function getStatus($name, $status){
        return Attendance::join('employees','attendances.employee_id','employees.id')
        ->select('employees.*','attendances.status')
        ->where('employees.name',$name)
        ->where('attendances.user_id',auth()->id())
        ->where('status',$status)
        ->get()
        ->count();
    }

    private function chartjs($type, $labels, $name = "Today", $bgColor, $hoverBgColor, $pointColor, $pointBgColor, $pointBorderColor, $data){
        return app()->chartjs
        ->name($type.'ChartTest')
        ->type($type)
        ->size(['width' => 400, 'height' => 200])
        ->labels($labels)
        ->datasets([
            [
                "label" => $name."'s Attendance",
                'backgroundColor' => $bgColor,
                'hoverBackgroundColor' => $hoverBgColor,
                'borderColor' => $pointColor,
                "pointBorderColor" => $pointColor,
                "pointBackgroundColor" => $pointColor,
                "pointHoverBackgroundColor" => $pointBgColor,
                "pointHoverBorderColor" =>  $pointBorderColor,
                'data' => $data,
            ],
        ])
        ->options([]);
    }
}
