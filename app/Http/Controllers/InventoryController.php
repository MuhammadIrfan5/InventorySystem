<?php

namespace App\Http\Controllers;

use App\Budgetitem;
use App\Carryforwardstatus;
use App\Dispatchin;
use App\Dispatchout;
use App\Disposal;
use App\EmployeeBranch;
use App\InventoryIssueRecord;
use App\UserPrivilige;
use App\VendorTerm;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Inventory;
use App\Issue;
use App\Category;
use App\Subcategory;
use App\Location;
use App\Department;
use App\Branch;
use App\Store;
use App\Devicetype;
use App\Itemnature;
use App\Inventorytype;
use App\Modal;
use App\User;
use App\Makee;
use App\Vendor;
use App\Employee;
use App\Budgetitem as Budget;
use App\Type;
use App\Year;
use App\SystemLogs;
use function Matrix\inverse;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:inventory_index', ['only' => ['index']]);
        $this->middleware('permission:inventory_delete', ['only' => ['destroy']]);
        $this->middleware('permission:inventory_update', ['only' => ['show', 'update']]);


    }

    public function index()
    {
        return view('inventory1');
    }

    public function list(Request $request)
    {
        $response = [
            "draw" => $request->draw,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
        ];
        $products = new Inventory();
        $products = $products->orderBy('id', 'desc');

        /*Search function*/
        $search = $request->search["value"];
        if (!empty($search)) {
            $products = $products->where("id", "like", "%" . $search . "%");
            $products = $products->orWhere("product_sn", "like", "%" . $search . "%");
            $products = $products->orWhereHas("make", function ($query) use ($search) {
                $query->where('make_name', "like", "%" . $search . "%");
            });
            $products = $products->orWhereHas("model", function ($query) use ($search) {
                $query->where('model_name', "like", "%" . $search . "%");
            });
            $products = $products->orWhere("purchase_date", date('Y-m-d', strtotime($search)));
            $products = $products->orWhere("item_price", $search);
            $products = $products->orWhere("dollar_rate", $search);
            $products = $products->orWhereHas("category", function ($query) use ($search) {
                $query->where('category_name', "like", "%" . $search . "%");
            });
            $products = $products->orWhereHas("employee", function ($query) use ($search) {
                $query->where('name', "like", "%" . $search . "%");
            });
        }
        $response["recordsTotal"] = $products->count();
        $response["recordsFiltered"] = $products->count();
        /*ordering*/
//        $order = $request["order"][0]["column"];
//        $orderDir = $request["order"][0]["dir"];
//        switch ($order) {
//            case '0':
//                $products = $products->orderBy('id', $orderDir);
//                break;
//            case '1':
//                $products = $products->orderBy('title', $orderDir);
//                break;
//            case '2':
//                $products = $products->orderBy('image', $orderDir);
//                break;
//            case '3':
//                $products = $products->orderBy('status', $orderDir);
//                break;
//            case '4':
//                $products = $products->orderBy('created_at', $orderDir);
//                break;
//        }
        $products = $products->skip($request->start)->take($request->length)->get();
        foreach ($products as $key => $record) {
            if (UserPrivilige::get_single_privilige(auth()->id(), 'inventory_delete') == true && UserPrivilige::get_single_privilige(auth()->id(), 'inventory_update') == true) {
                $key++;
                $response['data'][] = [
                    "id" => $record->id,
                    "ProductS" => view('defaultComponent.linkDetail', ["inventoryId" => $record->id, "productSN" => $record->product_sn])->render(),
                    "Make" => $record->make->make_name??"",
                    "Model" => $record->model->model_name??"",
                    "PurchaseDate" => date('d-M-Y', strtotime($record->purchase_date)),
                    "Category" => $record->category->category_name??"",
                    "Price" => number_format(round($record->item_price), 2),
                    "DollarRate" => '$' . $record->dollar_rate,
                    "Issued" => $record->employee->name ?? 'No Name',
                    "CreatedAt" => date('d-M-Y', strtotime($record->created_at)),
                    "edit" => view('defaultComponent.deleteButton', ["delete" => $record->id])->render() . ' ' . view('defaultComponent.editButton', ["edit" => $record->id])->render(),
                ];
            } elseif (UserPrivilige::get_single_privilige(auth()->id(), 'inventory_update') == true) {
                $key++;
                $response['data'][] = [
                    "id" => $record->id,
                    "ProductS" => view('defaultComponent.linkDetail', ["inventoryId" => $record->id, "productSN" => $record->product_sn])->render(),
                    "Make" => $record->make->make_name??"",
                    "Model" => $record->model->model_name??"",
                    "PurchaseDate" => date('d-M-Y', strtotime($record->purchase_date)),
                    "Category" => $record->category->category_name??"",
                    "Price" => number_format(round($record->item_price), 2),
                    "DollarRate" => '$' . $record->dollar_rate,
                    "Issued" => $record->employee->name ?? 'No Name',
                    "CreatedAt" => date('d-M-Y', strtotime($record->created_at)),
                    "edit" => view('defaultComponent.editButton', ["edit" => $record->id])->render(),
                ];

            } else {
                $key++;
                $response['data'][] = [
                    "id" => $record->id,
                    "ProductS" => view('defaultComponent.linkDetail', ["inventoryId" => $record->id, "productSN" => $record->product_sn])->render(),
                    "Make" => $record->make->make_name,
                    "Model" => $record->model->model_name,
                    "PurchaseDate" => date('d-M-Y', strtotime($record->purchase_date)),
                    "Category" => $record->category->category_name,
                    "Price" => number_format(round($record->item_price), 2),
                    "DollarRate" => '$' . $record->dollar_rate,
                    "Issued" => $record->employee->name ?? 'No Name',
                    "CreatedAt" => date('d-M-Y', strtotime($record->created_at)),
                    "edit" => '',
                ];
            }
        }
        return response($response);
    }

    public function index1()
    {
//        ->where('devicetype_id','!=','1')
        $inventory = Inventory::whereNotIn('status', [0])->where('devicetype_id', '!=', '1')->orderBy('id', 'desc')->get();
//        $arr = array();
//        $inventory = DB::table('inventories')->whereNotIn('status', [0])->orderBy('id')->chunk(50, function ($invent) use (&$arr) {
//                foreach ($invent as $inv) {
//                    $inv->id = $inv->id;
//                    $inv->product_sn = $inv->product_sn;
//                    $inv->make = Makee::find($inv->make_id)['make_name'];
//                    $inv->model = Modal::find($inv->model_id)['model_name'];
//                    $inv->purchase_date = $inv->purchase_date;
//                    $inv->item_price = $inv->item_price;
//                    $inv->cat_id = $inv->category_id;
//                    $inv->subcategory_id = Subcategory::find($inv->subcategory_id)['sub_cat_name'];
//                    $inv->dollar_rate = $inv->dollar_rate;
//                    $inv->issued_to = $inv->issued_to;
//                    $inv->created_at = $inv->created_at;
//                    array_push($arr,$inv);
//                }
//                return $arr;
//            });
        return view('inventory', ['inventories' => $inventory]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'product_sn' => 'required|unique:inventories,product_sn',
            'item_price' => 'required',
            'dollar_rate' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        // $budget = Budget::where('subcategory_id', $request->subcategory_id)->first();
        // if($budget){
        //     if($budget->consumed >= $budget->qty){
        //         return redirect()->back()->with('msg', 'Selected item is out of stock in budget!');
        //     }
        //     else{
        //         $b_fields = array(
        //             'consumed' => $budget->consumed+1,
        //             'remaining' => $budget->remaining-1
        //         );
        //         $update = Budget::where('id',$budget->id)->update($b_fields);
        //      }
        // }
        // else{
        //     return redirect()->back()->with('msg', 'Selected item is out of stock in budget!');
        // }

        $fields = $request->all();
        $fields['item_price'] = str_replace(",", "", $fields['item_price']);
        $fields['dollar_rate'] = str_replace(",", "", $fields['dollar_rate']);
        $loggedin_user = Auth::id();
        $fields['added_by'] = $loggedin_user;
        $fields['carry_forward_status_id'] = 1;
        $create = Inventory::create($fields);
        if ($create) {
            $log = SystemLogs::Add_logs('inventories', Inventory::find($create->id), 'insert');
            return redirect()->back()->with('msg', 'Inventory Added Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not add inventory, Try Again!');
        }
    }

    public function add_with_grn_multiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'product_sn' => 'required|unique:inventories',
            'item_price' => 'required',
            'dollar_rate' => 'required',
//            'bulk_qty' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
        $fields['item_price'] = str_replace(",", "", $fields['item_price']);
        $fields['dollar_rate'] = str_replace(",", "", $fields['dollar_rate']);
        $loggedin_user = Auth::id();
        $fields['added_by'] = $loggedin_user;
        $fields['carry_forward_status_id'] = 1;
        $sn_nos = "";
        $product_sn = explode(',', $request->product_sn);
        if (end($product_sn) == "") {
            array_pop($product_sn);
        }
        for ($i = 0; $i < count($product_sn); $i++) {
            $fields['product_sn'] = $product_sn[$i];
            $create = Inventory::create($fields);
            $sn_nos .= $fields['product_sn'] . ",";
            $log = SystemLogs::Add_logs('inventories', Inventory::find($create->id), 'insert');
        }
        if ($create) {
            return redirect()->back()->with('msg', 'Inventory Added Successfully And Product Serial Numbers are ' . $sn_nos);
        } else {
            return redirect()->back()->with('msg', 'Could not add inventory, Try Again!');
        }
    }

    public function add_with_grn_bulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
