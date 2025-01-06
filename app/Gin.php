<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Gin extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
