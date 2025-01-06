<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Modal extends Model
{
    use SoftDeletes;
    protected $table = 'models';
    protected $fillable = ['model_name','make_id','status','deleted_by'];

    protected $with = [
        'make:id,make_name'
    ];

    public function make()
    {
        return $this->belongsTo('App\Makee');
    }
}
