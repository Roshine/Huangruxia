<?php

namespace App\Http\Controllers;

use App\ChapterSum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChapterSumController extends Controller
{
    //添加每章总结分数时获取学生列表
    public function getStuListForChapterSum(Request $request){
        $validator = Validator::make($request->all(),[
            'chapterId' => 'required'
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $chapterId = $request->chapterId;

        $lists = DB::table('students')
            ->leftJoin('chaptersum',function ($join) use($chapterId){
                $join->on('chaptersum.stuId','=','students.stuId')
                    ->where('chaptersum.chapter','=',$chapterId);
            })
            ->select('students.stuId','students.name','class','chapterSumScore')
            ->get();

        $data = [];
        foreach ($lists as $list){
            if ($list->chapterSumScore == null){
                $filled = false;
            }else{
                $filled = true;
            }

            $data[] = [
               'stuId' => $list->stuId,
                'name' => $list->name,
                'class' => $list->class,
                'filled' => $filled,
                'chapterSumScore' => $list->chapterSumScore
            ];
        }

        return $data;
    }

    //添加某个学生的某章总结分数,并且算出该章综合成绩
    public function fillChapterSumScore(Request $request){
        $validator = Validator::make($request->all(),[
            'stuId' => 'required',
            'chapterId' => 'required',
            'chapterSumScore' => 'required'
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        /**
         * 算出该章总成绩
         * */
        //查出该章的上课周
        switch ($request->chapterId){
            case 1:
                $weeks = [5,6,7];
                break;
            case 2:
                $weeks = [8,9,10,11,12];
                break;
            case 3:
                $weeks = [13,14];
                break;
            case 4:
                $weeks = [15];
                break;
            case 5:
                $weeks = [16,17];
                break;
            case 6:
                $weeks = [18];
                break;
            case 7:
                $weeks = [19];
                break;
            default:
                return[
                    'error' => -1,
                    'des' => 'chapterId is wrong'
                ];
        }

        //算出这几周的综合成绩（在本章中）
        $weekScores = DB::table('weekscore')
            ->where('stuId',$request->stuId)
            ->whereIn('week',$weeks)
            ->lists('weekScore');
        $weekScore = array_sum($weekScores)/count($weeks);  //周综合成绩

        $chapterSumProportion = 0.4;        //每章总结在章成绩里占得比例
        $chapterScore = $weekScore + $request->chapterSumScore * $chapterSumProportion;     //章综合成绩

        $res = ChapterSum::create([
            'stuId' => $request->stuId,
            'chapter' => $request->chapterId,
            'chapterSumScore' => $request->chapterSumScore,
            'chapterScore' => $chapterScore
        ]);

        return[
            'error' => (!$res? -2 : 0)
        ];
    }
}













