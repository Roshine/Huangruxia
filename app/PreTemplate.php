<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PreTemplate extends Model
{
    //
    protected $table='pretemplates';
    protected $guarded = [ ]; //不可修改的字段名:none,所有字段都可以修改
}


