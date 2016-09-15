<?php

namespace App;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class HomeworkTemplate extends Model
{
    protected $table = 'homeworktemplates';
    protected $guarded = [ ]; //不可修改的字段名:none,所有字段都可以修改
}