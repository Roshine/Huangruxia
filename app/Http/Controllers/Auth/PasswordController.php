<?php

namespace App\Http\Controllers\Auth;

use App\Common\IDMaker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PasswordResets;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', []);
    }

    public function getForgetPassword()
    {
        return view('auth.forget_password');
    }

    protected function createPasswordReset($data)
    {

        return PasswordResets::create([
            'stuId' => $data['stuId'],
            'token' => IDMaker::guid(),
        ]);
    }

    public function postForgetPassword(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'stuId' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        if($valid->fails()){
            return [
                'error' => -1,
                'desc' => $valid->errors()
            ];
        }

        $user = User::where('stuId','=', $request->stuId)->first();
        if($user == null){
            return [
                'error' => -2,
                'message' => '学号还未注册!'
            ];
        }

        if ($user->email==$request->email) {

            $password = $this->createPasswordReset($request->all());

            $this->sendResetEmail($password, $user);

            return [
                'error' => 0,
            ];
        }else{
            return[
                'error' => -3,
                'message' => '填写的邮箱与注册的邮箱不一样，请填写注册所用的邮箱'
            ];
        }

    }

    public function sendResetEmail($password,$user)
    {
        $data = [
            'stuId' => $user->stuId,
            'name' => $user->name,
            'token' => $password['token'],
            'email' => $user->email,
        ];

        Mail::send('email.forget_password',$data , function($message) use($data)
        {
            $message->to($data['email'], PRODUCT_NAME.' user')->subject(PRODUCT_NAME.' 重置密码!');
        });

        return $data;
    }

    public function getResetPassword()
    {
        return view('auth.reset_password');
    }

    public function postResetPassword(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'stuId' => 'required|max:255',
            'token' => 'required|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        if($valid->fails()){
            return [
                'error' => -1,
                'message' => $valid->errors()
            ];
        }

        $password = PasswordResets::where('stuId','=',$request['stuId'])->where('token','=',$request['token'])->first();
        if($password == null){
            return [
                'error' => -2,
                'message' => 'The token is not valid',
            ];
        }

        if(User::where('stuId','=',$request['stuId'])->update(['password' => bcrypt($request['password'])])){
            PasswordResets::where('stuId','=',$request['stuId'])->where('token','=',$request['token'])->delete();
            return [
                'error' => 0
            ];
        } else {
            return [
                'error' => -3,
                'message' => 'The user is not found',
            ];
        }
    }
}