//            'product_sn' => 'required|unique:inventories',
            'item_price' => 'required',
            'dollar_rate' => 'required',
            'bulk_qty' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
        $fields['item_price'] = str_replace(",", "", $fields['item_price']);
        $fields['dollar_rate'] = str_replace(",", "", $fields['dollar_rate']);
        $loggedin_user = Auth::id();
        $fields['added_by'] = $loggedin_user;
        $fields['carry_forward_status_id'] = 1;
        $sn_nos = "";
        for ($i = 1; $i <= $request->bulk_qty; $i++) {
            $fields['product_sn'] = 'SN-' . time() . $i;
            $create = Inventory::create($fields);
            $sn_nos .= $fields['product_sn'] . ",";
            $log = SystemLogs::Add_logs('inventories', Inventory::find($create->id), 'insert');
        }
        if ($create) {
            return redirect()->back()->with('msg', 'Inventory Added Successfully And Product Serial Numbers are ' . $sn_nos);
        } else {
            return redirect()->back()->with('msg', 'Could not add inventory, Try Again!');
        }
    }

    public function show($id)
    {
        $data = array();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        // $data['departments'] = Department::where('status',1)->get();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        // $data['branches'] = Branch::where('status',1)->get();
        $data['stores'] = Store::orderBy('store_name', 'asc')->get();
        $data['models'] = Modal::where('status', 1)->orderBy('model_name', 'asc')->get();
        $data['makes'] = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['vendors'] = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes'] = Devicetype::where('status', 1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures'] = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['types'] = Type::orderBy('type', 'asc')->get();
