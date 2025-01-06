<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Rturn extends Model
{
    use SoftDeletes;
    protected $table = 'returns';
    protected $fillable = ['employee_id', 'inventory_id', 'remarks'];
}
