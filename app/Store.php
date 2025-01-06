<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Store extends Model
{
    use SoftDeletes;
    protected $fillable = ['store_name','location_id','emp_id','deleted_by'];
    
    protected $with = [
        'location:id,location'
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
