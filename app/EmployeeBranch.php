<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class EmployeeBranch extends Model
{
    use SoftDeletes;
    protected $table = 'employee_branch';
    protected $fillable = ['emp_id','emp_code','branch_id','branch_code','branch_name'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
