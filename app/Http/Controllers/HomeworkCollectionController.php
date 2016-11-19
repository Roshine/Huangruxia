<?php

namespace App\Http\Controllers;

use App\HomeworkCollection;
use App\HomeworkQuestion;
use App\HomeworkTemplate;
use App\weekScore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class HomeworkCollectionController extends Controller
{
    //存储学生提交的课后作业答卷
    public function submitHomework(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required',
            'result' => 'required',
            'experience' => 'required',
            'difficulty' => 'required',
//            'questionId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $deadline = HomeworkTemplate::where('id',$request->homeworkTempId)->select('deadLine')->first()->deadLine;

        if (Auth::user()->class>3){
            $deadline = date('Y-m-d',strtotime("$deadline + 2 day"));
        }

        if (time()<= strtotime($deadline)+90000) {   //判断是否超过截止时间
            $stuId = Auth::user()->stuId;
            $homeworkTempId = $request->homeworkTempId;
            $result = $request->result;
            $experience = $request->experience;
            $difficulty = json_encode($request->difficulty);
            //判断是否是自动出题的模板
            if (is_int(json_decode($request->questionId[0]))){
                $questionId = json_encode($request->questionId);
                $answers = HomeworkQuestion::whereIn('id',$request->questionId)->lists('answer');
                $answer_obj = new HomeworkQuesController();
                $answers = $answer_obj->objarray_to_array($answers);
                $resScore = $this->getresscoreauto($answers, $result);     //判断选择题分数
                $answers = json_encode($answers);
            }else{
                $questionId = '';
                $answers = '';
                $resScore = $this->getresscore($homeworkTempId, $result);     //判断选择题分数
            }

            $res = HomeworkCollection::create([
                    'stuId' => $stuId,
                    'homeworkTempId' => $homeworkTempId,
                    'questionId' => $questionId,
                    'answers' => $answers,
                    'result' => json_encode($result),
                    'resScore' => $resScore,
                    'experience' => $experience,
                    'marked' => 'no',
                    'difficulty' => $difficulty]
            );

            return [
                'error' => (!$res ? -2 : 0),
            ];

        }else{
            return [
                'error' => '-3',
                'desc' => '已过答题截止时间，不可提交'
            ];
        }
    }

    //判断选择题分数--老师出题的模板
    public function getresscore($homeworkTempId,$results){
        $answers=HomeworkTemplate::where('id',$homeworkTempId)->select('answers')->first();
        $ans = json_decode($answers->answers);
        $resScore = count(array_intersect_assoc($ans,$results));
        return $resScore;
    }

    //判断选择题分数--自动出题的模板
    public function getresscoreauto($answers,$result){
        $resScore = count(array_intersect_assoc($answers,$result));
        return $resScore;
    }

    //根据请求的模板id返回/显示该模板的所有答卷
    /**
     * @param Request $request
     * @return array
     */
    public function homeworkCollectionList(Request $request){

        $validator = Validator::make($request->all(), [
            'homeworkTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $HomeworkCollections = DB::table('homeworkcollections')
            ->join('students','students.stuId','=','homeworkcollections.stuId')
            ->where('homeworkTempId',$request->homeworkTempId)
            ->select('homeworkcollections.id','homeworkcollections.stuId','marked', 'remarks','resScore','experience','expScore','difficulty','name','class','homeworkcollections.created_at')
            ->get();

        if(!count($HomeworkCollections)){
            return [
                'error' => -2,
            ];
        }

        $data = [];
        foreach ($HomeworkCollections as $HomeworkCollection){

            $data[] = [
                'collectionid' => $HomeworkCollection->id,
                'stuId' => $HomeworkCollection->stuId,
                'stuclass' => $HomeworkCollection->class,
                'stuname' => $HomeworkCollection->name,
                'marked' => $HomeworkCollection->marked,
                'remarks' => $HomeworkCollection->remarks,
                'score' => $HomeworkCollection->resScore,
                'feedback' => $HomeworkCollection->experience,
                'expscore' => $HomeworkCollection->expScore,
                'difficulty' => json_decode($HomeworkCollection->difficulty),
                'submitTime' => $HomeworkCollection->created_at
            ];
        }

        return [
            'error' => 0,
            'data' => $data
        ];
    }


    //根据课后作业答卷id查询学生答题内容--老师
    public function showHomeworkCollectionInfo(Request $request){

        $validator = Validator::make($request->all(), [
            'homeworkCollectionId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $homeworkCollectionId = $request->homeworkCollectionId;

        $info = HomeworkCollection::where('id',$homeworkCollectionId)->select('questionId','answers')->first();
        if (!is_array(json_decode($info->questionId))) {
            $homeworkcollection = HomeworkCollection::where('id', $homeworkCollectionId)
                ->select('id', 'stuId', 'homeworkTempId', 'result', 'resScore', 'experience', 'marked', 'expScore', 'difficulty')
                ->first();

            if (!$homeworkcollection) {
                return [
                    'error' => -2
                ];
            }

            $homeworktempalte = HomeworkTemplate::where('id', $homeworkcollection->homeworkTempId)
                ->select('target', 'title','content', 'answers')
                ->first();

            if (!$homeworktempalte) {
                return [
                    'error' => -3
                ];
            }

            return [
                'error' => 0,
                'data' => [
                    'aaa' => $info->questionId,
                    'homeworkCollectionId' => $homeworkcollection->id,
                    'stuId' => $homeworkcollection->stuId,
                    'homeworkTempId' => $homeworkcollection->homeworkTempId,
                    'title' => $homeworktempalte->title,
                    'target' => $homeworktempalte->target,
                    'Qdesc' => json_decode($homeworktempalte->content),
                    'result' => json_decode($homeworkcollection->result),
                    'answer' => json_decode($homeworktempalte->answers),
                    'difficulty' => json_decode($homeworkcollection->difficulty),
                    'selectscore' => $homeworkcollection->resScore,
                    'feedback' => $homeworkcollection->experience,
                    'mark' => $homeworkcollection->marked,
                    'expscore' => $homeworkcollection->expScore
                ]
            ];
        }else{
            $homeworkCollection = HomeworkCollection::where('id',$homeworkCollectionId)
                ->select('homeworkTempId','questionId','answers','result','resScore','experience','expScore','marked','difficulty')
                ->first();
            $homeworkTempalte = HomeworkTemplate::where('id',$homeworkCollection->homeworkTempId)
                ->select('title','target')
                ->first();
            $content = HomeworkQuestion::whereIn('id',json_decode($homeworkCollection->questionId))
                ->select('content')
                ->get();

            if (!$homeworkCollection||!$homeworkCollection||!$content){
                return [
                    'error' => -4
                ];
            }

            $contents = [];
            foreach ($content as $item){
                $contents[] = json_decode($item->content);
            }

            return [
                'error' => 0,
                'data' => [
                    'homeworkTempId' => $homeworkCollection->homeworkTempId,
                    'title' => $homeworkTempalte->title,
                    'target' => $homeworkTempalte->target,
                    'Qdesc' => $contents,
                    'result' => json_decode($homeworkCollection->result),
                    'answer' => json_decode($homeworkCollection->answers),
                    'difficulty' => json_decode($homeworkCollection->difficulty),
                    'selectscore' => $homeworkCollection->resScore,
                    'feedback' => $homeworkCollection->experience,
                    'mark' => $homeworkCollection->marked,
                    'expscore' => $homeworkCollection->expScore
                ]
            ];
        }
    }

    //添加课后作业心得分数
    public function fillHomeworkExpMark(Request $request){
        $validator = Validator::make($request->all(), [
            'homeworkCollectionId' => 'required',
            'expscore' => 'required|integer|min:0|max:9'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $homeworkCollectionId = $request->homeworkCollectionId;
        $expscore = $request->expscore;
        $remarks = $request->remarks;
        DB::beginTransaction();
        //评分
        $res1 = HomeworkCollection::where('id',$homeworkCollectionId)->update(['expScore' => $expscore,'remarks' => $remarks,'marked' => 'yes']);

        $res2 = $this->addHomeworkToWeek($homeworkCollectionId);


        if (!$res1){
            DB::rollBack();
            return[
                'error' => -2,
                'des' => '评分出错'
            ];
        }else{
            if ($res2['error'] == 0){
                DB::commit();
                return[
                    'error' => 0
                ];
            }else{
                DB::rollBack();
                return[
                    'error' => -2,
                    'des' => '操作失败，可以评分但无法将其保存至每周综合成绩里,评分操作已撤销'
                ];
            }
        }
    }

    //评分完后添加分数到每周综合成绩
    public function addHomeworkToWeek($homeworkcollectionid){

        //根据$homeworkcollectionid从数据库读取week、分数
        $homeworkInfo = DB::table('homeworkcollections')
            ->where('homeworkcollections.id',$homeworkcollectionid)
            ->leftJoin('homeworktemplates','homeworktemplates.id','=','homeworkTempId')
            ->leftJoin('students','students.stuId','=','homeworkcollections.stuId')
            ->select('homeworkTempId','week','resScore','expScore','homeworkcollections.stuId','groupId')
            ->get();

        $everySelectScore = 13;     //每个选择题的分数
        $homeworkProportionInWeek = 0.24;        //课后作业在一周的综合成绩里所占的比例

        $homeworkScore = $everySelectScore * $homeworkInfo[0]->resScore + $homeworkInfo[0]->expScore;      //计算百分制的课前预习分数
        $addScore = $homeworkScore * $homeworkProportionInWeek;     //计算要添加到weekScore的成绩

        //判断数据库是否有学生在这一周的记录，若有，则更新分数，没有，则创建之
        $weekInfo = DB::table('weekscore')
            ->where('stuId',$homeworkInfo[0]->stuId)
            ->where('week',$homeworkInfo[0]->week)
            ->first();

        if (count($weekInfo)){      //存在记录，更新分数
            if ($weekInfo->homeworkScore){
                $res = true;
            }else{
                $res = weekScore::where('stuId',$homeworkInfo[0]->stuId)
                    ->where('week',$homeworkInfo[0]->week)
                    ->update([
                        'homeworkScore' => $homeworkScore,
                        'weekScore' => $weekInfo->weekScore += $addScore
                    ]);
            }
        }else{      //不存在，创建记录
            $res = weekScore::create([
                'stuId' => $homeworkInfo[0]->stuId,
                'week' => $homeworkInfo[0]->week,
                'groupId' => $homeworkInfo[0]->groupId,
                'homeworkScore' => $homeworkScore,
                'weekScore' => $addScore
            ]);
        }

        return[
            'error' => (!$res? -2 : 0)
        ];
    }
}

