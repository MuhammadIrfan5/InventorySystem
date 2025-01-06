<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carryforwardstatus extends Model
{
    protected $table = 'carry_forward_status';
    protected $fillable = ['type','status'];
}
