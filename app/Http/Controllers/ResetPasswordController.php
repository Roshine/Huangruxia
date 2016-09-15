<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    //修改密码
    public function Resetpassword(Request $request){
        $validator = Validator::make($request->all(),[
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);
        if ($validator->fails()){
            return[
               'error' => -1,
                'des' => $validator->errors()
            ];
        }

        $stuId = Auth::user()->stuId;

        $password = DB::table('students')
            ->where('stuId',$stuId)
            ->select('password')
            ->first();

//        return $password->password;
        if (!Hash::check($request->oldPassword,$password->password)){
            return[
                'error' => -3,
                'des' => '输入的原密码错误'
            ];
        }

        $res = DB::table('students')
            ->where('stuId',$stuId)
            ->update([
                'password' => bcrypt($request->newPassword)
            ]);

        return[
            'error' => (!$res? -2 : 0),
        ];
    }
}
