<?php

namespace App\Http\Controllers;

use App\testScore;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestScoreController extends Controller
{
    //填写随堂测试时获取学生列表
    public function getStuListForTestscore(Request $request){
        $validator = Validator::make($request->all(),[
            'testId' => 'required'
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $testId = $request->testId;

        $lists = DB::table('students')
            ->where('privilege',0)
            ->leftJoin('testscore',function ($join) use($testId){
                $join->on('testscore.stuId','=','students.stuId')
                    ->where('testscore.testId','=',$testId);
            })
            ->select('students.stuId','students.name','students.class','testScore')
            ->get();

        $data = [];
        foreach ($lists as $list){
            if ($list->testScore == null){
                $filled = false;
            }else{
                $filled = true;
            }

            $data[] = [
                'stuId' => $list->stuId,
                'name' => $list->name,
                'class' => $list->class,
                'filled' => $filled,
                'testScore' => $list->testScore
            ];
        }
        return $data;
    }

    //添加某一位学生的随堂测试成绩
    public function fillTestScore(Request $request){
        $validator = Validator::make($request->all(),[
            'stuId' => 'required',
            'testId' => 'required',
            'testScore' => 'required'
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $res = testScore::create([
            'stuId' => $request->stuId,
            'testId' => $request->testId,
            'testScore' => $request->testScore
        ]);

        return[
            'error' => (!$res? -2 : 0)
        ];
    }
}









