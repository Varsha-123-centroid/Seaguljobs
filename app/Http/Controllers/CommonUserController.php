<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;

class CommonUserController extends Controller
{
    public function home(){
        return view('common.common_home');
    }
 
}
