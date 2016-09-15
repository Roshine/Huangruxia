<?php

namespace App\Http\Controllers\Online;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class OnlineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getOnline()
    {
        return view('online.online_layout');
    }
}
