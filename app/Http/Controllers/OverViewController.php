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
        $onTime = Attendance::select('employee_id','status')->where('status','On time')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();
        $absent = Attendance::select('employee_id','status')->where('status','Absent')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();
        $late = Attendance::select('employee_id','status')->where('status','Late')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();

        $piejs = app()->chartjs
                ->name('pieChartTest')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['On Time', 'Late', 'Absent'])
                ->datasets([
                    [
                        'backgroundColor' => ['green', 'orange', 'red'],
                        'hoverBackgroundColor' => ['darkgreen', 'darkorange', 'darkred'],
                        'data' => [$onTime->count(), $late->count(), $absent->count()]
                    ]
                ])->options([]);

        $barjs = app()->chartjs
                ->name('barChartTest')
                ->type('bar')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['On Time', 'Late', 'Absent'])
                ->datasets([
                    [
                        "label" => "Today Attendance",
                        'backgroundColor' => ['green', 'orange', 'red'],
                        'hoverBackgroundColor' => ['darkgreen', 'darkorange', 'darkred'],
                        'data' => [$onTime->count(), $late->count(), $absent->count()]
                    ]
                ])->options([]);

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

        $employeeData = Employee::where('name', $request->name)->get()->count();
        if($employeeData == 0){
            Toastr::error("There is no employee named $request->name!", "Error Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
            return back();
        }

        $onTime = $this->getStatus($request->name, "On time");
        $late = $this->getStatus($request->name, "Late");
        $absent = $this->getStatus($request->name, "Absent");
        $totalDay = $onTime + $late + $absent;

        $onTimePer = ($onTime / $totalDay) * 100;
        $latePer = ($late / $totalDay) * 100;
        $absentPer = ($absent / $totalDay) * 100;

        $linejs = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['On Time', 'Late', "Absent"])
        ->datasets([
            [
                "label" => $request->name."'s Attendance",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [$onTimePer, $latePer, $absentPer],
            ],
        ])
        ->options([]);

        $barjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['On Time', 'Late', "Absent"])
        ->datasets([
            [
                "label" => $request->name."'s Attendance",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [$onTime, $late, $absent],
            ],
        ])
        ->options([]);

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
}
