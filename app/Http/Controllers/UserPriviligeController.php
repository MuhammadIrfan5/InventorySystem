<?php

namespace App\Http\Controllers;

use App\Privilige;
use App\User;
use App\UserPrivilige;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Config;
use Illuminate\Support\Facades\Validator;

class UserPriviligeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
    }

    public function show_priviliges_by_user(Request $request)
    {
//        $demo = new UserPrivilige();
//        $demo = UserPrivilige::count_privilige_subtype(auth()->id(),'Inventory','form');
//        dd($demo);

        date_default_timezone_set('Asia/karachi');
        $data = array();
        $data['users'] = User::orderBy('name', 'asc')->get();
        $data['selected_user'] = $request->user_id;
        $data['filters'] = array();
        if (empty($request->all())) {
            $data['permission_list'] = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            if ($data['selected_user'] != 0) {
                $data['permission_list'] = UserPrivilige::where('user_id', $data['selected_user'])->get();
            } else {
                $data['permission_list'] = UserPrivilige::all();
            }
        }
        return view('list_priviliges', $data);
    }

    public function index(Request $request)
    {
        return view('list_priviliges');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|not_in:0',
            'privilige_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $arr = array();
        $privilige = array();
        if (count($request->privilige_id) > 0) {
            $user = User::find($request->user_id);
            $permission = new UserPrivilige();
            for ($i = 0; $i < count($request->privilige_id); $i++) {
                $check_duplicates = UserPrivilige::where('user_id', $request->user_id)
                    ->where('privilege_id', $request->privilige_id[$i])->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $request->privilige_id[$i];
                    $privilige['user_id'] = $request->user_id;
                    $privilige['role_id'] = $user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
            if ($check) {
                return redirect()->back()->with('msg', 'Permissions Given Successfully!' );
            } else {
                return redirect()->back()->with('msg', 'Could not give permissions, Try Again!');
            }
        } else {
            return redirect()->back()->with('msg', 'Something went wrong !, Try Again!');
        }
    }

    public function assign_priviliges_new(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = User::find($request->user_id);
        if($request->budget_type_id != null && $request->budget_perm_id != null) {
            $arr = array();
            $privilige = array();
            $budget_priv = Privilige::where('privilige_type', 'Budget')->whereIn('privilige_sub_type', $request->budget_type_id)
                ->WhereIn('privilige_action', $request->budget_perm_id)->get();
            foreach ($budget_priv as $priv){
                $check_duplicates = UserPrivilige::where('user_id', $request->user_id)
                    ->where('privilege_id', $priv->id)->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $priv->id;
                    $privilige['user_id'] = $request->user_id;
                    $privilige['role_id'] = $user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
        }
        if($request->inv_type_id != null && $request->inv_perm_id != null){
            $arr = array();
            $privilige = array();
            $inv_priv = Privilige::where('privilige_type', 'Inventory')->whereIn('privilige_sub_type', $request->inv_type_id)
                ->WhereIn('privilige_action', $request->inv_perm_id)->get();
            foreach ($inv_priv as $priv){
                $check_duplicates = UserPrivilige::where('user_id', $request->user_id)
                    ->where('privilege_id', $priv->id)->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $priv->id;
                    $privilige['user_id'] = $request->user_id;
                    $privilige['role_id'] = $user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
        }
        if($request->invoice_type_id != null && $request->invoice_perm_id != null){
            $arr = array();
            $privilige = array();
            $invoice_priv = Privilige::where('privilige_type', 'Invoice')->whereIn('privilige_sub_type', $request->invoice_type_id)
                ->WhereIn('privilige_action', $request->invoice_perm_id)->get();
            foreach ($invoice_priv as $priv){
                $check_duplicates = UserPrivilige::where('user_id', $request->user_id)
                    ->where('privilege_id', $priv->id)->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $priv->id;
                    $privilige['user_id'] = $request->user_id;
                    $privilige['role_id'] = $user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
        }
        if($request->setup_type_id != null && $request->setup_perm_id != null){
            $arr = array();
            $privilige = array();
            $setup_priv = Privilige::where('privilige_type', 'Setup')->whereIn('privilige_sub_type', $request->setup_type_id)
                ->WhereIn('privilige_action', $request->setup_perm_id)->get();
            foreach ($setup_priv as $priv){
                $check_duplicates = UserPrivilige::where('user_id', $request->user_id)
                    ->where('privilege_id', $priv->id)->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $priv->id;
                    $privilige['user_id'] = $request->user_id;
                    $privilige['role_id'] = $user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
        }
        if($request->sla_type_id != null && $request->sla_perm_id != null){
            $arr = array();
            $privilige = array();
            $sla_priv = Privilige::where('privilige_type', 'Sla')->whereIn('privilige_sub_type', $request->sla_type_id)
                ->WhereIn('privilige_action', $request->sla_perm_id)->get();
            foreach ($sla_priv as $priv){
                $check_duplicates = UserPrivilige::where('user_id', $request->user_id)
                    ->where('privilege_id', $priv->id)->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $priv->id;
                    $privilige['user_id'] = $request->user_id;
                    $privilige['role_id'] = $user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
        }else{
            return redirect()->back()->with('error-msg', 'Please Select Permissions First !, Try Again!');
        }
        return redirect()->back()->with('msg', 'Permissions Assigned Successfully !');

    }


    public function replicate_repiviliges(Request $request){
        $validator = Validator::make($request->all(), [
            'from_user_id' => 'required|not_in:0',
            'to_user_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $arr = array();
        $privilige = array();
        $from_user = UserPrivilige::where('user_id',$request->from_user_id)->get();
        if(!$from_user->isEmpty()){
            $to_user = User::find($request->to_user_id);
            foreach ($from_user as $f_user){
                $check_duplicates = UserPrivilige::where('user_id', $request->to_user_id)
                    ->where('privilege_id', $f_user->privilege_id)->first();
                if ($check_duplicates == null) {
                    $privilige['privilege_id'] = $f_user->privilege_id;
                    $privilige['user_id'] = $request->to_user_id;
                    $privilige['role_id'] = $to_user->role_id;
                    $privilige['assign_by'] = auth()->id();
                    $privilige['created_at'] = Carbon::now();
                    array_push($arr, $privilige);
                }
            }
            $check = DB::table('user_privilliges')->insert($arr);
            if ($check) {
                return redirect()->back()->with('msg', 'Permissions Replicate Successfully!' );
            } else {
                return redirect()->back()->with('error-msg', 'Could not replicate permissions, Try Again!');
            }
        }else{
            return redirect()->back()->with('error-msg', 'From user must have some permissions to replicate, Try Again!');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $privilige = UserPrivilige::find($id)->delete();
        if ($privilige) {
            return redirect()->back()->with('msg', 'Permissions Revoke Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not revoke permissions, Try Again!');
        }
    }
}
