<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentsController extends Controller
{
    //获取个人信息
    public function getStudentInfo(){
        $stuId = Auth::user()->stuId;

        $info = DB::table('students')
            ->where('stuId',$stuId)
            ->select('stuId','name','email','gender','class','groupId')
            ->first();

        return[
            'error' => (!$info? -2 : 0),
            'info' => $info
        ];
    }

    //修改个人信息
    public function Modifyinformation(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
        ]);
        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $res = DB::table('students')
            ->where('stuId',Auth::user()->stuId)
            ->update([
                'email' => $request->email
            ]);

        return[
            'error' => (!$res? -2 : 0)
        ];
    }

    //统计所有学生期末成绩
    public function countFinalScore(){
        $stuIds = DB::table('students')
            ->where('privilege',0)
            ->lists('stuId');

        foreach ($stuIds as $stuId){

            $testProportion = 0.1;      //每次随堂测试在期末成绩中占得比例
            $everyExpSelectScore = 19;

            //随堂测试成绩
            $testScores = DB::table('testscore')
                ->where('stuId',$stuId)
                ->lists('testScore');
            $testScore = array_sum($testScores) * $testProportion;  //期末成绩中随堂测试的成绩


            //实验成绩
            $expScores = DB::table('expcollections')
                ->where('stuId',$stuId)
                ->select('resScore','expScore','expReportScore')
                ->get();

            $expPreScore = 0;
            $expReportScore = 0;
            foreach ($expScores as $item){
                $expPreScore += ($item->resScore * $everyExpSelectScore + $item->expScore);
                $expReportScore += $item->expReportScore;
            }

            $expScoreDb = DB::table('students')
                ->where('stuId',$stuId)
                ->select('expExam')
                ->first();


            $finalExpScore = $expPreScore * 0.01 + $expReportScore * 0.02 +$expScoreDb->expExam * 0.05;    //期末成绩中实验的成绩

            //章节分数
            $chapterScores = DB::table('chaptersum')
                ->where('stuId',$stuId)
                ->select('chapter','chapterScore')
                ->get();

            $chapterScore = 0;
            foreach ($chapterScores as $item){
                switch ($item->chapter) {
                    case 1 :
                    case 2 :
                    case 5 :
                        $chapterScore += $item->chapterScore*0.1;
                        break;
                    case 3:
                    case 4:
                    case 6:
                    case 7:
                        $chapterScore += $item->chapterScore*0.05;
                        break;
                    default:
                        return[
                            'error' => 'chapterId is wrong'
                        ];
                }
            }

            $finalScore = $chapterScore + $finalExpScore + $testScore;

            $res = User::where('stuId',$stuId)
                ->update([
                    'finalScore' => $finalScore
                ]);

//            dd(round($finalScore,2));
            if (!$res){
                return[
                    'error'=>-2,
                    'des'=>'统计失败',
                ];
            }

        }       //统计每位学生的成绩

        return[
            'error' => 0
        ];
    }

    //学生查看成绩
    /**
     * @return array
     */
    public function getScores(){
        $stuId = Auth::user()->stuId;

//        $testScores = DB::table('testscore')
//            ->where('stuId',$stuId)
//            ->select('testId','testScore')
//            ->orderBy('testId','asc')
//            ->get();

        $chapterScores = DB::table('chaptersum')
            ->where('stuId',$stuId)
            ->select('chapter','chapterScore')
            ->get();

        $expScores = DB::table('expcollections')
            ->where('stuId',$stuId)
            ->select('expTempId','resScore','expScore','expReportScore')
            ->get();

        $weekScores = DB::table('weekscore')
            ->where('stuId',$stuId)
            ->select('week','preScore','homeworkScore','sumScore','weekScore')
            ->get();

        //章节分数
        $chapterScore = [0,0,0,0,0,0,0];

        foreach ($chapterScores as $item){
            $chapterId = $item->chapter;
            $chapterScore[$chapterId-1] = $item->chapterScore;
        }

        //5次实验及实验报告的成绩
        $experimentScore = [0,0,0,0,0];
        $expReportScore = [0,0,0,0,0];

        foreach ($expScores as $item){
            $expTempId = $item->expTempId;
            $experimentScore[$expTempId-1] = $item->resScore * 19 + $item->expScore;
            if ($item->expReportScore){
                $expReportScore[$expTempId-1] = $item->expReportScore;
            }

        }

        //15周的课前预习、课后作业、每周总结分数、每周综合成绩
        $preScore = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $homeworkScore = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $sumScore = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $weekScore = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

        foreach ($weekScores as $item){
            $week = $item->week;
            if ($item->preScore) {
                $preScore[$week - 5] = $item->preScore;
            }
            if ($item->homeworkScore) {
                $homeworkScore[$week - 5] = $item->homeworkScore;
            }
            if ($item->sumScore) {
                $sumScore[$week - 5] = $item->sumScore;
            }
            $weekScore[$week-5] = ($item->weekScore/6)*10;
        }

        return[
            'preScore' => $preScore,
            'homeworkScore' => $homeworkScore,
            'sumScore' => $sumScore,
            'weekScore' => $weekScore,
            'chapterScore' => $chapterScore,
            'experimentScore' => $experimentScore,
            'expReportScore' => $expReportScore
        ];
    }

    //导入学生信息
    public function importStuInfo(){
        $fileName = "stuInfo.xls";
        $filePath = "../storage/app/";
        Excel::load($filePath.$fileName, function ($reader) {
            //获取excel的第1张表
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $results = $reader->toArray();
//            dd($results);

            $data = [];
            foreach ($results as $result){
                switch ($result[2]) {
                    case '计算机类m1601':
                        $class = 1;
                        break;
                    case '计算机类m1602':
                        $class = 2;
                        break;
                    case '计算机类m1603':
                        $class = 3;
                        break;
                    case '计算机类m1604':
                        $class = 4;
                        break;
                    case '计算机类m1605':
                        $class = 5;
                        break;
                    case '计算机类m1501':
                        $class = 6;
                        break;
                    case '计算机类m1503':
                        $class = 7;
                        break;
                    case '计算机类m1504':
                        $class = 8;
                        break;
                    case '计算机类m1505':
                        $class = 9;
                        break;
                    case '软件1402':
                        $class = 10;
                        break;
                    default:
                        return[
                            'error' => 'wrong class'
                        ];
                }

                $res = User::create([
                    'stuId' => $result[0],
                    'name' => $result[1],
                    'privilege' => 0,
                    'password' => bcrypt(123456),
                    'groupId' => 0,
                    'class' => $class
                ]);

                if (!$res){
                    return[
                        'error' => -2
                    ];
                }

                $data[] =[
                    'stuId' => $result[0],
                    'name' => $result[1],
                    'privilege' => 0,
                    'password' => bcrypt(123456),
                    'groupId' => 0,
                    'class' => $class
                ];
            }

            dd($data);
        });

    }

}

















