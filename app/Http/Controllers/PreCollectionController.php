<?php

namespace App\Http\Controllers;

use App\PreCollection;
use App\PreTemplate;
use App\weekScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreCollectionController extends Controller
{
    //存储学生提交的预习答卷
    public function submitPre(Request $request){
        $validator = Validator::make($request->all(), [
            'pretempid' => 'required',
            'result' => 'required',
            'experience' => 'required',
            'difficulty' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $deadline = PreTemplate::where('id',$request->pretempid)->select('deadLine')->first();  //判断是否超过答题时间
        if(time()>=strtotime($deadline)+90000) {
            $stuId = Auth::user()->stuId;
            $preTempId = $request->pretempid;
            $result = $request->result;
            $experience = $request->experience;
            $difficulty = json_encode($request->difficulty);
            $resScore = $this->getresscore($preTempId, $result);     //判断选择题分数
            $res = PreCollection::create(['stuId' => $stuId,
                    'preTempId' => $preTempId,
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

    //判断选择题分数
    public function getresscore($preTempId,$results){
        $answers=PreTemplate::where('id',$preTempId)->select('answers')->first();
        $ans = json_decode($answers->answers);
        $resScore = count(array_intersect_assoc($ans,$results));
        return $resScore;
    }

    //根据请求的模板id返回/显示该模板的所有答卷
    /**
     * @param Request $request
     * @return array
     */
    public function preCollectionList(Request $request){

        $validator = Validator::make($request->all(), [
            'tempid' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $PreCollections = DB::table('precollections')
            ->join('students','students.stuId','=','precollections.stuId')
            ->where('preTempId',$request->tempid)
            ->select('precollections.id','precollections.stuId','marked', 'resScore','experience','expScore','difficulty','name','class')
            ->get();

        if(!count($PreCollections)){
            return [
                'error' => -2,
            ];
        }

        $data = [];
        foreach ($PreCollections as $PreCollection){

            $data[] = [
                'collectionid' => $PreCollection->id,
                'stuId' => $PreCollection->stuId,
                'stuclass' => $PreCollection->class,
                'stuname' => $PreCollection->name,
                'marked' => $PreCollection->marked,
                'score' => $PreCollection->resScore,
                'feedback' => $PreCollection->experience,
                'expscore' => $PreCollection->expScore,
                'difficulty' => json_decode($PreCollection->difficulty)
            ];
        }

        return [
            'error' => 0,
            'data' => $data
        ];
    }


    //根据预习答卷id查询答题内容
    public function showPreCollectionInfo(Request $request){

        $validator = Validator::make($request->all(), [
            'precollectionid' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }


        $precollectionid = $request->precollectionid;
        $precollection = PreCollection::where('id',$precollectionid)
            ->select('id','stuId','preTempId','result','resScore', 'experience','marked','expScore','difficulty')
            ->first();

        if(!$precollection){
            return [
                'error' => -2
            ];
        }

        $pretempalte = PreTemplate::where('id',$precollection->preTempId)
            ->select('target','content','title','answers')
            ->first();

        if(!$pretempalte){
            return [
                'error' => -3
            ];
        }


        return [
            'error' => 0,
            'data' => [
                'precollectionid' => $precollection->id,
                'stuId' => $precollection->stuId,
                'pretempid' => $precollection->preTempId,
                'title' => $pretempalte->title,
                'target' => $pretempalte->target,
                'Qdesc' => json_decode($pretempalte->content),
                'result' => json_decode($precollection->result),
                'answer' => json_decode($pretempalte->answers),
                'difficulty' => json_decode($precollection->difficulty),
                'selectscore' => $precollection->resScore,
                'feedback' => $precollection->experience,
                'mark' => $precollection->marked,
                'expscore' => $precollection->expScore
            ]
        ];
    }

    //添加课前预习心得分数
    public function fillPreExpMark(Request $request){
        $validator = Validator::make($request->all(), [
            'precollectionid' => 'required',
            'expscore' => 'required|integer|min:0|max:5'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $precollectionid = $request->precollectionid;
        $expscore = $request->expscore;
        DB::beginTransaction();
        //评分
        $res1 = DB::table('precollections')
            ->where('id',$precollectionid)
            ->update(['expScore' => $expscore,'marked' => 'yes']);

        //添加分数到每周综合成绩
        $res2 = $this->addPreToWeek($precollectionid);

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
                    'des' => '操作失败，可以评分但无法将其保存至每周综合成绩里，评分操作已撤销'
                ];
            }
        }
    }

    //评分完后添加分数到每周综合成绩
    public function addPreToWeek($precollectionid){

        //根据$precollectionid从数据库读取week、分数
        $preInfo = DB::table('precollections')
            ->where('precollections.id',$precollectionid)
            ->leftJoin('pretemplates','pretemplates.id','=','preTempId')
            ->leftJoin('students','students.stuId','=','precollections.stuId')
            ->select('preTempId','week','resScore','expScore','precollections.stuId','groupId')
            ->get();

        $everySelectScore = 19;     //每个选择题的分数
        $preProportionInWeek = 0.24;        //课前预习在一周的综合成绩里所占的比例

        $preScore = $everySelectScore * $preInfo[0]->resScore + $preInfo[0]->expScore;      //计算百分制的课前预习分数
        $addScore = $preScore * $preProportionInWeek;     //计算要添加的成绩

        //判断数据库是否有学生在这一周的一周综合成绩的记录，若有，则更新分数，没有，则创建之
        $weekInfo = DB::table('weekscore')
            ->where('stuId',$preInfo[0]->stuId)
            ->where('week',$preInfo[0]->week)
            ->first();

        if (count($weekInfo)){      //存在记录，更新分数
            if ($weekInfo->preScore){
                $res = true;
            }else{
                $res = weekScore::where('stuId',$preInfo[0]->stuId)
                    ->where('week',$preInfo[0]->week)
                    ->update([
                       'preScore' => $preScore,
                        'weekScore' => $weekInfo->weekScore += $addScore
                    ]);
            }
        }else{      //不存在，创建记录
            $res = weekScore::create([
                'stuId' => $preInfo[0]->stuId,
                'week' => $preInfo[0]->week,
                'groupId' => $preInfo[0]->groupId,
                'preScore' => $preScore,
                'weekScore' => $addScore
                ]);
        }

        return[
            'error' => (!$res? -2 : 0)
        ];
    }
}
