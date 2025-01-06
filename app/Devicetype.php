<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Devicetype extends Model
{
    use SoftDeletes;
    protected $fillable = ['devicetype_name','status','deleted_by'];
}
