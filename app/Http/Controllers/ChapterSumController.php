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
            'chapterId' => 'required',
            'class' => 'required'
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $chapterId = $request->chapterId;

        $lists = DB::table('students')
            ->where('students.class',$request->class)
            ->leftJoin('chaptersum',function ($join) use($chapterId){
                $join->on('chaptersum.stuId','=','students.stuId')
                    ->where('chaptersum.chapter','=',$chapterId);
            })
            ->select('students.stuId','students.name','chapterSumScore')
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
                'filled' => $filled,
                'chapterSumScore' => $list->chapterSumScore
            ];
        }

        return $data;
    }

    //添加学生的某章总结分数,并且算出该章综合成绩
    public function fillChapterSumScore(Request $request){

        if (!count(json_decode($request))){
            return[
                'error' => -1,
                'des' => '提交的数据为空'
            ];
        }

        foreach (json_decode($request) as $item) {
            $validator = Validator::make($item->all(), [
                'stuId' => 'required',
                'chapterId' => 'required',
                'chapterSumScore' => 'required'
            ]);
            if ($validator->fails()) {
                return [
                    'error' => -1,
                    'des' => $validator->errors()
                ];
            }
        }
        /**
         * 算出该章总成绩
         * */
        //查出该章的上课周
        switch ($request[0]->chapterId){
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

        //算出本章综合成绩
        foreach (json_decode($request) as $item) {
            $weekScores = DB::table('weekscore')
                ->where('stuId', $item->stuId)
                ->whereIn('week', $weeks)
                ->lists('weekScore');
            $weekScore = array_sum($weekScores) / count($weeks);  //周综合成绩

            $chapterSumProportion = 0.4;        //每章总结在章成绩里占得比例
            $chapterScore = $weekScore + $item->chapterSumScore * $chapterSumProportion;     //章综合成绩

            $res = ChapterSum::create([
                'stuId' => $item->stuId,
                'chapter' => $item->chapterId,
                'chapterSumScore' => $item->chapterSumScore,
                'chapterScore' => $chapterScore
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

    //统计某章的所有学生的综合成绩
    public function  fillChapterScore(Request $request){
        $validator = Validator::make($request->all(),[
            'chapterId' => 'required|min:1|max:7'
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $stuIds = DB::table('students')
            ->where('privilege',0)
            ->lists('stuId');

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

        foreach ($stuIds as $stuId){
            $weekScores = DB::table('weekscore')
                ->where('stuId', $stuId)
                ->whereIn('week', $weeks)
                ->lists('weekScore');

            $chapterSumScore = DB::table('chaptersum')
                ->where('stuId',$stuId)
                ->where('chapter',$request->chapterId)
                ->select('chapterSumScore')
                ->first();

            if (is_object($chapterSumScore)){
                $chapterSumScore = $chapterSumScore->chapterSumScore;
            }

            $weekScore = array_sum($weekScores) / count($weeks);  //周综合成绩

            $chapterSumProportion = 0.4;        //每章总结在章成绩里占得比例
            $chapterScore = $weekScore + $chapterSumScore * $chapterSumProportion;     //章综合成绩

            $result = DB::table('chaptersum')
                ->where('stuId',$stuId)
                ->where('chapter',$request->chapterId)
                ->select('chapterScore')
                ->first();
            if(count($result)){         //记录存在，更新之

                $res = ChapterSum::where('stuId',$stuId)
                    ->where('chapter',$request->chapterId)
                    ->update([
                        'chapterScore' => $chapterScore
                    ]);

            }else{                  //记录不存在，创建新纪录
                $res = ChapterSum::create([
                    'stuId' => $stuId,
                    'chapter' => $request->chapterId,
                    'chapterScore' => $chapterScore
                ]);
            }

            if (!$res){
                return[
                    'error' => -2,
                    'des' => '统计出错'
                ];
            }
        }

        return[
            'error' => 0
        ];
    }
}













