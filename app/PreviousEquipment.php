<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreviousEquipment extends Model
{
    use SoftDeletes;
    protected $table = 'previous_equipment';
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'inventory_id',
        'dept_id',
        'user_id',
        'purchased_date',
        'disposalstatus_id',
        'remarks',
    ];
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function subcategory(){
        return $this->belongsTo(Subcategory::class,'subcategory_id','id');
    }
    public function inventory(){
        return $this->belongsTo(Inventory::class,'inventory_id','id');
    }
}
