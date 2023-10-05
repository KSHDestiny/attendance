<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class OverViewController extends Controller
{
    public function index(){
        // $attendances = Attendance::groupBy('status')->select('status',DB::raw('count(*) as total'))->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();
        // $attendees = Attendance::select('employee_id','status')->where('user_id',auth()->id())->whereDate('date', Carbon::today())->get();

        // $dbStatus = ['On time','Absent','Late'];
        // $status = [];
        // $total = [];
        // foreach($attendances as $attendance){
        //     $status[] = $attendance->status;
        //     $total[] = $attendance->total;
        // }

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
}
