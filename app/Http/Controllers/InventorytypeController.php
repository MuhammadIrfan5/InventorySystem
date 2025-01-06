<?php

namespace App\Http\Controllers;

use App\Budgetitem;
use App\Category;
use App\Inventory;
use App\Subcategory;
use App\Vendor;
use App\VendorTerm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Inventorytype;
use App\SystemLogs;
class InventorytypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:inventorytype', ['only' => ['index']]);
        $this->middleware('permission:add_inventorytype', ['only' => ['store']]);
        $this->middleware('permission:edit_inventorytype', ['only' => ['show','update']]);
        $this->middleware('permission:delete_inventorytype', ['only' => ['destroy']]);
    }
    public function index()
    {
        $inventorytype = Inventorytype::all();
        return view('inventorytype', ['inventorytypes' => $inventorytype]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventorytype_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = array('inventorytype_name'=>$request->inventorytype_name, 'status'=>1);
        $create = Inventorytype::create($fields);
        if($create){
            $log = SystemLogs::Add_logs('inventorytypes',$request->all(),'insert');
            return redirect()->back()->with('msg', 'Inventorytype Added Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not add inventorytype, Try Again!');
        }
    }

    public function show($id)
    {
        $inventorytype = Inventorytype::find($id);
        return view('edit_inventorytype', ['inventorytype'=> $inventorytype]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'inventorytype_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $update = Inventorytype::where('id', $id)->update(['inventorytype_name'=>$request->inventorytype_name, 'status'=>$request->status]);
        if($update){
            $log = SystemLogs::Add_logs('inventorytypes',Inventorytype::find($id),'update');
            return redirect()->back()->with('msg', 'Inventorytype Updated Successfully!');
        }
        else{
            return redirect()->back()->with('msg', 'Could not update inventorytype, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Inventorytype::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('inventorytypes',$find,'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Inventorytype Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete inventorytype, Try Again!');
    }
    public function check_vendor_term(Request $request)
    {
        $find = VendorTerm::where('vendor_id', $request->vendor_id)
            ->where('category_id', $request->category_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->where('type_id', $request->type_id)
            ->where('year_id', $request->year_id)
            ->first();
        if ($find != null) {
            return response()->json(['code' => 200, 'success' => 'Term Found']);
        } else {
            return response()->json(['code' => 404, 'failed' => 'Term Not Found']);
        }
    }

    public function get_first_char($request){
        $category = Category::find($request['category_id']);
        $sub_category = Subcategory::find($request['subcategory_id']);
        $vendor = Vendor::find($request['vendor_id']);
        $get_cat_name = substr($category->category_name,0,1);
        $get_sub_cat_name = substr($sub_category->sub_cat_name,0,1);
        $get_vendor_name = substr($vendor->vendor_name,0,1);

        return $get_cat_name.$get_sub_cat_name.$get_vendor_name;
    }

    public function add_invoice_recording(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
//            'product_sn' => 'required|unique:inventories',
            'item_price' => 'required',
            'dollar_rate' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
        $fields['item_price'] = str_replace(",", "", $fields['item_price']);
        $fields['dollar_rate'] = str_replace(",", "", $fields['dollar_rate']);
        $loggedin_user = Auth::id();
        $product_serial_number = date('dmYhhms');
        $data = [
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'vendor_id' => $request->vendor_id,
        ];
        $fields['added_by'] = $loggedin_user;
        $find = VendorTerm::where('vendor_id', $request->vendor_id)
            ->where('category_id', $request->category_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->where('type_id', $request->type_id)
            ->where('year_id', $request->year_id)
            ->first();

        if ($find != null) {
            if ($find->vendor_term_id == 1 && $find->invoice_count < $find->invoice_max_count) {
                $find->invoice_count++;
                $find->update();
                $create = Inventory::create($fields, ['itemnature_id' => 21 , 'product_sn' => $this->get_first_char($data).$product_serial_number]);
                if ($find->invoice_count == $create->invoice_max_count) {
                    $find_budget_item = Budgetitem::where('category_id', $request->category_id)
                        ->where('subcategory_id', $request->subcategory_id)
                        ->where('type_id', $request->type_id)
                        ->where('year_id', $request->year_id)->first();
                    if ($find_budget_item != null) {
                        $qty = $find_budget_item->qty;
                        $find_budget_item->consumed = $qty;
                        $find_budget_item->remaining = 0;
                        $find_budget_item->update();
                    }
                }
            } else if ($find->vendor_term_id == 2 && $find->invoice_count < $find->invoice_max_count) {
                $find->invoice_count++;
                $find->update();
                $create = Inventory::create($fields, ['itemnature_id' => 21 , 'product_sn' => $this->get_first_char($data).$product_serial_number]);
                if ($find->invoice_count == $create->invoice_max_count) {
                    $find_budget_item = Budgetitem::where('category_id', $request->category_id)
                        ->where('subcategory_id', $request->subcategory_id)
                        ->where('type_id', $request->type_id)
                        ->where('year_id', $request->year_id)->first();
                    if ($find_budget_item != null) {
                        $qty = $find_budget_item->qty;
                        $find_budget_item->consumed = $qty;
                        $find_budget_item->remaining = 0;
                        $find_budget_item->update();
                    }
                }
            } else if ($find->vendor_term_id == 3 && $find->invoice_count < $find->invoice_max_count) {
                $find->invoice_count++;
                $find->update();
                $create = Inventory::create($fields, ['itemnature_id' => 21 , 'product_sn' => $this->get_first_char($data).$product_serial_number]);
                if ($find->invoice_count == $create->invoice_max_count) {
                    $find_budget_item = Budgetitem::where('category_id', $request->category_id)
                        ->where('subcategory_id', $request->subcategory_id)
                        ->where('type_id', $request->type_id)
                        ->where('year_id', $request->year_id)->first();
                    if ($find_budget_item != null) {
                        $qty = $find_budget_item->qty;
                        $find_budget_item->consumed = $qty;
                        $find_budget_item->remaining = 0;
                        $find_budget_item->update();
                    }
                }
            } else if ($find->vendor_term_id == 4 && $find->invoice_count < $find->invoice_max_count) {
                $find->invoice_count++;
                $find->update();
                $create = Inventory::create($fields, ['itemnature_id' => 21 , 'product_sn' => $this->get_first_char($data).$product_serial_number]);
                if ($find->invoice_count == $create->invoice_max_count) {
                    $find_budget_item = Budgetitem::where('category_id', $request->category_id)
                        ->where('subcategory_id', $request->subcategory_id)
                        ->where('type_id', $request->type_id)
                        ->where('year_id', $request->year_id)->first();
                    if ($find_budget_item != null) {
                        $qty = $find_budget_item->qty;
                        $find_budget_item->consumed = $qty;
                        $find_budget_item->remaining = 0;
                        $find_budget_item->update();
                    }
                }
            } else if ($find->vendor_term_id == 5 && $find->invoice_count < $find->invoice_max_count) {
                $find->invoice_count++;
                $find->update();
                $create = Inventory::create($fields, ['itemnature_id' => 21 , 'product_sn' => $this->get_first_char($data).$product_serial_number]);
                if ($find->invoice_count == $create->invoice_max_count) {
                    $find_budget_item = Budgetitem::where('category_id', $request->category_id)
                        ->where('subcategory_id', $request->subcategory_id)
                        ->where('type_id', $request->type_id)
                        ->where('year_id', $request->year_id)->first();
                    if ($find_budget_item != null) {
                        $qty = $find_budget_item->qty;
                        $find_budget_item->consumed = $qty;
                        $find_budget_item->remaining = 0;
                        $find_budget_item->update();
                    }
                }
            } else {
                return redirect()->back()->with('error-msg', 'Invoice Recording Limit Reached!');
            }
            if ($create) {
                Inventory::where('id', $create->id)->update(['itemnature_id' => 21 , 'product_sn' => $this->get_first_char($data).$product_serial_number]);
                return redirect()->back()->with('msg', 'Invoice Recording Added Successfully!');
            } else {
                return redirect()->back()->with('error-msg', 'Could not add invoice recording, Try Again!');
            }
        } else {
            return redirect()->back()->with('error-msg', 'Vendor Terms does not exists, Try Again!');
        }
    }

    public function email_view(){
        return view('emails.user_email');
    }
}
