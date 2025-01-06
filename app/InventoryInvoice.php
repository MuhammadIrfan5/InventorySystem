<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InventoryInvoice extends Model
{
    use SoftDeletes;
    protected $table = 'inventory_invoice';
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'model_id',
        'make_id',
        'vendor_id',
        'year_id',
        'type_id',
        'product_sn',
        'purchase_date',
        'item_price',
        'dollar_rate',
        'invoice_number',
        'invoice_date',
        'added_by',
        'status',
        'po_number',
        'warrenty_period',
        'warranty_end',
        'tax',
        'current_cost',
        'contract_issue_date',
        'contract_end_date',
        'itemnature_id',
        'product_sn',
        'item_price_tax',
        'remarks',
        'deleted_by',
    ];

    protected $with = [
        'category:id,category_name',
        'subcategory:id,sub_cat_name',
        'branch:id,branch_name',
        'department:id,department_name',
        'model:id,model_name',
        'make:id,make_name',
        'vendor:id,vendor_name,contact_person',
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
    public function model()
    {
        return $this->belongsTo('App\Modal');
    }
    public function make()
    {
        return $this->belongsTo('App\Makee');
    }
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

}
