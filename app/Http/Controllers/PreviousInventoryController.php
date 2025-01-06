<?php

namespace App\Http\Controllers;

use App\Category;
use App\Employee;
use App\EmployeeBranch;
use App\Inventory;
use App\InventoryIssueRecord;
use App\Issue;
use App\PreviousEquipment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Modal;
use App\Makee;
use App\SystemLogs;

class PreviousInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:previous_inventory', ['only' => ['index']]);
        $this->middleware('permission:add_previous_inventory', ['only' => ['store']]);
        $this->middleware('permission:edit_previous_inventory', ['only' => ['show', 'update']]);
        $this->middleware('permission:delete_previous_inventory', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
//        dd('us');
        $previousEquipment = new PreviousEquipment();
        $previousEquipment = $previousEquipment->get();
        return view('previous_equipment', ['previousEquipment' => $previousEquipment]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'inventory_id' => 'required',
            'branch_id' => 'required',
            'employee_id' => 'required',
            'remarks' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        /*get previous inventory*/
        $previousInventory = Inventory::where('id', $request->inventory_id)->where('subcategory_id', $request->subcategory_id)->where('category_id', $request->category_id)->first();
//        dd($request->all(),$previousInventory);
        if (!empty($previousInventory)) {
            $employeeId = EmployeeBranch::where('emp_id', $request->employee_id)->first();
            $data = [
                'category_id' => $previousInventory->category_id,
                'subcategory_id' => $previousInventory->subcategory_id,
                'inventory_id' => $previousInventory->id,
                'dept_id' => $request->branch_id,
                'user_id' => $employeeId->emp_code,
                'purchased_date' => $previousInventory->purchase_date,
                'disposalstatus_id' => 0,
                'remarks' => $request->remarks,
            ];

            $issuedTo = PreviousEquipment::create($data);
            $update = Inventory::where('id', $request->inventory_id)->update(['issued_to' => $employeeId->emp_code, 'issued_by' => $request->user()->id, 'devicetype_id' => 3, 'branch_id' => $request->branch_id, 'branch_name' => $employeeId->branch_name, 'department_id' => $request->branch_id, 'remarks' => $request->remarks]);
            $insert = Issue::create(['employee_id' => $employeeId->emp_code, 'inventory_id' => $request->inventory_id, 'year_id' => $previousInventory->year_id, 'remarks' => 'Through previous equipment form']);
            $insert_issue = InventoryIssueRecord::create([
                'employee_id' => $request->employee_id,
                'employee_code' => $employeeId->emp_code,
                'inventory_id' => $request->inventory_id,
                'year_id' => $previousInventory->year_id,
                'received_status' => 0,
                'issued_at' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $log = SystemLogs::Add_logs('previousequipment', $request->all(), 'insert');
            return redirect()->back()->with('msg', 'Previous Inventory Issued Successfully!' . $request->inventory_id);
        } else {
            return redirect()->back()->with('msg', 'Could not add, Try Again!');
        }
    }

    public function show($id)
    {
        $data['departments'] = EmployeeBranch::groupBy('branch_name')->get();
        $data['previousEquipment'] = PreviousEquipment::find($id);
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('edit_previousEquipment', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'inventory_id' => 'required',
            'branch_id' => 'required',
            'employee_id' => 'required',
            'remarks' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $previousInventory = PreviousEquipment::find($id);
        $inventory = Inventory::where('id', $request->inventory_id)->where('subcategory_id', $request->subcategory_id)->where('category_id', $request->category_id)->first();
        if (!empty($inventory)) {
            $employeeId = EmployeeBranch::where('emp_code', $request->employee_id)->first();
            if (!empty($request->category_id)) {
                $previousInventory->category_id = $request->category_id;
            }
            if (!empty($request->subcategory_id)) {
                $previousInventory->subcategory_id = $request->subcategory_id;
            }
            if (!empty($request->inventory_id)) {
                $previousInventory->inventory_id = $request->inventory_id;
            }
            if (!empty($request->branch_id)) {
                $previousInventory->dept_id = $request->branch_id;
            }
            if (!empty($employeeId->emp_code)) {
                $previousInventory->user_id = $employeeId->emp_code;
            }
            if (!empty($request->remarks)) {
                $previousInventory->remarks = $request->remarks;
            }
            $previousInventory->save();
            $update = Inventory::where('id', $request->inventory_id)->update(['issued_to' => $employeeId->emp_code, 'issued_by' => $request->user()->id, 'devicetype_id' => 3, 'branch_id' => $request->branch_id, 'branch_name' => $employeeId->branch_name, 'department_id' => $request->branch_id, 'remarks' => $request->remarks]);
            $insert = Issue::where('inventory_id', $request->inventory_id)->update(['employee_id' => $employeeId->emp_code, 'inventory_id' => $request->inventory_id, 'year_id' => $previousInventory->year_id, 'remarks' => 'Through previous equipment form']);
            $insert_issue = InventoryIssueRecord::where('inventory_id', $request->inventory_id)->update([
                'employee_id' => $request->employee_id,
                'employee_code' => $employeeId->emp_code,
                'inventory_id' => $request->inventory_id,
                'year_id' => $previousInventory->year_id,
                'received_status' => 0,
                'issued_at' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $log = SystemLogs::Add_logs('previousequipment', $request->all(), 'update');
            return redirect()->back()->with('msg', 'Previous Inventory Edit Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not edit, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = PreviousEquipment::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('previousequipment', $find, 'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Previous Equipment Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete Previous Equipment, Try Again!');
    }
}
