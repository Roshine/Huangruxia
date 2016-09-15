<?php

namespace App\Http\Controllers;

use App\SumTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SumTempController extends Controller
{
    //显示每周小结模板列表--学生
    public function sumTempList(){
        $groupId = Auth::user()->groupId;
        $sumTemps = DB::table('sumtemplates')->where('startTime','<=',Carbon::now())
            ->leftJoin('sumcollections',function ($join){
                $join->on('sumcollections.weekId','=','sumtemplates.weekId')
                    ->where('sumcollections.stuId','=',Auth::user()->stuId);
            })
            ->leftJoin('assessments',function ($join){
                $join->on('assessments.weekId','=','sumtemplates.weekId')
                    ->where('assessments.stuId','=',Auth::user()->stuId)
                    ->where('assessments.peerId','=',Auth::user()->stuId);
            })
            ->select('sumtemplates.weekId','startTime','deadLine','marked','assessments.id')
            ->orderBy('sumtemplates.weekId','asc')
            ->get();
        $data = [];
        foreach ($sumTemps as $sumTemp){
            //判断是否在答题时间段内
            if (strtotime($sumTemp->startTime)<=time()&&time()<=strtotime($sumTemp->deadLine)+86400){
                $duringtime = true;
            }else{
                $duringtime = false;
            }
            //判断学生是否提交过
            if (count($sumTemp->id)){
                $submitted = true;
                $marked = $sumTemp->marked;
            }else{
                $submitted = false;
                $marked = false;
            }

            $data[] = [
                'weekId' => $sumTemp->weekId,
                'startTime' => $sumTemp->startTime,
                'deadLine' => $sumTemp->deadLine,
                'duringTime' => $duringtime,
                'submitted' => $submitted,
                'marked' => $marked
            ];
        }

        return [
            'groupId' => $groupId,
            'data' => $data
        ];

    }

    //显示每周小结模板列表--老师
    public function sumTempListTeacher(){
        $sumTemps = SumTemplate::where('startTime','<=',Carbon::now())
            ->select('weekId','startTime','deadLine')
            ->orderBy('weekId')
            ->get();

        if (!count($sumTemps)){
            return[
                'error' => -2
            ];
        }

        $data = [];

        foreach ($sumTemps as $sumTemp){
            //判断是否在答题时间段内
            if (strtotime($sumTemp->startTime)<=time()&&time()<=strtotime($sumTemp->deadLine)+86400){
                $duringtime = 'true';
            }else{
                $duringtime = 'false';
            }
            $data[] = [
                'weekId' => $sumTemp->weekId,
                'startTime' => $sumTemp->startTime,
                'deadLine' => $sumTemp->deadLine,
                'duringTime' => $duringtime
            ];
        }

        return[
            'data' => $data
        ];
    }
}


