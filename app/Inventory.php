<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'location_id',
        'department_id',
        'branch_id',
        'branch_name',
        'store_id',
        'model_id',
        'make_id',
        'vendor_id',
        'devicetype_id',
        'inventorytype_id',
        'product_sn',
        'purchase_date',
        'itemnature_id',
        'item_price',
        'dollar_rate',
        'remarks',
        'delivery_challan',
        'delivery_challan_date',
        'invoice_number',
        'invoice_date',
        'other_accessories',
        'purpose',
        'good_condition',
        'verification',
        'issued_to',
        'year_id',
        'carry_forward_year_id',
        'carry_forward_status_id',
        'type_id',
        'added_by',
        'issued_by',
        'status',
        'po_number',
        'warrenty_period',
        'insurance',
        'licence_key',
        'sla',
        'warrenty_check',
        'operating_system',
        'SAP_tag',
        'capacity',
        'hard_drive',
        'processor',
        'process_generation',
        'display_type',
        'DVD_rom',
        'RAM',
        'current_location',
        'current_consumer',
        'warranty_end',
        'tax',
        'current_cost',
        'contract_issue_date',
        'contract_end_date',
        'deleted_by',
    ];

    protected $with = [
        'category:id,category_name',
        'subcategory:id,sub_cat_name,threshold',
        'branch:id,branch_name',
        'department:id,department_name',
        'location:id,location',
        'store:id,store_name',
        'model:id,model_name',
        'make:id,make_name',
        'year:id,year',
        'type:id,type',
        'inventorytype:id,inventorytype_name',
        'vendor:id,vendor_name,contact_person',
        'itemnature:id,itemnature_name',
        'devicetype:id,devicetype_name',
        'user:id,name'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','issued_by');
    }
    public function disposals()
    {
        return $this->hasMany(Disposal::class,'inventory_id','id');
    }
    public function disposal()
    {
        return $this->hasOne(Disposal::class,'inventory_id','id');
    }
    public function year()
    {
        return $this->belongsTo('App\Year');
    }
    public function type()
    {
        return $this->belongsTo('App\Type');
    }
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
        return $this->belongsTo(Department::class,'department_id',"department_code");
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
    public function store()
    {
        return $this->belongsTo('App\Store');
    }
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
    public function inventorytype()
    {
        return $this->belongsTo('App\Inventorytype');
    }
    public function itemnature()
    {
        return $this->belongsTo('App\Itemnature');
    }
    public function devicetype()
    {
        return $this->belongsTo('App\Devicetype');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'issued_to','emp_code');
    }
    public function issue()
    {
        return $this->hasOne(Issue::class,'inventory_id','id')->latest();
    }
    public function inventoryIssueRecord()
    {
        return $this->hasOne(InventoryIssueRecord::class,'inventory_id','id')->latest();
    }
    public function dispatchins()
    {
        return $this->belongsTo(Dispatchin::class,'inventory_id','id');
    }
}
