<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function translate($lang){
        session()->put('language',$lang);
        return back();
    }
}
