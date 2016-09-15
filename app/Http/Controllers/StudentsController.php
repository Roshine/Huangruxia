<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            'gender' => 'required'
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
                'email' => $request->email,
                'gender' => $request->gender
            ]);

        return[
            'error' => (!$res? -2 : 0)
        ];
    }
}

















