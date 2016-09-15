<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacherAuth');
    }

    public function getTeacher()
    {
        return view('teacher.teacher_layout');
    }
}
