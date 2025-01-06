<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Budgetitem extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $with = [
        'year:id,year',
        'category:id,category_name',
        'subcategory:id,sub_cat_name,threshold',
        'type:id,type'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }
    public function type()
    {
        return $this->belongsTo('App\Type');
    }
    public function year()
    {
        return $this->belongsTo('App\Year');
    }
}
