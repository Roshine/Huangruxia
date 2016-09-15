<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Group;
use App\SumCollection;
use App\weekScore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SumCollectionController extends Controller
{
    //未分组学生提交每周总结
    public function submitSum(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required',
            'summary' => 'required',
            'selfAssessment' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $groupId = Auth::user()->groupId;
        $stuId = Auth::user()->stuId;
        $stuName = Auth::user()->name;
        $res1 = SumCollection::create([
            'weekId' => $request->weekId,
            'groupId' => $groupId,
            'stuId' => $stuId,
            'stuName' => $stuName,
            'summary' => $request->summary,
        ]);

        $res2 = Assessment::create([
            'weekId' => $request->weekId,
            'groupId' => $groupId,
            'stuId' => $stuId,
            'stuName'=> $stuName,
            'peerId' => $stuId,
            'peerName' => $stuName,
            'assessment' => $request->selfAssessment
        ]);

        return[
            'error' => ($res1&&$res2 ? 0 : -2)
        ];
    }

    //未分组的学生查看填写详情
    public function showSumCollectionInfo(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors()
            ];
        }

        $sumInfo = SumCollection::where('weekId',$request->weekId)
            ->where('stuId',Auth::user()->stuId)
            ->select('summary','sumScore')
            ->first();

        $assessment = Assessment::where('weekId',$request->weekId)
            ->where('stuId',Auth::user()->stuId)
            ->select('assessment')
            ->first();

        if (!$sumInfo||!$assessment){
            return[
                'error' => -2
            ];
        }

        return[
            'summary' => $sumInfo->summary,
            'sumScore' => $sumInfo->sumScore,
            'selfAssessment' => $assessment->assessment
        ];
    }

    //已经分组的学生提交每周小结--组长提交
    public function submitSumGroupLeader(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required',
            'assessment' => 'required',
            'summary' => 'required'
        ]);

        if ($validator->fails()) {
            return[
                'error' => -1,
                'desc' => $validator->errors()
            ];
        }

        $leaderId = Group::where('groupId',Auth::user()->groupId)
            ->select('leaderId')
            ->first();

        if (Auth::user()->stuId == $leaderId->leaderId){


                DB::beginTransaction();
                $res1 = SumCollection::create([
                    'weekId' => $request->weekId,
                    'groupId' => Auth::user()->groupId,
                    'stuId' => Auth::user()->stuId,
                    'stuName' => Auth::user()->name,
                    'summary' => $request->summary
                ]);

                $assessment = $request->assessment;

                $res2 = Assessment::insert([
                    [
                        'weekId' => $request->weekId,
                        'groupId' => Auth::user()->groupId,
                        'stuId' => Auth::user()->stuId,
                        'stuName' => Auth::user()->name,
                        'peerId' => $assessment[0]['stuId'],
                        'peerName' => $assessment[0]['stuName'],
                        'assessment' => $assessment[0]['score']
                    ], [
                        'weekId' => $request->weekId,
                        'groupId' => Auth::user()->groupId,
                        'stuId' => Auth::user()->stuId,
                        'stuName' => Auth::user()->name,
                        'peerId' => $assessment[1]['stuId'],
                        'peerName' => $assessment[1]['stuName'],
                        'assessment' => $assessment[1]['score']
                    ], [
                        'weekId' => $request->weekId,
                        'groupId' => Auth::user()->groupId,
                        'stuId' => Auth::user()->stuId,
                        'stuName' => Auth::user()->name,
                        'peerId' => $assessment[2]['stuId'],
                        'peerName' => $assessment[2]['stuName'],
                        'assessment' => $assessment[2]['score']
                    ]
                ]);

            if ($res1&&$res2){
                DB::commit();
            }else{
                DB::rollBack();
            }

            return[
                'error' => ($res1&&$res2 ? 0 : -2)
            ];
        }else{
            return[
                'error' => '不是组长，不可提交总结'
            ];
        }
    }

    //已经分组的学生提交每周小结--组员提交（不可提交小组总结）
    public function submitSumGroupMember(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required',
            'assessment' => 'required'
        ]);

        if ($validator->fails()) {
            return[
                'error' => -1,
                'desc' => $validator->errors()
            ];
        }

        $res = Assessment::insert([
            [
                'weekId' => $request->weekId,
                'groupId' => Auth::user()->groupId,
                'stuId' => Auth::user()->stuId,
                'stuName' => Auth::user()->name,
                'peerId' => $request->assessment[0]['stuId'],
                'peerName' => $request->assessment[0]['stuName'],
                'assessment' => $request->assessment[0]['score']
            ],[
                'weekId' => $request->weekId,
                'groupId' => Auth::user()->groupId,
                'stuId' => Auth::user()->stuId,
                'stuName' => Auth::user()->name,
                'peerId' => $request->assessment[1]['stuId'],
                'peerName' => $request->assessment[1]['stuName'],
                'assessment' => $request->assessment[1]['score']
            ],[
                'weekId' => $request->weekId,
                'groupId' => Auth::user()->groupId,
                'stuId' => Auth::user()->stuId,
                'stuName' => Auth::user()->name,
                'peerId' => $request->assessment[2]['stuId'],
                'peerName' => $request->assessment[2]['stuName'],
                'assessment' => $request->assessment[2]['score']
            ]
        ]);

        return [
            'error' => ($res ? 0 : -2)
        ];
    }

    //已经分组的学生查看填写总结的详情
    public function showSumInfoGroup(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $summary = SumCollection::where('groupId',Auth::user()->groupId)
            ->where('weekId',$request->weekId)
            ->select('summary','sumScore')
            ->first();
        if (!count($summary)){
            $summary = [
                'summary' => null,
                'sumScore' => null
            ];
        }

        $selfassessment = Assessment::where('weekId',$request->weekId)
            ->where('stuId',Auth::user()->stuId)
            ->where('peerId',Auth::user()->stuId)
            ->select('peerId','peerName','assessment')
            ->first();

        $assessment = Assessment::where('weekId',$request->weekId)
            ->where('stuId',Auth::user()->stuId)
            ->where('peerId','<>',Auth::user()->stuId)
            ->select('peerId','peerName','assessment')
            ->get();

        $self = [
            'stuId' => $selfassessment->peerId,
            'stuName' => $selfassessment->peerName,
            'score' => $selfassessment->assessment
        ];

        if ($assessment){
            $data =[];
            foreach ($assessment as $item){
                $data[]=[
                    'stuId' => $item->peerId,
                    'stuName' => $item->peerName,
                    'score' => $item->assessment
                ];
            }
            return[
                'self' => $self,
                'summary' => $summary['summary'],
                'sumScore' => $summary['sumScore'],
                'assessment' => $data
            ];
        }

        return[
            'error' => -2
        ];
    }

    //获取某一周学生提交的总结列表--老师
    public function getSumList(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'desc' => $validator->errors()
            ];
        }

        $weekId = $request->weekId;

        $groupList = SumCollection::where('weekId',$weekId)
            ->where('groupId','>',0)
            ->select('id','groupId','summary','marked','sumScore')
            ->orderBy('groupId')
            ->get();

        $singleList = DB::table('sumcollections')
            ->where('sumcollections.weekId',$weekId)
            ->where('sumcollections.groupId','=',0)
            ->leftJoin('assessments',function ($join){
                $join->on('assessments.stuId','=','sumcollections.stuId')
                    ->on('assessments.weekId','=','sumcollections.weekId');
            })
            ->select('sumcollections.id','sumcollections.weekId','sumcollections.stuId','sumcollections.stuName',
                'summary', 'marked','sumScore','assessment')
            ->get();

        $group = [];
        foreach ($groupList as $item){
            $group[] = [
                'sumCollectionId' => $item->id,
                'groupId' => $item->groupId,
                'summary' => $item->summary,
                'marked' => $item->marked,
                'sumScore' => $item->sumScore
            ];
        }


        $single = [];
        foreach ($singleList as $item){
            $single[] = [
                'sumCollectionId' => $item->id,
                'stuId' => $item->stuId,
                'stuName' => $item->stuName,
                'summary' => $item->summary,
                'marked' => $item->marked,
                'sumScore' => $item->sumScore,
                'selfAssessment' => $item->assessment
            ];
        }

        return[
            'group' => $group,
            'single' => $single
        ];
    }

    //查看某一小组的互评
    public function showGroupAssessment(Request $request){
        $validator = Validator::make($request->all(),[
            'weekId' => 'required',
            'groupId' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $summary = SumCollection::where('groupId',$request->groupId)
            ->where('weekId',$request->weekId)
            ->select('summary')
            ->first();

        $assessment = Assessment::where('groupId',$request->groupId)
            ->where('weekId',$request->weekId)
            ->select('stuName','peerName','assessment')
            ->get();

        if (!count($summary)||!count($assessment)){
            return[
                'error' => -2
            ];
        }

        return[
            'summary' => $summary->summary,
            'assessment' => $assessment
        ];
    }

    //老师对学生每周总结评分
    public function fillSummaryMark(Request $request){
        $validator = Validator::make($request->all(),[
            'sumCollectionId' => 'required',
            'sumScore' => 'required|min:0|max:100'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'desc' => $validator->errors()
            ];
        }

        $sumCollectionId = $request->sumCollectionId;
        $sumScore = $request->sumScore;

        DB::beginTransaction();

        $res1 = SumCollection::where('id',$sumCollectionId)
            ->update(['sumScore' => $sumScore,'marked' => 'yes']);

        $res2 = $this->addSumToWeek($sumCollectionId);

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
                    'error' => $res2['error'],
                    'des' => '操作失败，可以评分但无法将其保存至每周综合成绩里,评分操作已撤销'
                ];
            }
        }
    }

    //评完分后添加到每周综合成绩里
    public function addSumToWeek($sumCollectionId){
        $sumInfo = DB::table('sumcollections')
            ->where('sumcollections.id',$sumCollectionId)
            ->leftJoin('students','students.stuId','=','sumcollections.stuId')
            ->select('weekId','sumcollections.stuId','students.groupId','sumcollections.sumScore')
            ->get();

        if (!count($sumInfo)){
            return[
                'error' => -2
            ];
        }

        if ($sumInfo[0]->groupId) {        //是小组成员，找出小组3个学生的学号
            $groupMemberStuId = DB::table('students')
            ->where('groupId', $sumInfo[0]->groupId)
                ->lists('stuId');

            if (!count($groupMemberStuId)) {
                return [
                    'error' => -3
                ];
            }
        }else{      //为分组的学生
            $groupMemberStuId = [$sumInfo[0]->stuId];
        }

        $sumProportionInWeek = 0.12;        //每周总结在一周的综合成绩里所占的比例
        $addScore = $sumInfo[0]->sumScore * $sumProportionInWeek;

        foreach ($groupMemberStuId as $stuId){      //更新3个学生的每周综合成绩
            //判断数据库是否有学生在这一周的记录，若有，则更新分数，没有，则创建之
            $weekInfo = DB::table('weekScore')
                ->where('stuId',$stuId)
                ->where('week',$sumInfo[0]->weekId)
                ->get();

            if (count($weekInfo)){      //存在记录，更新
                if ($weekInfo->sumScore !== null){
                    $res = true;
                }else{
                    $res = weekScore::where('stuId',$stuId)
                        ->where('weekId',$sumInfo[0]->weekId)
                        ->update([
                            'sumScore' => $sumInfo[0]->sumScore,
                            'weekScore' => $weekInfo->weekScore += $addScore
                        ]);
                }
            }else{      //不存在记录，创建新纪录
                $res = weekScore::create([
                    'stuId' => $stuId,
                    'groupId' => $sumInfo[0]->groupId,
                    'week' => $sumInfo[0]->weekId,
                    'sumScore' => $sumInfo[0]->sumScore,
                    'weekScore' => $addScore
                ]);
            }

            if (!$res){
                return[
                    'error' => -4
                ];
            }
        }

        return[
            'error' => 0
        ];
    }
}
