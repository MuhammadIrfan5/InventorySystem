<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Disposalstatus;
use App\SystemLogs;
class DisposalstatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:disposalstatus', ['only' => ['index']]);
        $this->middleware('permission:add_disposal', ['only' => ['store']]);
        $this->middleware('permission:edit_disposal', ['only' => ['show','update']]);
        $this->middleware('permission:delete_disposal',   ['only' => ['destroy']]);
    }

    public function index()
    {
        $d_status = Disposalstatus::all();
        return view('disposalstatus', ['d_statuses' => $d_status]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'd_status' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);
        }
        $create = Disposalstatus::create($request->all());
        if($create){
            $log = SystemLogs::Add_logs('disposalstatuses',$request->all(),'insert');
            return redirect()->back()->with('msg', 'Disposalstatus Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add disposalstatus, Try Again!');
        }
    }

    public function show($id)
    {
        $d_status = Disposalstatus::find($id);
        return view('edit_disposalstatus', ['d_status'=> $d_status]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'd_status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Disposalstatus::where('id', $id)->update(['d_status'=>$request->d_status]);
        if($update){
            $log = SystemLogs::Add_logs('disposalstatuses',Disposalstatus::find($id),'update');
            return redirect()->back()->with('msg', 'Disposalstatus Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update disposalstatus, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Disposalstatus::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('disposalstatuses',$find,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Disposalstatus Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete disposalstatus, Try Again!');
    }
}
