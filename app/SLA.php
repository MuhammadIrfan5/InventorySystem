<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SLA extends Model
{
    use SoftDeletes;
    protected $table = 'service_level_agreement';
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'type_id',
        'year_id',
        'vendor_id',
        'qty',
        'agreement_start_date',
        'agreement_end_date',
        'current_dollar_rate',
        'current_sla_cost',
        'consumed_sla_cost',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = [
        'category:id,category_name',
        'subcategory:id,sub_cat_name',
        'vendor:id,vendor_name,cell,contact_person,email',
        'type:id,type',
        'year:id,year',
        'user:id,name',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
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
