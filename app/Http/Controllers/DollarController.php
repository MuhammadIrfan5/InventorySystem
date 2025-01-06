<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Dollar;
use App\SystemLogs;
class DollarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dollars', ['only' => ['index']]);
        $this->middleware('permission:add_dollar_price', ['only' => ['store']]);
        $this->middleware('permission:edit_dollar_price', ['only' => ['show','update']]);
        $this->middleware('permission:delete_dollar_price',   ['only' => ['destroy']]);
    }
    public function index()
    {
        $dollar = Dollar::all();
        return view('dollar', ['dollars' => $dollar]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pkr_val' => 'required',
            'year_id' => 'required|not_in:null'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('pkr_val'=>str_replace(",", "", $request->pkr_val), 'year_id'=>$request->year_id);
        $create = Dollar::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('dollars',$request->all(),'insert');
            return redirect()->back()->with('msg', 'Dollar Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add dollar, Try Again!');
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
        $find = Dollar::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('dollars',$find,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Dollar Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete dollar, Try Again!');
    }
}
