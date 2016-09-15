<?php
/**
 * Created by PhpStorm.
 * User: coder
 * Date: 2016/7/19
 * Time: 14:04
 */

namespace App\Http\Controllers\Auth;

use App\Common\IDMaker;
use App\User;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


trait RegisterCustomTrait
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return $this->showRegistrationForm();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        return $this->register($request);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $valid = $this->validateRegister($request->all());

        if ($valid->fails()) {
            return [
                'error' => -1,
                'desc' => $valid->errors(),
            ];
        }

        $user = User::where('email','=',$request['email'])->first();
        if($user != null){
            return [
                'error' => -2,
                'message' => 'The email has been registered!',
            ];
        } else{
            if(User::where('name','=',$request['name'])->count() > 0)
                return [
                    "error" => -3,
                    "message" => "The username has been registered!"
                ];
        }

        $user_model = $this->createRegister($request->all());

        $this->sendActiveEmail($user_model);

        return ["error" => 0];
    }

    protected  function createRegister(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'privilege' => 0,
        ]);
    }

    protected function validateRegister(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);
    }

    public function sendActiveEmail($user)
    {
        $data = [
            'username' => $user['name'],
            'token' => IDMaker::new_token($user['id']),
            'user_id' => $user['id'],
            'email' => $user['email'],
        ];

        Mail::send('email.active_user',$data , function($message) use($data)
        {
            $message->to($data['email'], PRODUCT_NAME.' user')->subject(PRODUCT_NAME.' User Active System!');
        });
    }

    public function getRegisterActive(Request $request)
    {
        $user_id =  IDMaker::get_user_id_by_token($request['token']);

        if($user_id != 0){
            $user_model = User::where(['id' => $user_id])->first();
            if($user_model != null){
                if($user_model['privilege'] == 0){
                    $user_model['privilege'] = 1;
                    $user_model->save();
                    $msg = [
                        'title' => 'Active Success!',
                        'message' => 'the user active success'
                    ];
                } else {
                    $msg = [
                        'title' => 'Active Fail',
                        'message' => 'the user has been actived'
                    ];
                }
                $msg['name'] = $user_model['name'];
            } else {
                $msg = [
                    'title' => 'Active Fail',
                    'message' => 'the user is not exist'
                ];
            }
        } else {
            $msg = [
                'title' => 'Active Fail',
                'message' => 'the token is not valid'
            ];
        }

        if(!isset($msg['name'])){
            $msg['name'] = 'User';
        }

        return view('auth.register_active',$msg);
        //return $request['token'];
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}