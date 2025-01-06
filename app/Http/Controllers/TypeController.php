<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Type;
use App\SystemLogs;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dispose', ['only' => ['index']]);
        $this->middleware('permission:add_d_status', ['only' => ['store']]);
        $this->middleware('permission:edit_d_status', ['only' => ['show','update']]);
        $this->middleware('permission:delete_d_status',   ['only' => ['destroy']]);
    }
    public function index()
    {
        $type = Type::all();
        return view('type', ['types' => $type]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('type'=>$request->type);
        $create = Type::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('types',$request->all() ,'insert');
            return redirect()->back()->with('msg', 'Type Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add type, Try Again!');
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $find = Type::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('types',$find ,'insert');
        return $find->delete() ? redirect()->back()->with('msg', 'Type Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete type, Try Again!');
    }
}
