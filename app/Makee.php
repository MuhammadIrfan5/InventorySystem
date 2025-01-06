<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Makee extends Model
{
    use SoftDeletes;
    protected $table = 'makes';
    protected $fillable = ['make_name','status','deleted_by'];
}
