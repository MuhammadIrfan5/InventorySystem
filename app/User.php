<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = ['name','role_id','email','password','emp_no','branch','location','contact','department','cell','designation','hdd','extention','status','deleted_by'];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $with = [
        'role:id,role'
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
