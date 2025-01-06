<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorTerm extends Model
{
    protected $table = 'vendortermrelation';

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'vendor_id',
        'type_id',
        'year_id',
        'vendor_term_id',
        'invoice_count',
        'invoice_max_count'
    ];

}
