<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SLAComplainLog extends Model
{
    use SoftDeletes;
    protected $table = 'sla_complain_log';
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'type_id',
        'year_id',
        'vendor_id',
        'sla_type',
        'issue_product_sn',
        'issue_make_id',
        'issue_model_id',
        'issued_to',
        'issue_description',
        'issue_occur_date',
        'visit_date_time',
        'engineer_detail',
        'handed_over_date',
        'replace_type',
        'replace_product_sn',
        'replace_product_make_id',
        'replace_product_model_id',
        'issue_resolve_date',
        'cost_occured',
        'current_dollar_rate',
        'status',
        'added_by',
        'deleted_by',
    ];

    protected $with = [
        'category:id,category_name',
        'subcategory:id,sub_cat_name',
        'vendor:id,vendor_name,cell,contact_person,email',
        'type:id,type',
        'year:id,year',
        'user:id,name',
    ];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

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

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function year()
    {
        return $this->belongsTo('App\Year');
    }
}
