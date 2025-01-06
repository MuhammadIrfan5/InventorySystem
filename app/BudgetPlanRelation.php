<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetPlanRelation extends Model
{
    protected $table = "plan_budget_relation";
    protected $fillable = [
        'plan_budget_id',
        'user_id',
        'emp_code',
        'subcategory_id',
        'category_id',
        'upgraded_qty',
        'new_qty',
        'approx_cost',
        'linked_subcategory',
        'remarks',
        'previous_year',
        'types_id',
    ];
    public function subcategory(){
        return $this->belongsTo(Subcategory::class,'subcategory_id','id');
    }
    public function employee(){
        return $this->belongsTo(BudgetPlanIT::class,'plan_budget_id','id');
    }
}
