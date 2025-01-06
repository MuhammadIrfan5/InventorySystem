<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Department;
use App\SystemLogs;
class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $department = Department::all();
        return view('department', ['departments' => $department]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required',
            'department_code' => 'required|unique:departments,department_code',
            'department_cost_center' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
        $fields['department_desc'] = $request->department_name.' ('.$request->department_code.')';
        $create = Department::create($fields);
        if($create){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'departments',
                'meta_value'     => json_encode($request->all()),
                'action_perform' => 'insert',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Department Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add department, Try Again!');
        }
    }

    public function show($id)
    {
        $department = Department::find($id);
        return view('edit_department', ['department'=> $department]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required',
            'department_code' => 'required|unique:departments,department_code,'.$id,
            'department_cost_center' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $update = Department::where('id', $id)->update(
            [
                'department_name' => $request->department_name,
                'department_code' => $request->department_code,
                'department_cost_center' => $request->department_cost_center,
                'department_desc' => $request->department_name.' ('.$request->department_code.')'
            ]
        );
        if($update){
            $log = SystemLogs::create([
                'user_id'        => Auth()->id(),
                'email'          => Auth::user()->email,
                'table_name'     => 'departments',
                'meta_value'     => json_encode(Department::find($id)),
                'action_perform' => 'update',
                'ip'             => $request->ip(),
                'user_agent'     => $request->header('user-agent'),
                'url'            => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Department Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update department, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Department::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::create([
            'user_id'        => Auth()->id(),
            'email'          => Auth::user()->email,
            'table_name'     => 'departments',
            'meta_value'     => json_encode($find),
            'action_perform' => 'delete',
            'ip'             => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'url'            => url()->full()
        ]);
        return $find->delete() ? redirect()->back()->with('msg', 'Department Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete department, Try Again!');
    }

    public function get_department(){
        $department= Department::all();
        foreach ($department as $dept) {
//            $dept->DEPARTMENT = $dept->department_name . ' (' .$dept->department_code.')';
            $dept->DEPARTMENT = $dept->department_name; //$dept->department_code . ' ('.')' .
            $dept->DEPARTMENT_ID = $dept->department_code;
        }
        return $department;
    }
}
