<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Location;
use App\SystemLogs;
class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:location', ['only' => ['index']]);
        $this->middleware('permission:add_location', ['only' => ['store']]);
        $this->middleware('permission:edit_location', ['only' => ['show','update']]);
        $this->middleware('permission:delete_location', ['only' => ['destroy']]);
    }
    public function index()
    {
        $location = Location::all();
        return view('location', ['locations' => $location]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $create = Location::create($request->all());
        if($create){
            $log = SystemLogs::Add_logs('locations',$request->all(),'insert');
            return redirect()->back()->with('msg', 'Location Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add location, Try Again!');
        }
    }

    public function show($id)
    {
        $location = Location::find($id);
        return view('edit_location', ['location'=> $location]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Location::where('id', $id)->update(['location'=>$request->location]);
        if($update){
            $log = SystemLogs::Add_logs('locations',Location::find($id),'update');
            return redirect()->back()->with('msg', 'Location Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update location, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Location::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('locations',$find,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Location Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete location, Try Again!');
    }
}
