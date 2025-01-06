<?php

namespace App\Http\Controllers;

use App\Department;
use App\Inventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Employee;
use App\EmployeeBranch;
use App\SystemLogs;
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:employee', ['only' => ['index']]);
        $this->middleware('permission:add_employee', ['only' => ['store']]);
//        $this->middleware('permission:delete_employee', ['only' => ['destroy']]);
        $this->middleware('permission:edit_employee', ['only' => ['show', 'update']]);
    }

    public function index()
    {
        $employee = Employee::orderBy('name', 'asc')->get();
        return view('employee', ['employees' => $employee]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_code'    => 'required',
            'name'        => 'required',
            'branch_id'   => 'required|not_in:null',
            'location'    => 'required',
            'department'  => 'required',
            'designation' => 'required',
            'hod'         => 'required',
            'status'      => 'required',
            'email'       => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $fields = $request->all();
//        if($fields['branch_id'] != 0){
//            $fields['dept_id'] = $fields['branch_id'];
//            $fields['department'] = $fields['branch'];
//        }
        $create = Employee::create([
            "emp_code"    => $fields['emp_code'],
            "name"        => $fields['name'],
            "designation" => $fields['designation'],
            "department"  => $fields['branch'],
            "location"    => $fields['location'],
            "hod"         => $fields['hod'],
            "email"       => $fields['email'],
            "status"      => $fields['status'],
            "branch"      => $fields['branch']
        ]);
        if ($create) {
            $branch_name = explode(',', $fields['branch']);
            foreach ($fields['branch_id'] as $key => $branch_id) {
                DB::table('employee_branch')->insert([
                    'emp_id'      => $create->id,
                    'emp_code'    => $fields['emp_code'],
                    'branch_id'   => $branch_id,
                    'branch_code' => $branch_id,
                    'branch_name' => $branch_name[$key],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
                $data       = [
                    'emp_id'      => $create->id,
                    'emp_code'    => $fields['emp_code'],
                    'branch_id'   => $branch_id,
                    'branch_code' => $branch_id,
                    'branch_name' => $branch_name[$key],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
                $branch_log = SystemLogs::Add_logs('employee_branch', $data, 'insert');
            }
            $log = SystemLogs::Add_logs('employees', $request->all(), 'insert');
            return redirect()->back()->with('msg', 'Employee Added Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not add employee, Try Again!');
        }
    }

    public function show($id)
    {
        $obj      = new FormController();
        $employee = Employee::find($id);
        $emp_data = EmployeeBranch::where('emp_id', $id)->get();
        $Link     = DB::table('links')->get()[0]->url;
//        $api = $obj->callAPI('GET',$Link.'branchdataall.php?uid=1',false);
        $api = $obj->callAPI('GET', $Link . 'deptdataall.php?uid=1', false);
//        $branches = json_decode($api,true);
        $json_branches = json_decode($api, true);
        $department    = Department::all();
        foreach ($department as $dept) {
            $dept->DEPARTMENT    = $dept->department_name . ' (' . $dept->department_code . ')';
            $dept->DEPARTMENT_ID = $dept->department_code;
        }
        $branches['Login'] = array_merge($json_branches['Login'], $department->toArray());
        return view('edit_employee', ['employee' => $employee, 'branches' => $branches, 'emp_data' => $emp_data]);
    }

    public function destroy($id)
    {
        $find             = Employee::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('employees', $find, 'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Employee Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete employee, Try Again!');
    }

    public function update(Request $request, $id)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
//            'branch' => 'required|not_in:null',
            'location'    => 'required',
            'department'  => 'required',
            'designation' => 'required',
            'hod'         => 'required',
            'status'      => 'required',
            'email'       => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $fields = $request->all();
        $update = Employee::where('id', $id)->update(
            [
                "emp_code"    => $fields['emp_code'],
                "name"        => $fields['name'],
                "designation" => $fields['designation'],
                "department"  => $fields['department'],
                "location"    => $fields['location'],
                "hod"         => $fields['hod'],
                "email"       => $fields['email'],
                "status"      => $fields['status'],
//                "branch" =>$fields['branch']
            ]
        );

        if ($update) {
            $branch_name = explode(',', $fields['branch_name']);
            foreach ($fields['branch_id'] as $key => $branch_id) {
                EmployeeBranch::updateOrCreate([
                    'emp_id'    => $id,
                    'branch_id' => $branch_id
                ], [
                    'emp_id'      => $id,
                    'emp_code'    => $fields['emp_code'],
                    'branch_id'   => $branch_id,
                    'branch_code' => $branch_id,
                    'branch_name' => $branch_name[$key],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
                $data = [
                    'emp_id'      => $id,
                    'emp_code'    => $fields['emp_code'],
                    'branch_id'   => $branch_id,
                    'branch_code' => $branch_id,
                    'branch_name' => $branch_name[$key],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
//                $branch_log = SystemLogs::Add_logs('employee_branch',$data,'insert');
            }

            $data_new = EmployeeBranch::where('emp_id', $id)->whereNotIn('branch_id', $fields['branch_id'])->get();
            foreach ($data_new as $emp) {
                $emp->delete();
            }
//            $log = SystemLogs::Add_logs('employees',$request->all(),'update');
            return redirect()->back()->with('msg', 'Employee Updated Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not update employee, Try Again!');
        }
    }

    public function get_employee($id)
    {
        $find = Employee::where('emp_code', $id)->first();
        return $find ?? 0;
    }

    public function get_employee_with_inventory($id)
    {
        $find      = Employee::where('emp_code', $id)->first();
        $inventory = array();
        if (!empty($find)) {
            $response['user'] = $find;
            $inventory        = Inventory::where('issued_to', $find->emp_code)->where('devicetype_id', '!=', 5)->orderBy('id', 'desc')->get();
            foreach ($inventory as $record) {
                $response['inventory'][] = [
                    "id"               => $record->id,
                    "ProductS"         => $record->product_sn,
                    "Make"             => $record->make->make_name ?? "",
                    "Model"            => $record->model->model_name ?? "",
                    "currentCondition" => 'In Use' ?? "",
                ];
            }
        }

        return !empty($response) ? $response : 0;
    }

    public function get_employee_branch($emp_code)
    {
        $find = DB::table('employee_branch')->where('emp_code', $emp_code)->get();

        return $find ?? 0;
    }

    public function employees_by_dept($dept_id)
    {
        $find = array();
        if ($dept_id == 0) {
            $find = Employee::orderBy('name', 'asc')->get();
        } else {
            $find_employees = DB::table('employee_branch')->where('branch_id', $dept_id)->get()->unique('emp_code');
            foreach ($find_employees as $employee) {
                $find[] = Employee::where('emp_code', $employee->emp_code)->orderBy('name', 'asc')->first();
            }
        }
        return $find ?? 0;
    }
}
