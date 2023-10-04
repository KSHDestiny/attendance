<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name','user_id','email','age','phone','address','department','location','position'];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function employees(){
        return $this->hasMany(Attendance::class);
    }

    public function getLocationAttribute($value){
        return ucwords(str_replace("_"," ",$value));
    }
}
