<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Year;
use App\SystemLogs;
class YearController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:years', ['only' => ['index']]);
        $this->middleware('permission:add_year', ['only' => ['store']]);
        $this->middleware('permission:edit_year', ['only' => ['show','update']]);
        $this->middleware('permission:delete_year',   ['only' => ['destroy']]);
    }
    public function index()
    {
        $year = Year::all();
        return view('year', ['years' => $year]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required',
            'year_start_date' => 'required',
            'year_end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $fields = array(
            'year'            => $request->year ,
            'year_start_date' => $request->year_start_date ,
            'year_end_date'   => $request->year_end_date ,
            'is_current_year'   => ($request->is_current_year == 1) ? 1 : 0,
            'locked'   => ($request->lock_budget == 1) ? 1 : 0,
            'inventory_allowed'   => ($request->inventory_allowed == 1) ? 1 : 0,
            'is_budget_collection' => ($request->is_budget_collection == 1) ? 1 : 0
        );

        $create = Year::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('years',$request->all() ,'insert');
            return redirect()->back()->with('msg', 'Year Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add year, Try Again!');
        }
    }

    public function show($id)
    {
       $year = Year::find($id);
       return view('edit_year', ['year' => $year]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required',
            'year_start_date' => 'required',
            'year_end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array(
            'year'            => $request->year,
            'year_start_date' => $request->year_start_date ,
            'year_end_date'   => $request->year_end_date ,
            'is_current_year'   => ( $request->is_current_year == 1 ) ? 1 : 0,
            'locked'   => ($request->lock_budget == 1) ? 1 : 0,
            'inventory_allowed'   => ($request->inventory_allowed == 1) ? 1 : 0,
            'is_budget_collection' => ($request->is_budget_collection == 1) ? 1 : 0
        );
        $update = Year::where('id', $id)->update($fields);
        if($update){
            $log = SystemLogs::Add_logs('years',Year::find($id) ,'update');
            return redirect()->back()->with('msg', 'Year Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update year, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Year::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('years',$find,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Year Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete year, Try Again!');
    }
}
