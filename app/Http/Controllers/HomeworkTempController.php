<?php

namespace App\Http\Controllers;

use App\HomeworkCollection;
use App\HomeworkQuestion;
use App\HomeworkTemplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeworkTempController extends Controller
{
    //存储课后作业模板--老师创建题目
    public function createHomeworkTemp(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'target' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required',
            'startTime' => 'required',
            'deadLine' => 'required',
            'week' => 'required|unique:homeworktemplates'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $title = $request->title;   //标题
        $target = $request->target; //课后作业目标
        $week = $request->week;
        $content = json_encode($request->Qdesc);
        $answers = json_encode($request->answer);
        $everyAnsNum=json_encode([[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]);

        $res = HomeworkTemplate::create([
                'published' => 'no',
                'title'=>$title,
                'target'=>$target,
                'week' => $week,
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

    //存储课后作业模板---自动出题
    public function createHomeworkTempAuto(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'target' => 'required',
            'startTime' => 'required',
            'deadLine' => 'required',
            'week' => 'required|unique:homeworktemplates',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $questions = HomeworkQuestion::where('week',$request->week)
            ->lists('id');

        if (count($questions)<7){
            return[
                'error' => -3,
                'desc' => '题库中该周的题目不足7道，不可创建'
            ];
        }

        $res = HomeworkTemplate::create([
            'published' => 'no',
            'title'=>$request->title,
            'target'=>$request->target,
            'content'=>'auto',
            'answers'=>'auto',
            'startTime'=>$request->startTime,
            'deadLine'=>$request->deadLine,
            'week' => $request->week
        ]);

        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    //显示课后作业模板详情--老师
    public function homeworkTempInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $info = HomeworkTemplate::where('id',$request->homeworkTempId)
            ->select('title','target','startTime','deadLine','content','answers','week')
            ->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }
        //如果content为数组，则该模板为老师出题的模板，否则为自动出题的模板
        if (is_array(json_decode($info->content))){
            return [
                'error' => 0,
                'data' => [
                    'homeworkTempId' => $request->homeworkTempId,
                    'title' => $info->title,
                    'target' => $info->target,
                    'startTime' => $info->startTime,
                    'deadLine' => $info->deadLine,
                    'week' => $info->week,
                    'Qdesc' => json_decode($info->content),
                    'answer' => json_decode($info->answers)
                ]
            ];
        }else{
            return [
                'error' => 0,
                'data' => [
                    'homeworkTempId' => $request->homeworkTempId,
                    'title' => $info->title,
                    'target' => $info->target,
                    'startTime' => $info->startTime,
                    'deadLine' => $info->deadLine,
                    'week' => $info->week
                ]
            ];
        }
    }

    //学生获取题目进行答题--学生答题
    public function showHomeworkInfoStu(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $homeworkTempId = $request->homeworkTempId;
        $info = HomeworkTemplate::where('id',$homeworkTempId)
            ->select('title','target','startTime','deadLine','content','week')
            ->first();

        if(!$info){
            return [
                'error' => -2
            ];
        }
        //如果content为数组，则该模板为老师出题的模板，否则为自动出题的模板
        if (is_array(json_decode($info->content))){
            $content = json_decode($info->content);
        }else {
            $con = new HomeworkQuesController();
            $content = $con->getcontent($info->week);       //获取随机7个题目
            if (!$content){
                return[
                    'error' => -2,
                    'des' => '获取题目失败'
                ];
            }
        }
        return [
            'error' => 0,
            "data" => [
                "homeworkTempId" => $homeworkTempId,
                "title" => $info->title,
                "target" => $info->target,
                "startTime" => $info->startTime,
                "deadLine" => $info->deadLine,
                "Qdesc" => $content
            ]
        ];

    }

    //查看已答题目--学生
    public function checkHomeworkInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $homeworkTempId = $request->homeworkTempId;

        $tempInfo = HomeworkTemplate::where('id',$homeworkTempId)->select('content','answers')->first();
        //如果content为数组，则该模板为老师出题的模板，否则为自动出题的模板
        if ($tempInfo->content !== $tempInfo->answers) {
            $info = DB::table('homeworktemplates')
                ->join('homeworkcollections', function ($join) use ($homeworkTempId) {
                    $join->on('homeworkcollections.homeworkTempId', '=', 'homeworktemplates.id')
                        ->where('stuId', '=', Auth::user()->stuId)
                        ->where('homeworktemplates.id', '=', $homeworkTempId);
                })
                ->select('homeworktemplates.id', 'title', 'target', 'startTime', 'deadLine', 'content', 'homeworktemplates.answers', 'result',
                    'resScore', 'experience', 'expScore', 'marked','remarks', 'difficulty', 'homeworkcollections.created_at')
                ->first();

            if (!$info) {
                return [
                    'error' => -2
                ];
            }

            if (time() >= strtotime($info->deadLine) + 86400*3){
                $afterDeadline = true;
            }else{
                $afterDeadline = false;
            }

            return [
                'error' => 0,
                "data" => [
                    "title" => $info->title,
                    "target" => $info->target,
//                    "startTime" => $info->startTime,
//                    "deadLine" => $info->deadLine,
                    "afterDeadline" => $afterDeadline,
                    "Qdesc" => json_decode($info->content),
                    "answer" => json_decode($info->answers),
                    "result" => json_decode($info->result),
                    "difficulty" => json_decode($info->difficulty),
                    "selectscore" => $info->resScore,
                    "experience" => $info->experience,
                    "marked" => $info->marked,
                    "remarks" => $info->remarks,
                    "expscore" => $info->expScore,
//                    "submitTime" => $info->created_at
                ]
            ];
        }else{
            $homeworkTemplate = HomeworkTemplate::where('id',$homeworkTempId)
                ->select('title','target','startTime', 'deadLine')
                ->first();
            $homeworkCollection = HomeworkCollection::where('stuId',Auth::user()->stuId)
                ->where('homeworkTempId',$homeworkTempId)
                ->select('questionId','answers','result','resScore','experience','expScore','marked','difficulty','created_at')
                ->first();
            $content = HomeworkQuestion::whereIn('id',json_decode($homeworkCollection->questionId))
                ->lists('content');

            $contents = [];
            foreach ($content as $item){
                $contents[]= json_decode($item);
            }

            if (!$homeworkTemplate||!$homeworkCollection||!$content){
                return [
                    'error' => -3
                ];
            }

            return[
                'error' => 0,
                "data" => [
                    "difficulty" => json_decode($homeworkCollection->difficulty),
                    "title" => $homeworkTemplate->title,
                    "target" => $homeworkTemplate->target,
                    "startTime" => $homeworkTemplate->startTime,
                    "deadLine" => $homeworkTemplate->deadLine,
                    "Qdesc" => $contents,
                    "answer" => json_decode($homeworkCollection->answers),
                    "result" => json_decode($homeworkCollection->result),
                    "selectscore" => $homeworkCollection->resScore,
                    "experience" => $homeworkCollection->experience,
                    "marked" => $homeworkCollection->marked,
                    "expscore" => $homeworkCollection->expScore,
                    "submitTime" => $homeworkCollection->created_at
                ]
            ];

        }
    }

    //显示课后作业模板列表--老师
    public function homeworkTempList(Request $request){
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

        $query = HomeworkTemplate::select('id','title','published','content','week');

        $num = $query->count();

        $HomeworkTemps = $query->skip($request['data']['offset'])
            ->take($request['data']['limit'])
            ->get();

        $lists = [];
        foreach ($HomeworkTemps as $homeworkTemp){
            if ($homeworkTemp->content == 'auto'){
                $type = 1;
            }else{
                $type = 0;
            }
            $lists[] = [
                "id" => $homeworkTemp->id,
                "title" => $homeworkTemp->title,
                "published" => $homeworkTemp->published,
                "type" => $type,
                "week" => $homeworkTemp->week
            ];
        }

        return [
            'error' => 0,
            'total' => $num,
            'rows' => $lists
        ];
    }

    //显示课后作业模板列表--学生
    public function homeworkTempListStu(){
        $homeworktemps = DB::table('homeworktemplates')
            ->where('homeworktemplates.published','yes')
            ->leftJoin('homeworkcollections',function ($join){
                $join->on('homeworkcollections.homeworkTempId','=','homeworktemplates.id')
                    ->where('homeworkcollections.stuId','=',Auth::user()->stuId);
            })
            ->select('homeworktemplates.id','title','startTime','deadLine','resScore','expScore','week')
            ->get();
        $data = [];
        foreach ($homeworktemps as  $homeworktemp){
            //判断是否在答题时间内
            if (Auth::user()->class <= 3) {     //周二上课的学生

                $startTime = $homeworktemp->startTime;
                $deadLine = $homeworktemp->deadLine;

                if (strtotime($startTime)<=time()&&time()<=strtotime($deadLine)+86400){
                    $duringtime = 'yes';
                }else{
                    $duringtime = 'no';
                }

            }else{          //周四上课的学生

                $startTime = date('Y-m-d',strtotime("$homeworktemp->startTime + 2 day"));
                $deadLine = date('Y-m-d',strtotime("$homeworktemp->deadLine + 2 day"));

                if (strtotime($startTime) <= time() && time() <= strtotime($deadLine) + 86400) {
                    $duringtime = 'yes';
                } else {
                    $duringtime = 'no';
                }

            }
            //判断该学生是否已作答
            if ($homeworktemp->resScore===null){
                $submitted = 'no';
            }else{
                $submitted = 'yes';
            }
            $data[]=[
                'homeworkTempId' => $homeworktemp->id,
                'title' => $homeworktemp->title,
                'startTime' => $startTime,
                'deadLine' => $deadLine,
                'duringtime' => $duringtime,
                'submitted' => $submitted,
                'selectscore' => $homeworktemp->resScore,
                'expscore' => $homeworktemp->expScore,
                'week' => $homeworktemp->week
            ];
        }
        return [
            'error' => 0,
            "data" => $data
        ];
    }

    //设置课后作业模板为发布状态
    public function publish(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = HomeworkTemplate::where('id',$request->homeworkTempId)->update(['published' => 'yes']);
        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //删除课后作业模板
    public function deleteHomeworkTemp(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = HomeworkTemplate::where('id',$request->homeworkTempId)->delete();
        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    //修改课后作业模板--老师出题
    public function modifyHomeworkTemp(Request $request){

        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required',
            'title' => 'required',
            'target' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required',
            'week' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $id = $request->homeworkTempId;  //课后作业模板id
        $title = $request->title;   //标题
        $target = $request->target; //课后作业目标
        $week = $request->week;     //周数
        $content = json_encode($request->Qdesc);    //课后作业题目和选项
        $answers = json_encode($request->answer);   //正确答案
        $everyAnsNum=json_encode([[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]);     //每个选项所选的人数
        $res = HomeworkTemplate::where('id',$id)->update([
                'title'=>$title,
                'target'=>$target,
                'content'=>$content,
                'answers'=>$answers,
                'week'=>$week,
                'startTime'=>$request->startTime,
                'deadLine'=>$request->deadLine,
                'everyAnsNum'=>$everyAnsNum]
        );//更新模板

        return [
            'error' => (!$res ? -2 : 0)
        ];

    }

    //修改课后作业模板--老师出题
    public function modifyHomeworkTempAuto(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required',
            'title' => 'required',
            'target' => 'required',
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

        $id = $request->homeworkTempId;  //课后作业模板id
        $res = HomeworkTemplate::where('id',$id)->update([
                'title'=>$request->title,
                'target'=>$request->target,
                'startTime'=>$request->startTime,
                'deadLine'=>$request->deadLine,
                'week' => $request->week
            ]
        );//更新模板

        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    //统计每道题的每个答案所选的人数
    public function countNum(Request $request){
        $validator = Validator::make($request->all(),[
            'homeworkTempId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $type = DB::table('homeworkTempId')
            ->where('id',$request->homeworkTempId)
            ->select('content')
            ->first();

        if ($type->content == 'auto'){
            return[
                'error' => -1,
                'des' => '该模板由系统自动出题，不可统计'
            ];
        }

        $homeworkCollections = DB::table('homeworkcollections')
            ->where('homeworkTempId',$request->homeworkTempId)
            ->lists('result');

        if (!count($homeworkCollections)){
            return[
                'error' => -2,
                'des' => '目前还没有人提交'
            ];
        }

        $everyAnsNum = [[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]];
        foreach ($homeworkCollections as $homeworkCollection) {

            $collection = json_decode($homeworkCollection);

            for ($i=0;$i<=6;$i++){
                $everyAnsNum[$i][$collection[$i]]++;
            }
        }

        $res = DB::table('homeworktemplates')
            ->where('id',$request->homeworkTempId)
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
