<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Subcategory extends Model
{
    use SoftDeletes;
    protected $fillable = ['category_id','sub_cat_name','subcat_desc','is_budget_collection','approx_price_pkr','approx_price_dollar','price_updated_at','is_fixed','threshold','status','deleted_by'];

    protected $with = [
        'category:id,category_name',
//        'linkedSubcategory:id,sub_cat_name'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function linkedSubcategory()
    {
        return $this->hasMany(LinkedSubcategory::class, 'subcategory_id','id');
    }
}
