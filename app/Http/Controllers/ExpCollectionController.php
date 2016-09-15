<?php

namespace App\Http\Controllers;

use App\ExpCollection;
use App\ExpTemplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpCollectionController extends Controller
{
    //存储学生提交的实验答卷
    public function submitExp(Request $request){
        $validator = Validator::make($request->all(), [
            'expTempId' => 'required',
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

        $deadline = ExpTemplate::where('id',$request->expTempId)->select('deadLine')->first();
        if (time()>=strtotime($deadline)+90000) {
            $stuId = Auth::user()->stuId;
            $expTempId = $request->expTempId;
            $result = $request->result;
            $experience = $request->experience;
            $difficulty = json_encode($request->difficulty);
            $resScore = $this->getresscore($expTempId, $result);     //判断选择题分数
            $res = ExpCollection::create(['stuId' => $stuId,
                    'expTempId' => $expTempId,
                    'result' => json_encode($result),
                    'resScore' => $resScore,
                    'experience' => $experience,
                    'marked' => 'no',
                    'difficulty' => $difficulty]
            );

            return [
                'error' => (!$res ? -2 : 0)
            ];
        }else{
            return [
                'error' => '-3',
                'desc' => '已过答题截止时间，不可提交'
            ];
        }
    }

    //判断选择题分数
    public function getresscore($expTempId,$results){
        $answers=ExpTemplate::where('id',$expTempId)->select('answers')->first();
        $ans = json_decode($answers->answers);
        $resScore = count(array_intersect_assoc($ans,$results));
        return $resScore;
    }

    //根据请求的模板id返回/显示该模板的所有答卷
    /**
     * @param Request $request
     * @return array
     */
    public function expCollectionList(Request $request){

        $validator = Validator::make($request->all(), [
            'expTempId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $ExpCollections = DB::table('expcollections')
            ->join('students','students.stuId','=','expcollections.stuId')
            ->where('expTempId',$request->expTempId)
            ->select('expcollections.id','expcollections.stuId','marked', 'resScore','experience','expScore','difficulty','name','class')
            ->get();

        if(!count($ExpCollections)){
            return [
                'error' => -2,
            ];
        }

        $data = [];
        foreach ($ExpCollections as $ExpCollection){

            $data[] = [
                'collectionid' => $ExpCollection->id,
                'stuId' => $ExpCollection->stuId,
                'stuclass' => $ExpCollection->class,
                'stuname' => $ExpCollection->name,
                'marked' => $ExpCollection->marked,
                'score' => $ExpCollection->resScore,
                'feedback' => $ExpCollection->experience,
                'expscore' => $ExpCollection->expScore,
                'difficulty' => json_decode($ExpCollection->difficulty)
            ];
        }

        return [
            'error' => 0,
            'data' => $data
        ];
    }


    //根据实验答卷id查询答题内容--老师
    public function showExpCollectionInfo(Request $request){

        $validator = Validator::make($request->all(), [
            'expcollectionid' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }


        $expcollectionid = $request->expcollectionid;
        $expcollection = ExpCollection::where('id',$expcollectionid)
            ->select('id','stuId','expTempId','result','resScore', 'experience','marked','expScore','difficulty')
            ->first();

        if(!$expcollection){
            return [
                'error' => -2
            ];
        }

        $exptempalte = ExpTemplate::where('id',$expcollection->expTempId)
            ->select('target','content','title','answers')
            ->first();

        if(!$exptempalte){
            return [
                'error' => -3
            ];
        }


        return [
            'error' => 0,
            'data' => [
                'expcollectionid' => $expcollection->id,
                'stuId' => $expcollection->stuId,
                'expTempId' => $expcollection->expTempId,
                'title' => $exptempalte->title,
                'target' => $exptempalte->target,
                'Qdesc' => json_decode($exptempalte->content),
                'result' => json_decode($expcollection->result),
                'answer' => json_decode($exptempalte->answers),
                'difficulty' => json_decode($expcollection->difficulty),
                'selectscore' => $expcollection->resScore,
                'feedback' => $expcollection->experience,
                'mark' => $expcollection->marked,
                'expscore' => $expcollection->expScore
            ]
        ];
    }

    //添加实验心得分数
    public function fillExpExpMark(Request $request){
        $validator = Validator::make($request->all(), [
            'expcollectionid' => 'required',
            'expscore' => 'required|integer|min:0|max:5'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $expcollectionid = $request->expcollectionid;
        $expscore = $request->expscore;
        $res = ExpCollection::where('id',$expcollectionid)->update(['expScore' => $expscore,'marked' => 'yes']);

        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    //添加实验报告分数时，返回学生列表
    public function getStuListForReportScore(Request $request){
        $validator = Validator::make($request->all(),[
            'expTempId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $expTempId = $request->expTempId;

        $lists = DB::table('students')
            ->leftJoin('expcollections',function ($join) use($expTempId){
                $join->on('expcollections.stuId','=','students.stuId')
                    ->where('expcollections.expTempId','=',$expTempId);
            })
            ->select('students.stuId','students.name','students.class','expReportScore','addReportScore')
            ->get();

        $data = [];

        if (count($lists)) {
            foreach ($lists as $list){
                if (!$list->addReportScore){
                    $addReportScore = false;
                }else{
                    $addReportScore = true;
                }

                $data[] = [
                    'stuId' => $list->stuId,
                    'name' => $list->name,
                    'class' => $list->class,
                    'filled' => $addReportScore,
                    'expReportScore' => $list->expReportScore
                ];
            }
            return $data;
        }else{
            return[
                'error' => -2
            ];
        }
    }

    //添加某个学生的实验报告分数
    public function addReportScore(Request $request){
        $validator = Validator::make($request->all(),[
            'stuId' => 'required',
            'expTempId' => 'required',
            'expReportScore' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1
            ];
        }

        //取得对应实验模板的截止时间
        $deadline = DB::table('exptemplates')
            ->where('id',$request->expTempId)
            ->select('deadLine')
            ->get();

        //该实验预习任务未到截止时间，不可添加实验报告分数
        if (time()<=strtotime($deadline[0]->deadLine)+86400){
            return[
                'error' => -3,
                'des' => '该实验预习任务未到截止时间，不可添加实验报告分数'
            ];
        }

        //查询数据库是否有对应记录，若有，则添加分数，若没有，则创建新记录
        $expCollection = DB::table('expcollections')
            ->where('stuId',$request->stuId)
            ->where('expTempId',$request->expTempId)
            ->first();

        if (count($expCollection)){                 //有记录，添加分数
            if ($expCollection->addReportScore == 'yes'){       //已有分数，不用更新
                $res = true;
            }else {
                $res = DB::table('expcollections')
                    ->where('stuId', $request->stuId)
                    ->where('expTempId', $request->expTempId)
                    ->update([
                        'expReportScore' => $request->expReportScore,
                        'addReportScore' => true
                    ]);
            }
        }else{              //没有记录，表示学生没提交该实验预习任务，创建新记录
            $res = DB::table('expcollections')
                ->insert([
                    'stuId' => $request->stuId,
                    'expTempId' => $request->expTempId,
                    'expReportScore' => $request->expReportScore,
                    'addReportScore' => true
                ]);
        }

        if (!$res){
            return[
                'error' => -2
            ];
        }else{
            return[
                'error' => 0
            ];
        }
    }

    //添加最后的实验考试成绩时获取学生列表
    public function getStuListForExpExam(){
        $lists = DB::table('students')
            ->where('privilege',0)
            ->select('stuId','name','class','expExam')
            ->get();

        $data =[];
        foreach ($lists as $list){
            if ($list->expExam == null){
                $filled = false;
            }else{
                $filled = true;
            }

            $data[] = [
                'stuId' => $list->stuId,
                'name' => $list->name,
                'class' => $list->class,
                'filled' => $filled,
                'expExam' => $list->expExam
            ];
        }

        return $data;
    }

    //添加某一位学生的实验考试成绩
    public function fillExpExam(Request $request){
        $validator = Validator::make($request->all(),[
            'stuId' => 'required',
            'expExam' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $res = DB::table('students')
            ->where('stuId',$request->stuId)
            ->update([
                'expExam' => $request->expExam
            ]);

        return[
            'error' => (!$res? -2 : 0)
        ];
    }
}



















