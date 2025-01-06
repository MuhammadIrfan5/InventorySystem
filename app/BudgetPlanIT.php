<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetPlanIT extends Model
{
    protected $table = "budget_plan";
    protected $fillable = [
        'user_id',
        'employee_code',
        'year_id',
        'subcategory_id',
        'category_id',
        'upgraded_qty',
        'new_qty',
        'remarks',
        'optional_file_path',
        'file_name',
        'file_size',
        'file_extension',
        'other_req',
        'new_budget',
        'is_agree',
        'agreed_at',
    ];
    public function planBudgetRelation(){
        return $this->hasMany(BudgetPlanRelation::class,'plan_budget_id','id');
    }
}
