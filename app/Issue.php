<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Issue extends Model
{
    use SoftDeletes;
    protected $fillable = ['employee_id', 'inventory_id', 'year_id', 'remarks','received_status','received_at','rejecter_remarks','rejecter_remarks_at'];
}
