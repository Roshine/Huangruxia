<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WeekScoreController extends Controller
{
    //返回每周成绩列表--学生
    public function getWeekScore(){
        $stuId = Auth::user()->stuId;
        $weeks = DB::table('weekscore')
            ->where('stuId',$stuId)
            ->select('week','preScore','homeworkScore','sumScore','weekScore')
            ->orderBy('week','asc')
            ->get();
        return $weeks;
    }
}
