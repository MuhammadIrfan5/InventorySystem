<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Itemnature extends Model
{
    use SoftDeletes;
    protected $fillable = ['itemnature_name','status'];
}
