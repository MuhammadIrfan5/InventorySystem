<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Dollar extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $with = [
        'year:id,year'
    ];

    public function year()
    {
        return $this->belongsTo('App\Year');
    }
}
