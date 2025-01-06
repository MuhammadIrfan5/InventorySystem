<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = ['department_name','department_code','department_cost_center','department_desc','deleted_by'];
}
