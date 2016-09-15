<?php

namespace App\Http\Controllers;

use App\ExpTemplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpTempController extends Controller
{
    //存储实验模板
    public function createExpTemp(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'target' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $title = $request->title;   //标题
        $target = $request->target; //实验目标
        $content = json_encode($request->Qdesc);
        $answers = json_encode($request->answer);
        $everyAnsNum=json_encode([[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]);

        $res = ExpTemplate::create([
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

    //显示实验模板详情--老师
    public function expTempInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'expTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $info = ExpTemplate::where('id',$request->expTempId)->select('title','target','startTime','deadLine','content','answers')->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }

        return [
            'error' => 0,
            'data' => [
                'expTempId' => $request->expTempId,
                'title' => $info->title,
                'target' => $info->target,
                'startTime' => $info->startTime,
                'deadLine' => $info->deadLine,
                'Qdesc' => json_decode($info->content),
                'answer' => json_decode($info->answers)
            ]
        ];
    }

    //学生获取题目进行答题--学生答题
    public function showExpInfoStu(Request $request){
        $validator = Validator::make($request->all(), [
            'expTempId' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $expTempId = $request->expTempId;
        $info = ExpTemplate::where('id',$expTempId)->select('title','target','startTime','deadLine','content')->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }

        return [
            'error' => 0,
            "data" => [
                "expTempId" => $expTempId,
                "title" => $info->title,
                "target" => $info->target,
                "startTime" => $info->startTime,
                "deadLine" => $info->deadLine,
                "Qdesc" => json_decode($info->content)
            ]
        ];
    }

    //查看已答题目--学生
    public function checkExpInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'expTempId' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $expTempId = $request->expTempId;
        $info = DB::table('exptemplates')
            ->join('expcollections',function ($join) use ($expTempId){
                $join->on('expcollections.expTempId','=','exptemplates.id')
                    ->where('stuId','=',Auth::user()->stuId)
                    ->where('exptemplates.id','=',$expTempId);
            })
            ->select('exptemplates.id','title','target','startTime','deadLine','content','answers', 'result',
                'resScore', 'experience','expScore','marked','difficulty','expcollections.created_at')
            ->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }

        return [
            'error' => 0,
            "data" => [
                "title" => $info->title,
                "target" => $info->target,
                "startTime" => $info->startTime,
                "deadLine" => $info->deadLine,
                "Qdesc" => json_decode($info->content),
                "answer" => json_decode($info->answers),
                "result" => json_decode($info->result),
                "difficulty" => json_decode($info->difficulty),
                "selectScore" => $info->resScore,
                "experience" => $info->experience,
                "marked" => $info->marked,
                "expScore" => $info->expScore,
                "submitTime" => $info->created_at
            ]
        ];

    }

    //显示实验模板列表--老师
    public function expTempList(Request $request){
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

        $query = ExpTemplate::select('id','title','published');

        $num = $query->count();

        $ExpTemps = $query->skip($request['data']['offset'])
            ->take($request['data']['limit'])
            ->get();

        return [
            'error' => 0,
            'total' => $num,
            'rows' => $ExpTemps
        ];
    }

    //显示实验模板列表--学生
    public function expTempListStu(){
        $exptemps = DB::table('exptemplates')->where('exptemplates.published','yes')
            ->leftJoin('expcollections',function ($join){
                $join->on('expcollections.expTempId','=','exptemplates.id')
                    ->where('expcollections.stuId','=',Auth::user()->stuId);
            })
            ->select('exptemplates.id','title','startTime','deadLine','resScore','expScore')
            ->get();
        $data = [];
        foreach ($exptemps as  $exptemp){
            //判断是否在答题时间内
            if (strtotime($exptemp->startTime)<=time()&&time()<=strtotime($exptemp->deadLine)+86400){
                $duringtime = 'yes';
            }else{
                $duringtime = 'no';
            }
            //判断该学生是否已作答
            if ($exptemp->resScore===null){
                $submitted = 'no';
            }else{
                $submitted = 'yes';
            }
            $data[]=[
                'expTempId' => $exptemp->id,
                'title' => $exptemp->title,
                'startTime' => $exptemp->startTime,
                'deadLine' => $exptemp->deadLine,
                'duringtime' => $duringtime,
                'submitted' => $submitted,
                'selectscore' => $exptemp->resScore,
                'expscore' => $exptemp->expScore
            ];
        }
        return [
            'error' => 0,
            "data" => $data
        ];
    }

    //设置实验模板为发布状态
    public function publish(Request $request){
        $validator = Validator::make($request->all(), [
            'expTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = ExpTemplate::where('id',$request->expTempId)->update(['published' => 'yes']);
        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //删除实验模板
    public function deleteExpTemp(Request $request){
        $validator = Validator::make($request->all(), [
            'expTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = ExpTemplate::where('id',$request->expTempId)->delete();
        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    //修改实验模板
    public function modifyExpTemp(Request $request){

        $validator = Validator::make($request->all(), [
            'expTempId' => 'required',
            'title' => 'required',
            'target' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required',
            'startTime' => 'required',
            'deadLine' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $id = $request->expTempId;  //实验模板id
        $title = $request->title;   //标题
        $target = $request->target; //实验目标
        $content = json_encode($request->Qdesc);    //实验题目和选项
        $answers = json_encode($request->answer);   //正确答案
        $everyAnsNum=json_encode([[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]);     //每个选项所选的人数
        $res = ExpTemplate::where('id',$id)->update(['title'=>$title,
                'target'=>$target,
                'content'=>$content,
                'answers'=>$answers,
                'startTime'=>$request->startTime,
                'deadLine'=>$request->deadLine,
                'everyAnsNum'=>$everyAnsNum]
        );//更新模板

        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //统计每道题的每个答案所选的人数
    public function countNum(Request $request){
        $validator = Validator::make($request->all(),[
            'expTempId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $expCollections = DB::table('expcollections')
            ->where('expTempId',$request->expTempId)
            ->lists('result');

        if (!count($expCollections)){
            return[
                'error' => -2,
                'des' => '目前还没有人提交'
            ];
        }

        $everyAnsNum = [[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]];
        foreach ($expCollections as $expCollection) {

            $collection = json_decode($expCollection);

            for ($i=0;$i<=4;$i++){
                $everyAnsNum[$i][$collection[$i]]++;
            }
        }

        $res = DB::table('exptemplates')
            ->where('id',$request->expTempId)
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
