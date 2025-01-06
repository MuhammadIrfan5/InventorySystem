<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\EmployeeBranch;
class Employee extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $with = [
        'branches:id,branch_name,branch_code',
    ];

    public function branches()
    {
        return $this->hasMany(EmployeeBranch::class, 'emp_id','id');
    }

}
