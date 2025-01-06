<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Term extends Model
{
    use SoftDeletes;
    protected $table = 'vendorterm';

    protected $fillable = [
        'term_type',
        'term_number',
        'status',
    ];

}
