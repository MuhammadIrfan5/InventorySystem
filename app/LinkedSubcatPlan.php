<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSubcatPlan extends Model
{
    protected $table = 'linked_subcategory_budget_plan';

    protected $fillable = [
        'user_id',
        'linked_subcategory_id',
        'linked_subcat_qty',
        'budget_plan_id',
    ];
}
