<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSubcategory extends Model
{
    protected $table = "linked_subcategory";
    protected $fillable = [
        'subcategory_id',
        'linked_category_id',
        'linked_subcategory_id',
        'user_id',
        'is_fixed',
    ];
}
