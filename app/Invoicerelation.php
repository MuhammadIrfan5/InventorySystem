<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Invoicerelation extends Model
{
    // Base Table for invoice recording
    use SoftDeletes;
    protected $table = 'invoice_relation';
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'model_id',
        'type_id',
        'year_id',
        'vendor_id',
        'po_number',
        'purchase_date',
        'make_id',
        'item_price',
        'dollar_rate',
        'invoice_number',
        'invoice_date',
        'added_by',
        'warrenty_period',
        'warranty_end',
        'tax',
        'contract_issue_date',
        'contract_end_date',
        'item_price_tax',
        'product_sn',
        'invoice_tbl_id',
        'itemnature_id',
        'status',
    ];

    protected $with = [
        'category:id,category_name',
        'subcategory:id,sub_cat_name',
        'model:id,model_name',
        'make:id,make_name',
        'year:id,year',
        'vendor:id,vendor_name',
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }
    public function model()
    {
        return $this->belongsTo('App\Modal');
    }
    public function make()
    {
        return $this->belongsTo('App\Makee');
    }
    public function year()
    {
        return $this->belongsTo('App\Year');
    }
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
}
