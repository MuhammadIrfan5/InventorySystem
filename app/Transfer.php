<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transfer extends Model
{
    use SoftDeletes;
    protected $fillable = ['from_employee_id', 'to_employee_id', 'inventory_id', 'remarks'];
}
