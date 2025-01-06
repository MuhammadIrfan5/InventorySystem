<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InventoryIssueRecord extends Model
{
    use SoftDeletes;
    protected $table = 'inventory_issue_status';
    protected $fillable = ['employee_id','employee_code', 'inventory_id', 'year_id','issued_at','receive_remarks','received_status','received_at','rejecter_remarks','rejecter_remarks_at','deleted_at','deleted_by'];
}