//        $data['carry_forward_status'] = Carryforwardstatus::all();
//        $data['years'] = Year::where('locked', null)->orderBy('year', 'asc')->get();
        $data['years'] = Year::all();
        $inventory = Inventory::find($id);
        $inventory->item_price = number_format(round($inventory->item_price));
        $inventory->dollar_rate = number_format($inventory->dollar_rate);
        $data['inventory'] = $inventory;
        return view('edit_inventory', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'product_sn' => 'required',
            'item_price' => 'required',
            'dollar_rate' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $inv = Inventory::find($id);
        $arr = array();
        $arr['category_id'] = $request->category_id;
        $arr['subcategory_id'] = $request->subcategory_id;
        $arr['location_id'] = $request->location_id;
//        $arr['department_id'] = $request->department_id;
//        $arr['branch_id'] = $request->branch_id;
        $arr['store_id'] = $request->store_id;
        $arr['product_sn'] = $request->product_sn;
        $arr['model_id'] = $request->model_id;
        $arr['make_id'] = $request->make_id;
        $arr['vendor_id'] = $request->vendor_id;
        //$arr['devicetype_id'] = $request->devicetype_id;
        $arr['inventorytype_id'] = $request->inventorytype_id;
        $arr['itemnature_id'] = $request->itemnature_id;
        $arr['purchase_date'] = $request->purchase_date;
        $arr['remarks'] = $request->remarks;
        $arr['item_price'] = str_replace(",", "", $request->item_price);
        $arr['dollar_rate'] = str_replace(",", "", $request->dollar_rate);
        $arr['delivery_challan'] = $request->delivery_challan;
        $arr['delivery_challan_date'] = $request->delivery_challan_date;
        $arr['invoice_number'] = $request->invoice_number;
        $arr['invoice_date'] = $request->invoice_date;
        $arr['other_accessories'] = $request->other_accessories;
        $arr['good_condition'] = $request->good_condition;
        $arr['verification'] = $request->verification;
        $arr['purpose'] = $request->purpose;
        $arr['po_number'] = $request->po_number;
        $arr['warrenty_period'] = $request->warrenty_period;
        $arr['insurance'] = $request->insurance;
        $arr['licence_key'] = $request->licence_key;
        $arr['sla'] = $request->sla;
        $arr['warrenty_check'] = $request->warrenty_check;
        $arr['operating_system'] = $request->operating_system;
        $arr['SAP_tag'] = $request->SAP_tag;
        $arr['capacity'] = $request->capacity;
        $arr['hard_drive'] = $request->hard_drive;
        $arr['processor'] = $request->processor;
        $arr['process_generation'] = $request->process_generation;
        $arr['display_type'] = $request->display_type;
        $arr['DVD_rom'] = $request->DVD_rom;
        $arr['RAM'] = $request->RAM;
        $arr['current_location'] = $request->current_location;
        $arr['current_consumer'] = $request->current_consumer;
        $arr['warranty_end'] = $request->warranty_end;
        $arr['tax'] = $request->tax;
        $arr['current_cost'] = $request->current_cost;
        $arr['year_id'] = $request->year_id;
        $arr['type_id'] = $request->type_id;
        $arr['department_id'] = $inv->department_id;
        $arr['branch_id'] = $inv->branch_id;
        $arr['branch_name'] = $inv->branch_name;
//        $arr['carry_forward_status_id'] = 2;

        $update = Inventory::where('id', $id)->update($arr);
        if ($update) {
            $log = SystemLogs::Add_logs('inventories', Inventory::find($id), 'update');
            return redirect()->back()->with('msg', 'Inventory Updated Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not update inventory, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Inventory::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::Add_logs('inventories', $find, 'delete');
        return $find->delete() ? redirect()->back()->with('msg', 'Inventory Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete inventory, Try Again!');

    }

    public function item_detail($id)
    {
        $inventory = Inventory::find($id);
        $user = Employee::where('emp_code', $inventory->issued_to)->first();
        if ($user) {
            $inventory->user = $user;
        }
        //return $data;
        return view('inventorydetail', ['inventory' => $inventory]);
    }

    public function single_item($id)
    {
        $inventory = Inventory::find($id);
        $issue = Issue::where('inventory_id', $id)->orderBy('id','DESC')->first();
        if ($issue) {
            $user = Employee::where('emp_code', $issue->employee_id)->first();
            if (!empty($user)) {
                $inventory['departmentName'] = $user->department;
                $inventory['userName'] = $user->name;
                $inventory['userId'] = $user->id;
            }
        }
        return $inventory??0;
    }

    public function check_product($pro)
    {
        $inventory = Inventory::where('product_sn', $pro)->first();
        if ($inventory) {
            return 1;
        } else {
            return 0;
        }
        return view('inventorydetail', ['inventory' => $inventory]);
    }

    public function get_price($id)
    {
        $inventory = Inventory::find($id);
        return number_format(round($inventory->item_price));
    }

    public function get_inv_items($id)
    {
        $inventories = Inventory::where('subcategory_id', $id)->get();
        return $inventories;
    }

    public function get_unassigned_items($id)
    {
//        ->whereIn('status', [1,2,4])
        $inventories = Inventory::where('subcategory_id', $id)->where('issued_to', NULL)->whereNotIn('devicetype_id', ['1'])->get();
        return $inventories;
    }

    public function get_unassigned_items_Capex($id)
    {
//        dd(date( 'Y' ) . '-01-01');
//        ->whereIn('status', [1,2,4])
        $yearList = array();
        $years = Year::select('id')->where('year_end_date', '<=', date('Y') . '-01-01')->orderBy('id', 'DESC')->get();
        foreach ($years as $item) {
            array_push($yearList, $item->id);
        }
        $inventories = Inventory::where('subcategory_id', $id)->where('issued_to', NULL)->where('type_id', 1)->whereIn('year_id', $yearList)->whereNotIn('devicetype_id', ['1'])->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime('2022-12-31 23:59:59')))->get();
        return $inventories;
    }

    public function get_assigned_items($id, $action)
    {
        $d_id = $action == 'in' ? 2 : 3;
        $inventories = Inventory::where('subcategory_id', $id)->whereNotNull('issued_to')->whereIn('devicetype_id', ['2', '3'])->get();
        return $inventories;
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
                $create = Inventory::create($fields, ['itemnature_id' => 21, 'product_sn' => $this->get_first_char($data) . $product_serial_number]);
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
                $create = Inventory::create($fields, ['itemnature_id' => 21, 'product_sn' => $this->get_first_char($data) . $product_serial_number]);
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
                $create = Inventory::create($fields, ['itemnature_id' => 21, 'product_sn' => $this->get_first_char($data) . $product_serial_number]);
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
                $create = Inventory::create($fields, ['itemnature_id' => 21, 'product_sn' => $this->get_first_char($data) . $product_serial_number]);
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
                $create = Inventory::create($fields, ['itemnature_id' => 21, 'product_sn' => $this->get_first_char($data) . $product_serial_number]);
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
                Inventory::where('id', $create->id)->update(['itemnature_id' => 21, 'product_sn' => $this->get_first_char($data) . $product_serial_number]);
                return redirect()->back()->with('msg', 'Invoice Recording Added Successfully!');
            } else {
                return redirect()->back()->with('error-msg', 'Could not add invoice recording, Try Again!');
            }
        } else {
            return redirect()->back()->with('error-msg', 'Vendor Terms does not exists, Try Again!');
        }
    }

    public function get_first_char($request)
    {
        $category = Category::find($request['category_id']);
        $sub_category = Subcategory::find($request['subcategory_id']);
        $vendor = Vendor::find($request['vendor_id']);
        $get_cat_name = substr($category->category_name, 0, 1);
        $get_sub_cat_name = substr($sub_category->sub_cat_name, 0, 1);
        $get_vendor_name = substr($vendor->vendor_name, 0, 1);

        return $get_cat_name . $get_sub_cat_name . $get_vendor_name;
    }

    public function email_view()
    {
        return view('emails.user_email');
    }

    public function inventory_detail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403);
        }
        $inventory = Inventory::find($request->inv_id);
        $user = Employee::where('emp_code', $inventory->issued_to)->first();
        if ($user) {
            $inventory->user = $user;
        }
        return view('inventory_detail', ['inventory' => $inventory]);
    }

    public function test()
    {
        $NoData = array();
        $NoData1 = array();
        $issueId1 = array();
        $issueId = array();
        $Dispatchin = array();
        $inventory = Inventory::where('devicetype_id', 3)->whereNotNull('issued_to')->get();
        foreach ($inventory as $item) {
            $emp1 = Issue::where('inventory_id', $item->id)->first()['inventory_id'];
            if (!empty($emp1)) {
                array_push($issueId1, $emp1);
            } else {
                $dispose=Disposal::where('inventory_id', $item->id)->first()['inventory_id'];
                if(!empty($dispose)){
                    array_push($Dispatchin,$dispose);
                }
//            $emp = Employee::where('emp_code', $item->issued_to)->first();
//            $insert = Issue::create(['employee_id' => $emp->emp_code, 'inventory_id' => $item->id, 'year_id' => $item->year_id, 'remarks' => "Previous Equipment"]);
//            $insert_issue = InventoryIssueRecord::create([
//                'employee_id' => $emp->id,
//                'employee_code' => $emp->emp_code,
//                'inventory_id' => $item->id,
//                'year_id' => $item->year_id,
//                'received_status' => 0,
//                'issued_at' => date('Y-m-d'),
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ]);
//            $item->devicetype_id = 3;
//            $item->update();
//            array_push($issueId, $item->id);
        }
        }
        dd(
            'Disposed', $Dispatchin,
            'Not Issued TO', $issueId
        );
    }
}
