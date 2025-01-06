<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Itemnature;
use App\SystemLogs;

class ItemnatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:itemnature', ['only' => ['index']]);
        $this->middleware('permission:add_itemnature', ['only' => ['store']]);
        $this->middleware('permission:edit_itemnature', ['only' => ['show','update']]);
        $this->middleware('permission:delete_itemnature', ['only' => ['destroy']]);
    }
    public function index()
    {
        $itemnature = Itemnature::all();
        return view('itemnature', ['itemnatures' => $itemnature]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itemnature_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('itemnature_name'=>$request->itemnature_name, 'status'=>1);
        $create = Itemnature::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('itemnatures',$request->all(),'insert');
            return redirect()->back()->with('msg', 'Itemnature Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add itemnature, Try Again!');
        }
    }

    public function show($id)
    {
        $itemnature = Itemnature::find($id);
        return view('edit_itemnature', ['itemnature'=> $itemnature]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'itemnature_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Itemnature::where('id', $id)->update(['itemnature_name'=>$request->itemnature_name, 'status'=>$request->status]);
        if($update){
            $log = SystemLogs::Add_logs('itemnatures',Itemnature::find($id),'update');
            return redirect()->back()->with('msg', 'Itemnature Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update itemnature, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Itemnature::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('itemnatures',$find,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Itemnature Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete itemnature, Try Again!');
    }
}
