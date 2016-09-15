<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    //已经分组的学生进入每周小结填写页面时获取小组内成员信息
    public function getGroupMember(){
        $groupId = Auth::user()->groupId;
        $members = User::where('groupId',$groupId)
            ->where('stuId','<>',Auth::user()->stuId)
            ->select('stuId','name')
            ->get();

        $groupmember = [];
        foreach ($members as $member){
            $groupmember[] = [
                'stuId' => $member->stuId,
                'stuName' => $member->name
            ];
        }

        $leaderId = Group::where('groupId',$groupId)
            ->select('leaderId')
            ->first();
        if (Auth::user()->stuId == $leaderId->leaderId){
            $leader = true;
        }else{
            $leader = false;
        }

        return[
            'self' => [
                'stuId' => Auth::user()->stuId,
                'stuName' => Auth::user()->name
            ],
            'groupmember' => $groupmember,
            'leader' => $leader
        ];
    }
}
