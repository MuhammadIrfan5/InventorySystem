<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Devicetype;
use App\SystemLogs;
class DevicetypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:devicetype', ['only' => ['index']]);
        $this->middleware('permission:add_devicetype', ['only' => ['store']]);
        $this->middleware('permission:edit_devicetype', ['only' => ['show','update']]);
        $this->middleware('permission:delete_devicetype', ['only' => ['destroy']]);
    }
    public function index()
    {
        $devicetype = Devicetype::orderBy('devicetype_name', 'asc')->get();
        return view('devicetype', ['devicetypes' => $devicetype]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'devicetype_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('devicetype_name'=>$request->devicetype_name, 'status'=>1);
        $create = Devicetype::create($fields);
        if($create){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'devicetypes',
                'meta_value'     => json_encode($request->all()),
                'action_perform' => 'insert',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Devicetype Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add devicetype, Try Again!');
        }
    }

    public function show($id)
    {
        $devicetype = Devicetype::find($id);
        return view('edit_devicetype', ['devicetype'=> $devicetype]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'devicetype_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Devicetype::where('id', $id)->update(['devicetype_name'=>$request->devicetype_name, 'status'=>$request->status]);
        if($update){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'devicetypes',
                'meta_value'     => json_encode(Devicetype::find($id)),
                'action_perform' => 'update',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Devicetype Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update devicetype, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Devicetype::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::create([
            'user_id'        => Auth()->id(),
            'email'          => Auth::user()->email,
            'table_name'     => 'devicetypes',
            'meta_value'     => json_encode($find),
            'action_perform' => 'delete',
            'ip'             => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'url'            => url()->full()
        ]);
        return $find->delete() ? redirect()->back()->with('msg', 'Devicetype Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete devicetype, Try Again!');
    }
}
