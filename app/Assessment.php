<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table='assessments';
    protected $guarded = [ ]; //不可修改的字段名:none,所有字段都可以修改
}
