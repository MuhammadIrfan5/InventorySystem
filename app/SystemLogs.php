<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class SystemLogs extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','email','table_name','meta_value','action_perform','ip','url','user_agent'];

    protected $with = [
        'user:id,name',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function Add_logs($tableName, $data,$action){
        date_default_timezone_set("Asia/Karachi");
        $log = SystemLogs::create([
            'user_id'        => Auth()->id(),
            'email'          => Auth::user()->email,
            'table_name'     => $tableName,
            'meta_value'     => json_encode($data),
            'action_perform' => $action,
            'ip'             => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'url'            => url()->full()
        ]);
        if($log){
            return true;
        }
        return false;
    }

}
