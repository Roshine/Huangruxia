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
            'testId' => 'required',
            'class' => 'required'
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
            ->where('class',$request->class)
            ->leftJoin('testscore',function ($join) use($testId){
                $join->on('testscore.stuId','=','students.stuId')
                    ->where('testscore.testId','=',$testId);
            })
            ->select('students.stuId','students.name','testScore')
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
                'filled' => $filled,
                'testScore' => $list->testScore
            ];
        }
        return $data;
    }

    //添加学生的随堂测试成绩
    public function fillTestScore(Request $request){

        if (!count(json_decode($request))){
            return[
                'error' => -1,
                'des' => '提交的数据为空'
            ];
        }

        foreach (json_decode($request) as $item) {
            $validator = Validator::make($item->all(), [
                'stuId' => 'required',
                'testId' => 'required',
                'testScore' => 'required'
            ]);
            if ($validator->fails()) {
                return [
                    'error' => -1,
                    'des' => $validator->errors()
                ];
            }
        }

        foreach (json_decode($request) as $item) {
            $res = testScore::create([
                'stuId' => $item->stuId,
                'testId' => $item->testId,
                'testScore' => $item->testScore
            ]);

            if (!$res){
                return[
                    'error' => -2
                ];
            }
        }

        return[
            'error' => 0
        ];
    }
}









