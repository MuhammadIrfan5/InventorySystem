<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Role;
use App\SystemLogs;
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role', ['only' => ['index']]);
        $this->middleware('permission:edit_role', ['only' => ['show','update']]);
    }
    public function index()
    {
        $role = Role::all();
        return view('role', ['roles' => $role]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $create = Role::create($request->all());
        if($create){
            $log = SystemLogs::Add_logs('roles',$request->all() ,'insert');
            return redirect()->back()->with('msg', 'Role Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add role, Try Again!');
        }
    }

    public function show($id)
    {
        $role = Role::find($id);
        return view('edit_role', ['role'=> $role]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Role::where('id', $id)->update(['role'=>$request->role]);
        if($update){
            $log = SystemLogs::Add_logs('roles',Role::find($id) ,'update');
            return redirect()->back()->with('msg', 'Role Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update role, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Role::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('roles', $find ,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Role Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete role, Try Again!');
    }
}
