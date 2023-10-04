<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','employee_id','status','start','break','finish'];

    public function getStartAttribute($date){
        return date_format(date_create(date($date)),"H:i");
    }

    public function getBreakAttribute($date){
        return date_format(date_create(date($date)),"H:i");
    }

    public function getFinishAttribute($date){
        return date_format(date_create(date($date)),"H:i");
    }

    public function getDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d F, o');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id','id');
    }
}
