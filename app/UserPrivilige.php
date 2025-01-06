<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Privilige;

class UserPrivilige extends Model
{
    protected $table = 'user_privilliges';
    protected $fillable = ['privilige_id','user_id','role_id','assign_by'];
    protected $with = [
        'role:id,role',
        'user:id,name',
        'privilige:id,privilige_title'
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function privilige()
    {
        return $this->belongsTo('App\Privilige','privilege_id');
    }

    static function check_user_privilige($user_id,$privilige_id){
        $check=DB::table('user_privilliges')
            ->select('user_id','privilege_id')
            ->where('user_id',$user_id)
            ->where('privilege_id',$privilige_id)
            ->get();
        if(!empty($check[0]->user_id) && !empty($check[0]->privilege_id))
        {
            return true;
        }else{
            return false;
        }
    }

    public function get_user_priviliges($role_id,$user_id){
//        $check=DB::table('user_privilliges')
//            ->select('role_id','user_id','privilege_id')
//            ->where('role_id',$role_id)
//            ->where('user_id',$user_id)
//            ->get();

        $check = DB::table('user_privilliges')
            ->join('priviliges', 'user_privilliges.privilege_id', '=', 'priviliges.id')
            ->select('user_privilliges.privilege_id', 'priviliges.privilige_url','user_privilliges.role_id','user_privilliges.user_id')
            ->where('role_id',$role_id)
            ->where('user_id',$user_id)
            ->get();

        if($check != null)
        {
            return $check;
        }else{
            return false;
        }
    }

    static function get_single_privilige($user_id,$privilige_url){
        $privilige = Privilige::where('privilige_url',$privilige_url)->first();
        if($privilige != null) {
            $check = DB::table('user_privilliges')
                ->select('role_id', 'user_id', 'privilege_id')
                ->where('user_id', $user_id)
                ->where('privilege_id', $privilige->id)
                ->first();
            if ($check != null) {
                return true;
            } else {
                return false;
            }
        }
    }

    static function count_user_privilige($user_id){
        $count = UserPrivilige::where('user_id',$user_id)->count();
        return $count;
    }

    static function count_privilige_type($user_id,$type){
        $check = DB::table('user_privilliges')
            ->join('priviliges', 'user_privilliges.privilege_id', '=', 'priviliges.id')
            ->select('user_privilliges.privilege_id', 'priviliges.privilige_url','user_privilliges.role_id','user_privilliges.user_id')
            ->where('user_id',$user_id)
            ->where('priviliges.privilige_type',$type)
            ->count();
        if ($check != null) {
            return $check;
        } else {
            return 0;
        }
    }

    static function count_privilige_subtype($user_id,$type,$sub_type){
        $check = DB::table('user_privilliges')
            ->join('priviliges', 'user_privilliges.privilege_id', '=', 'priviliges.id')
            ->select('user_privilliges.privilege_id', 'priviliges.privilige_url','user_privilliges.role_id','user_privilliges.user_id')
            ->where('user_id',$user_id)
            ->where('priviliges.privilige_type',$type)
            ->where('priviliges.privilige_sub_type',$sub_type)
            ->count();
        if ($check != null) {
            return $check;
        } else {
            return 0;
        }
    }

}
