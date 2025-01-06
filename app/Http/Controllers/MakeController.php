<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Makee;
use App\SystemLogs;

class MakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:make', ['only' => ['index']]);
        $this->middleware('permission:add_make', ['only' => ['store']]);
        $this->middleware('permission:edit_make', ['only' => ['show','update']]);
        $this->middleware('permission:delete_make', ['only' => ['destroy']]);
    }
    public function index()
    {
        $make = Makee::all();
        return view('make', ['makes' => $make]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'make_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('make_name'=>$request->make_name, 'status'=>1);
        $create = Makee::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('makes', $request->all() ,'insert');
            return redirect()->back()->with('msg', 'Make Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add make, Try Again!');
        }
    }

    public function show($id)
    {
        $make = Makee::find($id);
        return view('edit_make', ['make'=> $make]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'make_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Makee::where('id', $id)->update(['make_name'=>$request->make_name, 'status'=>$request->status]);
        if($update){
            $log = SystemLogs::Add_logs('makes',Makee::find($id) ,'update');
            return redirect()->back()->with('msg', 'Make Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update make, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Makee::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('makes',$find ,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Make Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete make, Try Again!');

    }
}
