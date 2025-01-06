<?php

namespace App\Http\Controllers;

use App\Budgetitem;
use App\Category;
use App\Devicetype;
use App\Inventory;
use App\Invoicerelation;
use App\InventoryInvoice;
use App\Inventorytype;
use App\Itemnature;
use App\Location;
use App\Makee;
use App\Modal;
use App\Store;
use App\Subcategory;
use App\Type;
use App\Vendor;
use App\VendorTerm;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\SystemLogs;
class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:add_invoice_recording', ['only' => ['store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Invoicerelation::all();
        return view('invoice_recording',compact('data'));
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = array();
        $invoice = InventoryInvoice::findorFail($id);
        $data['categories'] = Category::where('status',1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status',1)->orderBy('sub_cat_name', 'asc')->get();
        $data['years']    = Year::whereNull('locked')->get();
        $data['types']    = Type::all();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        $data['stores'] = Store::orderBy('store_name', 'asc')->get();
        $data['models'] = Modal::find($invoice->model_id);
//        $data['models'] = Modal::where('status',1)->orderBy('model_name', 'asc')->get();
        $data['makes'] = Makee::where('status',1)->orderBy('make_name', 'asc')->get();
        $data['vendors'] = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes'] = Devicetype::where('status',1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures'] = Itemnature::where('status',1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = Inventorytype::where('status',1)->orderBy('inventorytype_name', 'asc')->get();
        return view('edit_invoice_recording',compact('invoice'),$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = InventoryInvoice::find($id);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'subcategory_id' => 'required|not_in:0',
            'item_price' => 'required',
            'dollar_rate' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $fields = $request->all();
        unset($fields['_method']);
        unset($fields['_token']);
        unset($fields['add_inventory']);
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
        $create = $invoice::where('id', $invoice->id)->update($fields);
        if ($create) {
            InventoryInvoice::where('id', $invoice->id)->update(['item_price_tax' => floor($request->item_price_tax)]);
            return redirect()->back()->with('msg', 'Invoice Recording Added Successfully!');
        } else {
            return redirect()->back()->with('error-msg', 'Could not add invoice recording, Try Again!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = Invoicerelation::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        return $find->delete() ? redirect()->back()->with('msg', 'Inventory Invoice Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete inventory invoice, Try Again!');
    }


    public function add_invoice_recording(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'category_id' => 'required|not_in:0',
//            'subcategory_id' => 'required|not_in:0',
//            'item_price' => 'required',
//            'dollar_rate' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields = $request->all();
//        $fields['item_price'] = str_replace(",", "", $fields['item_price']);
//        $fields['dollar_rate'] = str_replace(",", "", $fields['dollar_rate']);
        $loggedin_user = Auth::id();
        $product_serial_number = date('dmYhhms');
        $fields['added_by'] = $loggedin_user;
        $create = InventoryInvoice::create([
            'type_id' => $fields['type_id'],
            'year_id' => $fields['year_id'],
            'vendor_id' => $fields['vendor_id'],
            'po_number' => $fields['po_number'],
            'purchase_date' => $fields['purchase_date'],
            'invoice_number' => $fields['invoice_number'],
            'invoice_date' => $fields['invoice_date'],
//            'product_sn' => $this->get_first_char($data) . $product_serial_number,
            'itemnature_id' => 21
        ]);

        if ($create) {
            $count = count($request->category_id);
            $data = array();
            $rows = array();
            for($i=0;$i<$count;$i++){
                $data['category_id'] =  $request->category_id[$i];
                $data['subcategory_id'] =  $request->subcategory_id[$i];
                $data['model_id'] =  $request->model_id[$i];
                $data['make_id'] =  $request->make_id[$i];
                $data['item_price'] =  $request->item_price[$i];
                $data['tax'] =  $request->tax[$i];
                $data['dollar_rate'] =  $request->dollar_rate[$i];
                $data['contract_issue_date'] =  $request->contract_issue_date[$i];
                $data['contract_end_date'] =  $request->contract_end_date[$i];
//                $data['warrenty_period'] =  $request->warrenty_period[$i];
//                $data['warranty_end'] =  $request->warranty_end[$i];
                $data['invoice_number'] =  $fields['invoice_number'];
                $data['added_by'] =  $loggedin_user;
                $data['invoice_date'] =  $fields['invoice_date'];
                $data['type_id'] =  $fields['type_id'];
                $data['year_id'] =  $fields['year_id'];
                $data['vendor_id'] =  $fields['vendor_id'];
                $data['po_number'] =  $fields['po_number'];
                $data['purchase_date'] =  $fields['purchase_date'];
                $data['itemnature_id'] =  21;
                $data['status'] =  1;

                array_push($rows,$data);
            }
            for ($j=0;$j<count($rows);$j++){
                $find_budget_item = Budgetitem::where('category_id', $rows[$j]['category_id'])
                    ->where('subcategory_id', $rows[$j]['subcategory_id'])
                    ->where('type_id',$fields['type_id'])
                    ->where('year_id', $fields['year_id'])->first();
                if($find_budget_item != null){
                    $qty = $find_budget_item->qty;
                    $find_budget_item->consumed = $qty;
                    $find_budget_item->remaining = 0;
                    $find_budget_item->update();
                }
                $psn_data = [
                    'category_id' => $rows[$j]['category_id'],
                    'subcategory_id' => $rows[$j]['subcategory_id'],
                    'vendor_id' => $fields['vendor_id'],
                ];
                $item_price= str_replace(",", "",$rows[$j]['item_price']);
//                $item_price_tax = ($item_price * $rows[$j]['tax']) + $item_price;
                $insert = Invoicerelation::create([
                    "category_id" => $rows[$j]['category_id'],
                    "subcategory_id" => $rows[$j]['subcategory_id'],
                    "model_id" => $rows[$j]['model_id'],
                    "make_id" => $rows[$j]['make_id'],
                    "item_price" => $item_price,
                    "dollar_rate" => $rows[$j]['dollar_rate'],
                    "tax" => $rows[$j]['tax'],
                    "contract_issue_date" => $rows[$j]['contract_issue_date'],
                    "contract_end_date" => $rows[$j]['contract_end_date'],
//                    "warrenty_period" => $rows[$j]['warrenty_period'],
//                    "warranty_end" => date('Y-m-d', strtotime("+".$rows[$j]['warrenty_period']."months", strtotime($fields['purchase_date']))),
                    "invoice_number" => $rows[$j]['invoice_number'],
//                    "item_price_tax" => $item_price_tax,
                    "product_sn" => $this->get_first_char($psn_data) . $product_serial_number,
                    "type_id" => $rows[$j]['type_id'],
                    "year_id" => $rows[$j]['year_id'],
                    "vendor_id" => $rows[$j]['vendor_id'],
                    "po_number" => $rows[$j]['po_number'],
                    "purchase_date" => $rows[$j]['purchase_date'],
                    "invoice_date" => $rows[$j]['invoice_date'],
                    "added_by" => $rows[$j]['added_by'],
                    "itemnature_id" => $rows[$j]['itemnature_id'],
//                    "invoice_tbl_id" => $create->id,
                   'status'       => 1
                ]);
            }
            $log = SystemLogs::Add_logs('inventory_invoice',$request->all(),'insert');
            return redirect()->back()->with('msg', 'Invoice Recording Added Successfully!');
        } else {
            return redirect()->back()->with('error-msg', 'Could not add invoice recording, Try Again!');
        }
    }

}
