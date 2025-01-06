<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventorytype extends Model
{
    use SoftDeletes;
    protected $fillable = ['inventorytype_name','status','deleted_by'];
}
