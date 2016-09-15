<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class testcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getTest()
    {
        return view('test.test');
    }
}
