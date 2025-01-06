<?php

namespace App\Http\Controllers;

use App\Rturn;
use App\Type;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Inventory;
use App\InventoryInvoice;
use App\SystemLogs;
use App\Invoicerelation;
use App\InventoryIssueRecord;
use App\Category;
use App\Subcategory;
use App\Location;
use App\Department;
use App\Branch;
use App\Store;
use App\SLA;
use App\SLAComplainLog;
use App\Devicetype;
use App\Itemnature;
use App\Inventorytype;
use App\Modal;
use App\User;
use App\Makee;
use App\Vendor;
use App\Employee;
use App\Budgetitem as Budget;
use App\Issue;
use App\Repairing;
use App\Disposal;
use App\Dispatchin;
use App\Dispatchout;
use App\Year;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use DataTables;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
    }

    public function show_inventory(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data               = array();
        $inventory          = array();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['locations']  = Location::orderBy('location', 'asc')->get();
        $data['years']      = Year::orderBy('year', 'asc')->get();
        $data['types']      = Type::orderBy('type', 'asc')->get();
        $data['filters']    = array();
//        dd($request->all());
        if (empty($request->all())) {
            $data['inventories'] = array();
        } else {
            $fields = array_filter($request->all());
            $key    = null;
            $op     = null;
            $val    = null;
            unset($fields['_token']);
            if (isset($fields['inout'])) {
                if ($fields['inout'] == 'in') {
                    $fields['issued_to'] = null;
                } else {
                    $key = 'issued_to';
                    $op  = '>';
                    $val = 0;
                }
            }

            $fields['inout'] = array($key, $op, $val);
            $data['filters'] = $fields;
            unset($fields['inout']);
            $fields['from_date']=!empty($request['from_date'])?'2023-04-01':"";
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $inventory = Inventory::where([[$fields]])->where($key, $op, $val)
                    ->where('devicetype_id', '!=', 5)
                    ->whereBetween('purchase_date', [$from, date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get()
                ;

            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $inventory =$inventory;
//                $inventory = Inventory::where([[$fields]])->where($key, $op, $val)
//                    ->where('devicetype_id', '!=', 5)
//                    ->whereBetween('purchase_date', [$from, date('Y-m-d', strtotime('+1 day'))])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $inventory = Inventory::where([[$fields]])->where($key, $op, $val)
                    ->where('devicetype_id', '!=', 5)
                    ->whereBetween('purchase_date', ['', date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();

            } else {

                $from      = date('2023-04-01 00:00:00');
                $to        = Carbon::now();
                $inventory =$inventory;
//                    Inventory::where([[$fields]])->whereBetween('created_at', [$from, $to])->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get()
                ;
            }
        }
        foreach ($inventory as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
        }
        $data['inventories'] = $inventory;
        return view('show_inventorylist', $data);
    }

    public function show_invoice_inventory_list(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data = array();
//        $fields = array();
        $inventory             = array();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['years']         = Year::all();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['filters']       = array();
//        dd($request->all());
        if (empty($request->all())) {
            $data['inventories'] = array();
        } else {
            $fields = array_filter($request->all());
            $key    = null;
            $op     = null;
            $val    = null;
            unset($fields['_token']);
            if (isset($fields['inout'])) {
                if ($fields['inout'] == 'in') {
                    $fields['issued_to'] = null;
                } else {
                    $key = 'issued_to';
                    $op  = '>';
                    $val = 0;
                }
            }

            $fields['inout'] = array($key, $op, $val);
            $data['filters'] = $fields;
            unset($fields['inout']);
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = $fields['to_date'];
                unset($fields['from_date']);
                unset($fields['to_date']);
//                $inventory = Invoicerelation::where([[$fields]])->where($key, $op, $val)
//                    ->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
                $inventory = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
                    'invoice_number',
                    'invoice_date',
//                    DB::raw('group_concat(invoice_date) as invoice_date'),
                    DB::raw('year_id as year_id'),
                    DB::raw('vendor_id as vendor_id'),
                    DB::raw('group_concat(subcategory_id) as subcategory_id'),
                    DB::raw('SUM(item_price) as item_price'),
                    DB::raw('SUM(tax) as tax'),
                    DB::raw('SUM(item_price_tax) as item_price_tax'),
                    DB::raw('group_concat(contract_issue_date) as contract_issue_date'),
                    DB::raw('group_concat(contract_end_date) as contract_end_date'))
                    ->where([[$fields]])
                    ->where($key, $op, $val)
                    ->whereBetween('invoice_date', [$from, $to])
                    ->groupBy('invoice_number')
                    ->groupBy('invoice_date')
//                    ->orderBy('id')
                    ->get();
            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
//                $inventory = Invoicerelation::where([[$fields]])->where($key, $op, $val)
//                    ->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
                $inventory = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
                    'invoice_number',
                    'invoice_date',
//                    DB::raw('group_concat(invoice_date) as invoice_date'),
                    DB::raw('year_id as year_id'),
                    DB::raw('vendor_id as vendor_id'),
                    DB::raw('group_concat(subcategory_id) as subcategory_id'),
                    DB::raw('SUM(item_price) as item_price'),
                    DB::raw('SUM(tax) as tax'),
                    DB::raw('SUM(item_price_tax) as item_price_tax'),
                    DB::raw('group_concat(contract_issue_date) as contract_issue_date'),
                    DB::raw('group_concat(contract_end_date) as contract_end_date'))
                    ->where([[$fields]])
                    ->where($key, $op, $val)
                    ->whereBetween('invoice_date', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->groupBy('invoice_number')
                    ->groupBy('invoice_date')
//                    ->orderBy('id')
                    ->get();
            } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
//                $inventory = Invoicerelation::where([[$fields]])->where($key, $op, $val)
//                    ->whereBetween('updated_at', ['', date('Y-m-d', $to)])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
                $inventory = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
                    'invoice_number',
                    'invoice_date',
//                    DB::raw('group_concat(invoice_date) as invoice_date'),
                    DB::raw('year_id as year_id'),
                    DB::raw('vendor_id as vendor_id'),
                    DB::raw('group_concat(subcategory_id) as subcategory_id'),
                    DB::raw('SUM(item_price) as item_price'),
                    DB::raw('SUM(tax) as tax'),
                    DB::raw('SUM(item_price_tax) as item_price_tax'),
                    DB::raw('group_concat(contract_issue_date) as contract_issue_date'),
                    DB::raw('group_concat(contract_end_date) as contract_end_date'))
                    ->where([[$fields]])
                    ->where($key, $op, $val)
                    ->whereBetween('invoice_date', ['', date('Y-m-d', $to)])
                    ->groupBy('invoice_number')
                    ->groupBy('invoice_date')
//                    ->orderBy('id')
                    ->get();
            } else {
                $inventory = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
                    'invoice_number',
                    'invoice_date',
//                    DB::raw('group_concat(invoice_date) as invoice_date'),
                    DB::raw('year_id as year_id'),
                    DB::raw('vendor_id as vendor_id'),
                    DB::raw('group_concat(subcategory_id) as subcategory_id'),
                    DB::raw('SUM(item_price) as item_price'),
                    DB::raw('SUM(tax) as tax'),
                    DB::raw('SUM(item_price_tax) as item_price_tax'),
                    DB::raw('group_concat(contract_issue_date) as contract_issue_date'),
                    DB::raw('group_concat(contract_end_date) as contract_end_date'))
                    ->where([[$fields]])
                    ->groupBy('invoice_number')
                    ->groupBy('invoice_date')
//                    ->orderBy('id')
                    ->get();
//                $inventory = Invoicerelation::where([[$fields]])->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }
        $cat_name = array();
        foreach ($inventory as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
            $inv->cat_name     = explode(',', $inv->category_id);
            $inv->sub_cat_name = explode(',', $inv->subcategory_id);
        }
        $data['inventories'] = $inventory;
        $data['year_id']     = $request->year_id;
        $data['vendor_id']   = $request->vendor_id;
//        dd($data);
        return view('show_invoice_inventory_list', $data);
    }

    public function balance_report(Request $request)
    {
        $data              = array();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        $data['stores']    = Store::orderBy('store_name', 'asc')->get();
        $fields            = array_filter($request->all());
        unset($fields['_token']);
        $data['filters'] = $fields;
        if (empty($request->all())) {
            $subcategories = array();
        } else {
            $subcategories = Subcategory::where('status', 1)->get();
            foreach ($subcategories as $subcat) {
                $subcat->rem = Inventory::where([[$fields]])->where('subcategory_id', $subcat->id)->where('issued_to', NULL)->where('devicetype_id', '!=', 5)->count();
                $subcat->out = Inventory::where([[$fields]])->where('subcategory_id', $subcat->id)->whereNotNull('issued_to')->where('devicetype_id', '!=', 5)->count();
            }
        }
        $data['subcategories'] = $subcategories;
        return view('balancereport', $data);
    }

    public function edit_logs(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data               = array();
        $data['productsns'] = Inventory::whereNotIn('status', [0])->get();
        $data['filters']    = array();
        if (empty($request->all())) {
            $data['inventories'] = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $data['inventories'] = Inventory::where([[$fields]])->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $data['inventories'] = Inventory::where([[$fields]])->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $data['inventories'] = Inventory::where([[$fields]])->whereBetween('updated_at', ['', date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else {
                $data['inventories'] = Inventory::where([[$fields]])->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }
        return view('show_editlogs', $data);
    }

    public function inventory_in(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['locations']     = Location::orderBy('location', 'asc')->get();
        $data['invtypes']      = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['makes']         = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['stores']        = Store::orderBy('store_name', 'asc')->get();
        $data['itemnatures']   = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['years']         = Year::orderBy('year', 'asc')->get();
        $data['filters']       = array();
        $invs                  = array();
        if (empty($request->all())) {
            $data['inventories'] = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $fields['issued_to'] = null;
            $data['filters']     = $fields;
            $fields['from_date'] = $request->from_date == "" ? "" : $fields['from_date'];

            if ($fields['from_date'] <= '2023-04-01') {
                $fields['from_date'] = '2023-04-01';
            }
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->whereNull('issued_to')->where('product_sn', 'not like', "%-CR-%")->whereBetween('created_at', [$from, date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            }
            else if (isset($fields['from_date']) && !isset($request['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->where('carry_forward_status_id', '!=', 3)->whereNull('issued_to')->whereBetween('created_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->whereNull('issued_to')->where('product_sn', 'not like', "%-CR-%")->where('carry_forward_status_id', '!=', 3)->whereBetween('created_at', ['', date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else {
                if (isset($fields['purchase_date_from']) && isset($fields['purchase_date_to'])) {
                    $invs = Inventory::whereBetween('purchase_date', [$fields['purchase_date_from'], $fields['purchase_date_to']])->whereNull('issued_to')->whereNotIn('devicetype_id', ['1', '5'])->whereNull('issued_to')->where('product_sn', 'not like', "%-CR-%")->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
                } else {
                    unset($fields['purchase_date_from']);
                    unset($fields['purchase_date_to']);
                    unset($fields['from_date']);
                    unset($fields['to_date']);
                    $invs =$invs;
//                        Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->whereNull('issued_to')->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
                }
            }
            foreach ($invs as $inv) {
                $inv->added_by       = User::find($inv->added_by);
                $inv->return_remarks = Rturn::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first();
            }


            $data['inventories'] = $invs;
        }
        return view('show_inventoryin', $data);
    }

    public function inventoryOutIndex()
    {
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['locations']     = Location::orderBy('location', 'asc')->get();
        $data['invtypes']      = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['makes']         = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['stores']        = Store::orderBy('store_name', 'asc')->get();
        $data['employees']     = Employee::orderBy('name', 'asc')->get();
        $data['itemnatures']   = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['years']         = Year::orderBy('year', 'asc')->get();
        $departments           = DB::table('employees')->select('dept_id', 'department')->orderBy('department', 'asc')->distinct()->get();
        foreach ($departments as $d) {
            $depts[$d->dept_id] = $d->department;
        }
        $data['departments'] = $depts;
        $data['filters']     = 0;
        return view('show_inventoryout1', $data);
    }

    public function inventoryOutList(Request $request)
    {
        $response = [
            "draw"            => $request->draw,
            "recordsTotal"    => 0,
            "recordsFiltered" => 0,
            "data"            => [],
        ];
        /*Search*/
        if ($request->draw == 1) {
            $response['data'] = [];
        } else {
            $fields  = array_filter($request->all());
            $key     = null;
            $op      = null;
            $val     = null;
            $dept_id = $fields['department_id'] ?? null;
            unset($fields['_token']);
            unset($fields['draw']);
            unset($fields['columns']);
            unset($fields['order']);
            unset($fields['length']);
            unset($fields['search']);
            unset($fields['_']);
            unset($fields['start']);
            if (!isset($fields['issued_to'])) {
                $key = 'issued_to';
                $op  = '>';
                $val = 0;
            }
            $fields['inout'] = array($key, $op, $val);
            unset($fields['inout']);
            if (isset($fields['from_issuance']) || isset($fields['to_issuance'])) {
                if (isset($fields['from_issuance']) && isset($fields['to_issuance'])) {
                    $from = $fields['from_issuance'];
                    $to   = strtotime($fields['to_issuance'] . '+1 day');
                    unset($fields['from_issuance']);
                    unset($fields['to_issuance']);
                    $issue = Issue::whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                        ->select('inventory_id')
                        ->orderBy('id', 'desc')->get();
                } else if (isset($fields['from_issuance']) && !isset($fields['to_issuance'])) {
                    $from = $fields['from_issuance'];
                    unset($fields['from_issuance']);
                    $issue = Issue::whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                        ->select('inventory_id')
                        ->orderBy('id', 'desc')->get();
                } else if (!isset($fields['from_issuance']) && isset($fields['to_issuance'])) {
                    $to = strtotime($fields['to_issuance'] . '+1 day');
                    unset($fields['to_issuance']);
                    $issue = Issue::whereBetween('updated_at', ['', date('Y-m-d', $to)])
                        ->select('inventory_id')
                        ->orderBy('id', 'desc')->get();
                }
                $ids = array();
                foreach ($issue as $iss) {
                    $ids[] = $iss->inventory_id;
                }
//                where($key, $op, $val)
                $invs = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where('carry_forward_status_id', '!=', 3)->where('issued_to', '!=', null)->whereIn('id', $ids)->orderBy('id', 'desc');
            } else {
                if (isset($fields['purchase_date_from']) || isset($fields['purchase_date_to'])) {
                    $invs = Inventory::whereBetween('purchase_date', [$fields['purchase_date_from'], $fields['purchase_date_to']])->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->orderBy('id', 'desc');
                } else {
                    $invs = Inventory::where([[$fields]])->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->orderBy('id', 'desc');
                }
            }
            /*End Search*/
            $response["recordsTotal"]    = $invs->count();
            $response["recordsFiltered"] = $invs->count();
            $invs                        = $invs->skip($request->start)->take($request->length)->get();
            foreach ($invs as $key => $record) {
                $key++;
                $response['data'][] = [
                    "id"                   => $record->id,
                    "itemCategory"         => $record->subcategory->sub_cat_name ?? '',
                    "ProductS"             => view('defaultComponent.linkDetail', ["inventoryId" => $record->id, "productSN" => $record->product_sn])->render(),
                    "Make"                 => $record->make->make_name,
                    "Model"                => $record->model->model_name,
                    "issued_to"            => $record->employee->name ?? '',
                    "location"             => $record->employee->department ?? '',
                    "issued_by"            => $record->user->name ?? '',
                    "issue_date"           => $record->issue != null ? date('d-M-Y', strtotime($record->issue->created_at)) : "",
                    "inventorytype"        => $record->inventorytype->inventorytype_name ?? '',
                    "devicetype"           => $record->devicetype->devicetype_name,
                    "current_consumer"     => $record->current_consumer,
                    "inventoryIssueRecord" => !empty($record->inventoryIssueRecord) ? view('defaultComponent.statusButton', ["status" => $record->inventoryIssueRecord->received_status])->render() : "",
                    "receive_remarks"      => $record->inventoryIssueRecord->receive_remarks ?? "",
                    "received_at"          => $record->inventoryIssueRecord->received_at ?? '',
                    "remarks"              => $record->remarks,
                    "issue_remarks"        => $record->issue != null ? $record->issue->remarks : "",
                    "email"                => view('defaultComponent.email', ["employeeId" => $record->employee->id, "inventoryId" => $record->id, "receivedStatus" => $record->inventoryIssueRecord != null ? $record->inventoryIssueRecord->received_status : 1])->render(),
                ];
            }
        }
        return response($response);
    }

    public function inventory_out(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['locations']     = Location::orderBy('location', 'asc')->get();
        $data['invtypes']      = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['makes']         = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['stores']        = Store::orderBy('store_name', 'asc')->get();
        $data['employees']     = Employee::orderBy('name', 'asc')->get();
        $data['itemnatures']   = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['years']         = Year::orderBy('year', 'asc')->get();
        $data['filters']       = array();
        $depts                 = array();
        $departments           = DB::table('employees')->select('dept_id', 'department')->orderBy('department', 'asc')->distinct()->get();

        foreach ($departments as $d) {
            $depts[$d->dept_id] = $d->department;
        }
        $data['departments'] = $depts;
        $invs                = array();
        if (empty($request->all())) {
            $data['inventories'] = array();
        } else {
//            dd($request->all());
            $fields  = array_filter($request->all());
            $key     = null;
            $op      = null;
            $val     = null;
            $dept_id = $fields['department_id'] ?? null;
//            unset($fields['department_id']);
            unset($fields['_token']);
            if (!isset($fields['issued_to'])) {
                $key = 'issued_to';
                $op  = '>';
                $val = 0;
            }

            $fields['inout'] = array($key, $op, $val);
            $data['filters'] = $fields;
            unset($fields['inout']);
            if (isset($fields['from_issuance']) || isset($fields['to_issuance'])) {
                if ($fields['from_issuance'] <= '2023-04-01') {
                    $fields['from_issuance'] = '2023-04-01';
                }
                if (isset($fields['from_issuance']) && isset($fields['to_issuance'])) {

                    $from = $fields['from_issuance'];
                    $to   = strtotime($fields['to_issuance'] . '+1 day');
                    unset($fields['from_issuance']);
                    unset($fields['to_issuance']);
                    $issue = Issue::whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                        ->select('inventory_id')
                        ->orderBy('id', 'desc')->get();
                } else if (isset($fields['from_issuance']) && !isset($fields['to_issuance'])) {
                    $from = $fields['from_issuance'];
                    unset($fields['from_issuance']);
                    $issue = Issue::whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                        ->select('inventory_id')
                        ->orderBy('id', 'desc')->get();
                } else if (!isset($fields['from_issuance']) && isset($fields['to_issuance'])) {
                    $to = strtotime($fields['to_issuance'] . '+1 day');
                    unset($fields['to_issuance']);
                    $issue = Issue::whereBetween('updated_at', ['', date('Y-m-d', $to)])
                        ->select('inventory_id')
                        ->orderBy('id', 'desc')->get();
                }
                $ids = array();
                foreach ($issue as $iss) {
                    $ids[] = $iss->inventory_id;
                }
//                where($key, $op, $val)
                $invs = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where('carry_forward_status_id', '!=', 3)->where('issued_to', '!=', null)->whereIn('id', $ids)->orderBy('id', 'desc')->get();
            } else {
                if (isset($fields['purchase_date_from']) || isset($fields['purchase_date_to'])) {
                    unset($fields['purchase_date_from']);
                    unset($fields['purchase_date_to']);
                    $invs = Inventory::where([[$fields]])->whereBetween('purchase_date', [$request['purchase_date_from'], $request['purchase_date_to']])->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->orderBy('id', 'desc')->get();
                } else {
                    $invs = Inventory::where([[$fields]])->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->orderBy('id', 'desc')->get();
                }
            }
            $items           = array();
            $inv_user        = array();
            $inventory_users = array();
            foreach ($invs as $inv) {
//                $inv->user = Employee::where('emp_code', $inv->issued_to)->first();
                $inv->users                = DB::table('employees')
                    ->join('employee_branch', 'employees.emp_code', '=', 'employee_branch.emp_code')
                    ->where('employee_branch.emp_code', $inv->issued_to)
                    ->select('employees.*', 'employee_branch.*')
                    ->get();
                $inv->employee_status_data = InventoryIssueRecord::where('employee_code', $inv->issued_to)->where('inventory_id', $inv->id)->latest('created_at')->first();
                $inv->issued_by            = User::find($inv->issued_by);
                $inv->issued_to            = Employee::where('emp_code', $inv->issued_to)->first();
                $inv->issue_date           = Issue::where('inventory_id', $inv->id)->select('created_at')->orderBy('id', 'desc')->first();
                $inv->issue_remarks        = Issue::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first();
//                ->where('employee_id',$inv->issued_to)
                foreach ($inv->users as $employee) {
                    $inv_user[] = $employee->branch_id;
                }
            }
            foreach ($inv_user as $emp) {
                if ($dept_id == $emp) {
                    $items[] = $invs;
                }
            }
            if ($dept_id) {
                if (!empty($items)) {
                    $invs = (object)$items[0];
                }
            }
            if (!isset($dept_id)) {
                $items[] = $invs;
            }
            $data['inventories'] = $invs;
        }
        return view('show_inventoryout', $data);
    }

    public function edit_inventory_out(Request $request)
    {
        $data['inventory_out'] = InventoryIssueRecord::where('inventory_id', $request->id)->orderBy('id', 'DESC')->first();
        return view('edit_inventory_out1', $data);
    }

    public function update_inventory_out(Request $request)
    {
        $inventoryOut = InventoryIssueRecord::find($request->id);
        if (!empty($inventoryOut) && $inventoryOut->received_status != 1 && $inventoryOut->received_status != 0) {
            $inventoryOut->received_status = 0;
            $inventoryOut->update();
            return redirect()->back()->with('msg', 'Inventory Out Updated Successfully!');
        } else {
            return redirect()->back()->with('msg', 'You can not update status!');
        }
    }

    public function reverification_email($inventory_id)
    {
        $inventory = Inventory::find($inventory_id);
        $user      = Employee::where('emp_code', $inventory->issued_to)->first();
        if (isset($user)) {
            $issue = InventoryIssueRecord::create(
                [
                    'employee_id'     => $user->id,
                    'employee_code'   => $user->emp_code,
                    'inventory_id'    => $inventory_id,
                    'year_id'         => $inventory->year_id,
                    'received_status' => 0
                ]
            );
            $data  = array(
                'user'           => $user->name,
                'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                'make'           => Makee::find($inventory->make_id)['make_name'],
                'product_sn'     => $inventory->product_sn,
                'subcategory'    => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
                'message'        => "Reverification Email",
                'url'            => Url::signedRoute('reverify-email', ['emp_id' => $user->id, 'inventory_id' => $inventory_id, 'issue_PK' => $issue->id, 'status' => 'reverify_yes']),
                'url_reject'     => Url::signedRoute('reverify-email', ['emp_id' => $user->id, 'inventory_id' => $inventory_id, 'issue_PK' => $issue->id, 'status' => 'reverify_no']),
            );
            Mail::send('emails.reverify_inventory', ['data' => $data], function ($message) use ($user) {
                $message->to($user->email)->subject
                ('Inventory Re-Verification');
                $message->from('itstore@efulife.com', 'Support IT Store');
            });
//            muhammadirfan5891@gmail.com jibranmasood@efulife.com
            $log = SystemLogs::Add_logs('email', $data, 'email');
            return redirect()->back()->with('success-msg', 'Email successfully sent !');
        } else {
            return redirect()->back()->with('error-msg', 'Something went wrong, Try Again !');
        }
//        return view('emails.reverify_inventory', ['data' => $data]);
    }

    public function bin_card(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data               = array();
        $data['productsns'] = Inventory::whereNotIn('status', [0])->get();
        $data['filters']    = array();
        if (empty($request->all())) {
            $inventories = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereBetween('updated_at', ['', date('Y-m-d', $to)])
                    ->whereNotIn('status', [0])
                    ->orderBy('id', 'desc')->get();
            } else {
                $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }
        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                $inventory->repairing = Repairing::where('item_id', $inventory->id)->first();
                $inventory->added_by  = User::where('id', $inventory->added_by)->first();
            }
        }
        $data['inventories'] = $inventories;
        return view('show_bincard', $data);
    }

    public function asset_repairing(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['filters']       = array();
        if (empty($request->all())) {
            $repairs = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            $repairs         = Repairing::where([[$fields]])->orderBy('item_id', 'desc')->get();
        }

        foreach ($repairs as $repair) {
            $repair->item->user = Employee::where('emp_code', $repair->item->issued_to)->first();
        }
        $data['repairs'] = $repairs;
        return view('show_repairings', $data);
    }

    public function disposal(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data            = array();
        $data['filters'] = array();
        if (empty($request->all())) {
            $inventories = array();
        } else {
            $fields = array_filter($request->all());

            unset($fields['_token']);
            $data['filters'] = $fields;
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                if (isset($fields['handover'])) {
                    if ($fields['handover'] == 1) {
                        $inventories = Disposal::whereBetween('dispose_date', [$from, date('Y-m-d', $to)])
                            ->whereNotNull('handover_date')
                            ->orderBy('id', 'desc')->get();
                    } else {
                        $inventories = Disposal::whereBetween('dispose_date', [$from, date('Y-m-d', $to)])
                            ->whereNull('handover_date')
                            ->orderBy('id', 'desc')->get();
                    }
                } else {
                    $inventories = Disposal::whereBetween('dispose_date', [$from, date('Y-m-d', $to)])
                        ->orderBy('id', 'desc')->get();
                }
            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $inventories = Disposal::whereBetween('dispose_date', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_d ate']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $inventories = Disposal::whereBetween('dispose_date', ['', date('Y-m-d', $to)])
                    ->orderBy('id', 'desc')->get();
            } else {
                if (isset($fields['handover'])) {
                    if ($fields['handover'] == 1) {
                        $inventories = Disposal::whereNotNull('handover_date')->orderBy('id', 'desc')->get();
                    } else {
                        $inventories = Disposal::whereNull('handover_date')->orderBy('id', 'desc')->get();
                    }
                } else {
                    $inventories = Disposal::orderBy('id', 'desc')->get();
                }
            }
        }
        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                $issue = Issue::where('inventory_id', $inventory->inventory_id)->orderBy('id', 'DESC')->first();
                if ($issue) {
                    $user = Employee::where('emp_code', $issue->employee_id)->first();
                    if ($user) {
                        $inventory->user = $user;
                    }
                }

            }
        }
        $data['disposals'] = $inventories;
        //return $data;
        return view('show_disposal', $data);
    }

    public function vendor_buying(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                  = array();
        $array                 = array();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['filters']       = array();
        if (empty($request->all())) {
            $inventories = array();
        } else {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            if (empty($request->subcategory_id)) {
                $subcat = Subcategory::where('status', 1)->get();
            } else {
                $subcat = Subcategory::where('id', $request->subcategory_id)->get();
            }

            $i = 0;
            foreach ($subcat as $sub) {

                if (isset($fields['from_date']) && isset($fields['to_date'])) {
                    $from = $fields['from_date'];
                    $to   = strtotime($fields['to_date'] . '+1 day');
                    unset($fields['from_date']);
                    unset($fields['to_date']);
                    $array[$i]['subcategory'] = $sub->sub_cat_name;
                    $array[$i]['vendor']      = Vendor::where('id', $request->vendor_id)->select('vendor_name')->first();
                    $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('vendor_id', $request->vendor_id)->whereBetween('updated_at', [$from, date('Y-m-d', $to)])->whereNotIn('status', [0])->count();
                    $array[$i]['amount']      = Inventory::where('subcategory_id', $sub->id)->where('vendor_id', $request->vendor_id)->whereBetween('updated_at', [$from, date('Y-m-d', $to)])->whereNotIn('status', [0])->sum('item_price');

                } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                    $from = $fields['from_date'];
                    unset($fields['from_date']);
                    $array[$i]['subcategory'] = $sub->sub_cat_name;
                    $array[$i]['vendor']      = Vendor::where('id', $request->vendor_id)->select('vendor_name')->first();
                    $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('vendor_id', $request->vendor_id)->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])->whereNotIn('status', [0])->count();
                    $array[$i]['amount']      = Inventory::where('subcategory_id', $sub->id)->where('vendor_id', $request->vendor_id)->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])->whereNotIn('status', [0])->sum('item_price');

                } else if (!isset($fields['from_d ate']) && isset($fields['to_date'])) {
                    $to = strtotime($fields['to_date'] . '+1 day');
                    unset($fields['to_date']);
                    $array[$i]['subcategory'] = $sub->sub_cat_name;
                    $array[$i]['vendor']      = Vendor::where('id', $request->vendor_id)->select('vendor_name')->first();
                    $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $request->vendor_id)->whereBetween('updated_at', ['', date('Y-m-d', $to)])->whereNotIn('status', [0])->count();
                    $array[$i]['amount']      = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $request->vendor_id)->whereBetween('updated_at', ['', date('Y-m-d', $to)])->whereNotIn('status', [0])->sum('item_price');

                } else {
                    $array[$i]['subcategory'] = $sub->sub_cat_name;
                    $array[$i]['vendor']      = Vendor::where('id', $request->vendor_id)->select('vendor_name')->first();
                    $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $request->vendor_id)->whereNotIn('status', [0])->count();
                    $array[$i]['amount']      = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $request->vendor_id)->whereNotIn('status', [0])->sum('item_price');
                }
                if ($array[$i]['total_items'] == 0) {
                    unset($array[$i]);
                }
                $i++;
            }
        }

        $data['inventories'] = $array;
        return view('show_vendorbuying', $data);
    }

    public function dispatchin_report(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data            = array();
        $data['filters'] = array();
        if (empty($request->all())) {
            $inventories = array();
        } else {
            $fields = array_filter($request->all());

            unset($fields['_token']);
            $data['filters'] = $fields;
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $inventories = Dispatchin::whereBetween('dispatchin_date', [$from, date('Y-m-d', $to)])
                    ->orderBy('id', 'desc')->get();
            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $inventories = Dispatchin::whereBetween('dispatchin_date', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_d ate']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $inventories = Dispatchin::whereBetween('dispatchin_date', ['', date('Y-m-d', $to)])
                    ->orderBy('id', 'desc')->get();
            } else {
                $inventories = Dispatchin::orderBy('id', 'desc')->get();
            }
        }
        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                if (!empty($inventory->inventory)) {
                    $user = Employee::where('emp_code', $inventory->inventory->issued_to)->first();
                    if ($user) {
                        $inventory->user = $user;
                    }
                }
            }
        }
        $data['dispatch'] = $inventories;
        // return $data;
        return view('show_dispatchin', $data);
    }

    public function dispatchout_report(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data            = array();
        $data['filters'] = array();
        if (empty($request->all())) {
            $inventories = array();
        } else {
            $fields = array_filter($request->all());

            unset($fields['_token']);
            $data['filters'] = $fields;
            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to   = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $inventories = Dispatchout::whereBetween('dispatchout_date', [$from, date('Y-m-d', $to)])
                    ->orderBy('id', 'desc')->get();
            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $inventories = Dispatchout::whereBetween('dispatchout_date', [$from, date('Y-m-d', strtotime('+1 day'))])
                    ->orderBy('id', 'desc')->get();
            } else if (!isset($fields['from_d ate']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $inventories = Dispatchout::whereBetween('dispatchout_date', ['', date('Y-m-d', $to)])
                    ->orderBy('id', 'desc')->get();
            } else {
                $inventories = Dispatchout::orderBy('id', 'desc')->get();
            }
        }
        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                $user = Employee::where('emp_code', $inventory->inventory->issued_to ?? '')->first();
                if ($user) {
                    $inventory->user = $user;
                }

            }
        }
        $data['dispatch'] = $inventories;
        //return $data;
        return view('show_dispatchout', $data);
    }

    public function reorder_level(Request $request)
    {
        $budget                = array();
        $data                  = array();
        $data['filters']       = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        if (empty($request->all())) {
            $records = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            $from            = date('Y-m-d', strtotime('-3 months'));
            $to              = date('Y-m-d', strtotime('+1 day'));
            $records         = array();
            $subcategories   = Subcategory::where([[$fields]])->where('status', 1)->get();
            foreach ($subcategories as $subcategory) {
//                dd($subcategory->in_stock);
                $items_in_stock = Inventory::where('subcategory_id', $subcategory->id)->where('issued_to', null)->whereNotIn('devicetype_id', [1])->count();
//                dd($items_in_stock);
                $subcategory->in_stock     = $items_in_stock;
                $subcategory->issued_count = 0;
                $inventories               = Inventory::where('subcategory_id', $subcategory->id)->whereNotNull('issued_to')->whereNotIn('devicetype_id', [1])->get();
                foreach ($inventories as $inv) {
                    $subcategory->issued_count += Issue::where('inventory_id', $inv->id)->whereBetween('updated_at', [$from, $to])->count();
                }
                if ($items_in_stock <= $subcategory->threshold) {
                    $records[] = $subcategory;
                }
            }
        }
        $data['reorders'] = $records;
        //return $subcategories;
        return view('show_reorders', $data);
    }

    public function sla_report(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                  = array();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->where('category_id', '163')->orderBy('sub_cat_name', 'asc')->get();
        $data['types']         = Type::orderBy('type', 'asc')->get();
        $data['years']         = Year::where('locked', null)->orderBy('year', 'asc')->get();
        $data['filters']       = array();
        $sla_data              = array();
        if (empty($request->all())) {
            $records = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            $records         = array();
            $sla_data        = SLA::where([[$fields]])->get();
        }
        $data['reorders'] = $sla_data;
        return view('sla_report', $data);
    }

    public function sla_complain_report(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                  = array();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->where('category_id', '163')->orderBy('sub_cat_name', 'asc')->get();
        $data['types']         = Type::orderBy('type', 'asc')->get();
        $data['years']         = Year::all();
        $data['filters']       = array();
        $sla_data              = array();
        if (empty($request->all())) {
            $records = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            $records         = array();
            $sla_data        = SLAComplainLog::where([[$fields]])->get();
        }
        $data['reorders'] = $sla_data;
        return view('sla_complain_report', $data);
    }

    public function sla_consumption_report(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data            = array();
        $data['years']   = Year::all();
        $data['filters'] = array();
        $data['months']  = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $data['diff']    = '';
        $data['year_y']  = '';
        $arr             = array();

        if (empty($request->all())) {
            $records = array();
        } else {
            $fields = array_filter($request->all());
            unset($fields['_token']);
            $data['filters'] = $fields;
            $selected_year   = Year::find($request->year_id);
            $data['diff']    = Carbon::parse($selected_year->year_start_date)->diffInMonths(Carbon::parse($selected_year->year_end_date), true);
            $data['year_y']  = Carbon::parse($selected_year->year_start_date)->format("Y");
            $log             = SLAComplainLog::select(DB::raw('subcategory_id as subcategory_id'),
                'vendor_id',
                'category_id',
                'year_id',
                'type_id',
                'created_at',
                DB::raw('SUM(cost_occured) as cost_occured'),
                DB::raw('group_concat(current_dollar_rate) as current_dollar_rate'),
                DB::raw('group_concat(issue_occur_date) as issue_occur_date'),
                DB::raw("DATE_FORMAT(issue_occur_date, '%m') issue_occur_date_month"),
                DB::raw('MONTH(issue_occur_date) month')
            )
                ->where('year_id', $request->year_id)
                ->whereBetween('issue_occur_date', [$selected_year->year_start_date, $selected_year->year_end_date])
                ->groupBy(['subcategory_id', 'month'])
                ->get();
            $arr             = array();
            foreach ($log as $log_data) {
                $sla                                                       = SLA::where('type_id', $log_data->type_id)->where('year_id', $log_data->year_id)
                    ->where('subcategory_id', $log_data->subcategory_id)->first();
                $arr[$log_data->subcategory_id]['sub_cat_id']              = Subcategory::findorfail($log_data->subcategory_id)['sub_cat_name'];
                $arr[$log_data->subcategory_id]['vendor_id']               = Vendor::findorfail($log_data->vendor_id)['vendor_name'];
                $arr[$log_data->subcategory_id]['created_at']              = $log_data->created_at;
                $arr[$log_data->subcategory_id]['category_id']             = $log_data->category_id;
                $arr[$log_data->subcategory_id]['year_id']                 = $log_data->year_id;
                $arr[$log_data->subcategory_id]['type_id']                 = $log_data->type_id;
                $arr[$log_data->subcategory_id]['sla']                     = $sla;
                $arr[$log_data->subcategory_id][$log_data->month]['month'] = $log_data->month;
                $arr[$log_data->subcategory_id][$log_data->month]['cost']  = $log_data->cost_occured;
            }
        }
        return view('sla_consumption_report', $data, compact('arr'));
    }

}
