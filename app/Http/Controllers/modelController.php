<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Modal;
use App\Makee;
use App\SystemLogs;
class modelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:model', ['only' => ['index']]);
        $this->middleware('permission:add_model', ['only' => ['store']]);
        $this->middleware('permission:edit_model', ['only' => ['show','update']]);
        $this->middleware('permission:delete_model', ['only' => ['destroy']]);
    }
    public function index()
    {
        $model = Modal::all();
        return view('model', ['models' => $model]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model_name' => 'required',
            'make_id' => 'required|not_in:null'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('model_name'=>$request->model_name, 'make_id'=>$request->make_id, 'status'=>1);
        $create = Modal::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('models',$request->all() ,'insert');
            return redirect()->back()->with('msg', 'Model Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add model, Try Again!');
        }
    }

    public function show($id)
    {
        $make = Makee::where('status',1)->orderBy('make_name', 'asc')->get();
        $model = Modal::find($id);
        return view('edit_model', ['model'=> $model, 'makes' => $make]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'model_name' => 'required',
            'make_id' => 'required|not_in:null'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Modal::where('id', $id)->update(['model_name'=>$request->model_name,'make_id'=>$request->make_id, 'status'=>$request->status]);
        if($update){
            $log = SystemLogs::Add_logs('models',Modal::find($id) ,'update');
            return redirect()->back()->with('msg', 'Model Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update model, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Modal::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('models', $find ,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Model Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete model, Try Again!');
    }
}
