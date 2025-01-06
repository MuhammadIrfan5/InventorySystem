<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Disposal extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $with = [
        'category:id,category_name',
        'subcategory:id,sub_cat_name',
        'disposalstatus:id,d_status',
        'inventory:id,product_sn,purchase_date,item_price,dollar_rate,make_id,model_id,devicetype_id,location_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }
    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }
    public function disposalstatus()
    {
        return $this->belongsTo('App\Disposalstatus');
    }
}
