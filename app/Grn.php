<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Grn extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
