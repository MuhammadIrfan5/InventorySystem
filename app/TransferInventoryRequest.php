<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferInventoryRequest extends Model
{
    protected $table = 'transfer_inventory_request';

    protected $fillable = [
        'from_employee_id',
        'to_employee_id',
        'to_employee_name',
        'to_employee_location',
        'to_employee_email',
        'inventory_list',
        'remarks',
        'comments',
        'status',
    ];
}
