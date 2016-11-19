<?php

namespace App\Http\Controllers;

use App\PreCollection;
use App\PreTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreTempController extends Controller
{

    //存储预习模板
    public function createPreTemp(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'target' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required',
            'startTime' => 'required',
            'deadLine' => 'required',
            'week' => 'required|unique:pretemplates'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $week = $request->week;
        $title = $request->title;   //标题
        $target = $request->target; //预习目标
        $content = json_encode($request->Qdesc);
        $answers = json_encode($request->answer);
        $everyAnsNum=json_encode([[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]);

        $res = PreTemplate::create([
            'week' => $week,
            'published' => 'no',
            'title'=>$title,
            'target'=>$target,
            'content'=>$content,
            'answers'=>$answers,
            'startTime'=>$request->startTime,
            'deadLine'=>$request->deadLine,
            'everyAnsNum'=>$everyAnsNum]
        );

        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //显示预习模板详情--老师
    public function preTempInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'pretempid' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $info = PreTemplate::where('id',$request->pretempid)
            ->select('title','target','startTime','deadLine','content','answers','week')
            ->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }

        return [
            'error' => 0,
            'data' => [
                'week' => $info->week,
                'pretempid' => $request->pretempid,
                'title' => $info->title,
                'target' => $info->target,
                'startTime' => $info->startTime,
                'deadline' => $info->deadLine,
                'Qdesc' => json_decode($info->content),
                'answer' => json_decode($info->answers)
            ]
        ];
    }

    //学生获取题目进行答题--学生答题
    public function showPreInfoStu(Request $request){
        $validator = Validator::make($request->all(), [
            'pretempid' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $pretempid = $request->pretempid;
        $info = PreTemplate::where('id',$pretempid)
            ->select('title','target','startTime','deadLine','content')
            ->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }

        return [
            'error' => 0,
            "data" => [
                "pretempid" => $pretempid,
                "title" => $info->title,
                "target" => $info->target,
                "startTime" => $info->startTime,
                "deadLine" => $info->deadLine,
                "Qdesc" => json_decode($info->content)
            ]
        ];
    }

    //查看已答题目--学生
    public function checkPreInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'pretempid' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $pretempid = $request->pretempid;
        $info = DB::table('pretemplates')
            ->join('precollections',function ($join) use ($pretempid){
                $join->on('precollections.preTempId','=','pretemplates.id')
                    ->where('stuId','=',Auth::user()->stuId)
                    ->where('pretemplates.id','=',$pretempid);
            })
            ->select('pretemplates.id','title','target','startTime','deadLine','content','answers', 'result',
                'resScore', 'experience','expScore','marked','remarks','difficulty','precollections.created_at')
            ->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }

        if (time() >= strtotime($info->deadLine) + 86400){
            $afterDeadline = true;
        }else{
            $afterDeadline = false;
        }

        return [
            'error' => 0,
            "data" => [
                "title" => $info->title,
                "target" => $info->target,
//                "startTime" => $info->startTime,
//                "deadLine" => $info->deadLine,
                "Qdesc" => json_decode($info->content),
                "answer" => json_decode($info->answers),
                "result" => json_decode($info->result),
                "difficulty" => json_decode($info->difficulty),
                "selectscore" => $info->resScore,
                "experience" => $info->experience,
                "marked" => $info->marked,
                "afterDeadline" => $afterDeadline,
                "remarks" => $info->remarks,
                "expScore" => $info->expScore,
//                "submitTime" => $info->created_at
            ]
        ];
    }

    //显示预习模板列表--老师
    public function preTempList(Request $request){
        $validator = Validator::make($request->all(), [
            'data.limit' => 'required',
            'data.offset' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $query = PreTemplate::select('id','title','published','week');

        $num = $query->count();

        $PreTemps = $query->skip($request['data']['offset'])
            ->take($request['data']['limit'])
            ->get();

        return [
            'error' => 0,
            'total' => $num,
            'rows' => $PreTemps
        ];
    }

    //显示预习模板列表--学生
    public function preTempListStu(){
        $pretemps = DB::table('pretemplates')->where('pretemplates.published','yes')
            ->leftJoin('precollections',function ($join){
                $join->on('precollections.preTempId','=','pretemplates.id')
                    ->where('precollections.stuId','=',Auth::user()->stuId);
            })
            ->select('pretemplates.id','title','startTime','deadLine','resScore','expScore','week')
            ->get();
        $data = [];
        foreach ($pretemps as  $pretemp){
            //判断是否在答题时间内
//            if (Auth::user()->class <= 3) {     //周二上课的学生
//
//                $startTime = $pretemp->startTime;
//                $deadLine = $pretemp->deadLine;
//
//                if (strtotime($startTime) <= time() && time() <= strtotime($deadLine) + 86400) {
//                    $duringtime = 'yes';
//                } else {
//                    $duringtime = 'no';
//                }
//
//            }else{          //周四上课的学生
//
//                $deadLine = date('Y-m-d',strtotime("$pretemp->deadLine + 2 day"));
//                $startTime = $pretemp->startTime;
//
//                if (strtotime($startTime) <= time() && time() <= strtotime($deadLine) + 86400) {
//                    $duringtime = 'yes';
//                } else {
//                    $duringtime = 'no';
//                }
//
//            }

            $startTime = $pretemp->startTime;
            $deadLine = $pretemp->deadLine;

            if (strtotime($startTime) <= time() && time() <= strtotime($deadLine) + 86400) {
                $duringtime = 'yes';
            } else {
                $duringtime = 'no';
            }

            //判断该学生是否已作答
            if ($pretemp->resScore===null){
                $submitted = 'no';
            }else{
                $submitted = 'yes';
            }
            $data[]=[
                'pretempid' => $pretemp->id,
                'title' => $pretemp->title,
                'startTime' => $startTime,
                'deadLine' => $deadLine,
                'duringtime' => $duringtime,
                'submitted' => $submitted,
                'selectscore' => $pretemp->resScore,
                'expscore' => $pretemp->expScore,
                'week' => $pretemp->week
            ];
        }
        return [
            'error' => 0,
            "data" => $data,
        ];
    }

    //设置预习模板为发布状态
    public function publish(Request $request){
        $validator = Validator::make($request->all(), [
                'pretempid' => 'required'
            ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = PreTemplate::where('id',$request->pretempid)->update(['published' => 'yes']);
        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //删除预习模板
    public function deletePreTemp(Request $request){
        $validator = Validator::make($request->all(), [
            'pretempid' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = PreTemplate::where('id',$request->pretempid)->delete();
        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    //修改预习模板
    public function modifyPreTemp(Request $request){

        $validator = Validator::make($request->all(), [
            'pretempid' => 'required',
            'title' => 'required',
            'target' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required',
            'startTime' => 'required',
            'deadLine' => 'required',
            'week' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $id = $request->pretempid;  //预习模板id
        $title = $request->title;   //标题
        $target = $request->target; //预习目标
        $week = $request->week;     //周数
        $content = json_encode($request->Qdesc);    //预习题目和选项
        $answers = json_encode($request->answer);   //正确答案
        $everyAnsNum=json_encode([[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]);     //每个选项所选的人数
        $res = PreTemplate::where('id',$id)->update([
            'title'=>$title,
            'target'=>$target,
            'content'=>$content,
            'answers'=>$answers,
            'startTime'=>$request->startTime,
            'deadLine'=>$request->deadLine,
            'everyAnsNum'=>$everyAnsNum,
            'week'=>$week
        ]);//更新模板

        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //统计每道题的每个答案所选的人数
    public function countNum(Request $request){
        $validator = Validator::make($request->all(),[
            'preTempId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $preCollections = DB::table('precollections')
            ->where('preTempId',$request->preTempId)
            ->lists('result');

        if (!count($preCollections)){
            return[
                'error' => -2,
                'des' => '目前还没有人提交'
            ];
        }

        $everyAnsNum = [[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]];
        foreach ($preCollections as $preCollection) {

            $collection = json_decode($preCollection);

            for ($i=0;$i<=4;$i++){
                $everyAnsNum[$i][$collection[$i]]++;
            }
        }

        $res = DB::table('pretemplates')
            ->where('id',$request->preTempId)
            ->update(['everyAnsNum' => json_encode($everyAnsNum)]);

        if (!count($res)){
            return[
                'error' => -2,
                'des' => '无法将计算结果保存至数据库'
            ];
        }

        return [
            'data' => $everyAnsNum
        ];
    }
}
