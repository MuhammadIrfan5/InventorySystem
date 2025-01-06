<?php

namespace App\Http\Controllers;

use App\Budgetitem;
use App\Budgetitem as Budget;
use App\BudgetPlanIT;
use App\BudgetPlanRelation;
use App\Disposal;
use App\InventoryInvoice;
use App\Invoicerelation;
use App\LinkedSubcategory;
use App\Privilige;
use App\SlaLogType;
use App\TransferInventoryRequest;
use App\UserPrivilige;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Subcategory;
use App\EmployeeBranch;
use App\Location;
use App\Department;
use App\InventoryIssueRecord;
use App\Branch;
use App\Store;
use App\SLA;
use App\Modal;
use App\Makee;
use App\Vendor;
use App\Devicetype;
use App\Itemnature;
use App\Inventorytype;
use App\Role;
use App\User;
use App\Issue;
use App\Transfer;
use App\Rturn;
use App\Inventory;
use App\Repairing;
use App\Employee;
use App\Year;
use App\Dollar;
use App\Type;
use App\Disposalstatus;
use App\SystemLogs;
use Illuminate\Validation\Rules\In;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
    }

    public function add_category()
    {
        return view('add_category');
    }

    public function add_subcategory()
    {
        $category = Category::orderBy('category_name', 'asc')->get();
        return view('add_subcategory', ['categories' => $category]);
    }

    public function add_branch()
    {
        return view('add_branch');
    }

    public function add_department()
    {
        return view('add_department');
    }

    public function add_location()
    {
        return view('add_location');
    }

    public function add_model()
    {
        $make = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        return view('add_model', ['makes' => $make]);
    }

    public function add_role()
    {
        return view('add_role');
    }

    public function add_devicetype()
    {
        return view('add_devicetype');
    }

    public function add_itemnature()
    {
        return view('add_itemnature');
    }

    public function add_inventorytype()
    {
        return view('add_inventorytype');
    }

    public function add_dollar_price()
    {
        $year = Year::orderBy('year', 'asc')->get();
        return view('add_dollar_price', ['years' => $year]);
    }

    public function add_year()
    {
        return view('add_year');
    }

    public function add_type()
    {
        return view('add_type');
    }

    public function add_d_status()
    {
        return view('add_d_status');
    }

    public function add_disposal()
    {
        $data               = array();
        $data['categories'] = \request()->user()['role_id'] == 3 ? Category::whereIn('id', [27, 82])->where('status', 1)->orderBy('category_name', 'asc')->get() : Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        //$data['subcategories'] = Subcategory::where('status',1)->orderBy('sub_cat_name', 'asc')->get();
        //$data['inventories'] = Inventory::where('issued_to', NULL)->whereIn('status', [1,2])->orderBy('id', 'desc')->get();
        $data['statuses'] = Disposalstatus::all();
        return view('add_disposal', $data);
    }

    public function add_previous_inventory()
    {
        $data                = array();
        $data['categories']  = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['statuses']    = Disposalstatus::all();
        $data['departments'] = EmployeeBranch::groupBy('branch_name')->get();
        return view('add_previous_equipment', $data);
    }

    public function add_dispatchin()
    {
        $data               = array();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('add_dispatchin', $data);
    }

    public function add_dispatchout()
    {
        $data               = array();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('add_dispatchout', $data);
    }

    public function add_budget()
    {
        $data = array();
        $Link = DB::table('links')->get()[0]->url;
//        $api = $this->callAPI('GET', $Link . 'branchdataall.php?uid=1', false);
//        dd($Link.'deptdataall.php?uid=1');
        $api        = $this->callAPI('GET', $Link . 'deptdataall.php?uid=1', false);
        $department = Department::all();

        foreach ($department as $dept) {
            $dept->DEPARTMENT    = $dept->department_name . ' (' . $dept->department_code . ')';
            $dept->DEPARTMENT_ID = $dept->department_code;
        }
        $json_branches      = json_decode($api, true);
        $data['department'] = array_merge($json_branches['Login'], $department->toArray());
//        dd($data['department']);
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['types']         = Type::orderBy('type', 'asc')->get();
        $data['years']         = Year::where('locked', 0)->orderBy('year', 'asc')->get();
        return view('add_budget', $data);
    }

    function callAPI($method, $url, $data)
    {

        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                break;
//                if ($data  == false)
//                    curl_setopt($curl, CURLOPT_URL, $url);
//                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        $headers = array(
            'Content-Type: application/json',
        );
//        `1`
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);

        return $result;
    }

    public function add_budget_collection()
    {
        $data = array();
        $Link = DB::table('links')->get()[0]->url;
//        $api = $this->callAPI('GET', $Link . 'branchdataall.php?uid=1', false);
//        dd($Link.'deptdataall.php?uid=1');
        $api        = $this->callAPI('GET', $Link . 'deptdataall.php?uid=1', false);
        $department = Department::all();

        foreach ($department as $dept) {
            $dept->DEPARTMENT    = $dept->department_name . ' (' . $dept->department_code . ')';
            $dept->DEPARTMENT_ID = $dept->department_code;
        }
        $json_branches      = json_decode($api, true);
        $data['department'] = array_merge($json_branches['Login'], $department->toArray());
//        dd($data['department']);
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['types']         = Type::orderBy('type', 'asc')->get();
        $data['years']         = Year::where('locked', 0)->where('is_budget_collection', 1)->orderBy('year', 'asc')->get();
        return view('add_budget_collection', $data);
    }

    public function add_budget_planing()
    {

        $data                  = array();
        $data['categories']    = Category::where('status', 1)->where('is_budget_collection', null)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->where('is_budget_collection', 1)->orderBy('sub_cat_name', 'asc')->get();
//        dd($data['subcategories']);
        $data['approx_price']  = array();
        $data['linked_subcat'] = array();
        foreach ($data['subcategories'] as $sub_cat) {
            if ($sub_cat->approx_price_dollar != null) {
                array_push($data['approx_price'], $sub_cat->approx_price_dollar);
            } else {
                array_push($data['approx_price'], "0");
            }
//            $budget_items = Budgetitem::whereIn("year_id",[83,85,87,88])->where("subcategory_id",$sub_cat->id)->whereNotNull('unit_price_dollar')->orderBy('created_at', 'desc')->first();
//            if($budget_items) {
//                array_push($data['approx_price'],$budget_items->unit_price_dollar);
//            }
//            else{
//                array_push($data['approx_price'],"0");
//            }
            $linked_sub = LinkedSubcategory::where('subcategory_id', $sub_cat->id)->get();
            if (!$linked_sub->isEmpty()) {
                array_push($data['linked_subcat'], $linked_sub);
            }
        }
//        $data['types'] = Type::orderBy('type', 'asc')->get();
        $data['years'] = Year::where('locked', 0)->where('is_budget_collection', 1)->select('year', 'id')->orderBy('year', 'asc')->first();
        $data['check'] = BudgetPlanIT::where('user_id', auth()->user()->id)->first();
        if (!empty($data['check'])) {
            $data['check_message'] = false;
        } else {
            $data['check_message'] = true;
        }
        return view('add_budget_plan', $data);
    }

    public function edit_budget_planing($id)
    {
        $data                       = array();
        $data['categories']         = Category::where('status', 1)->where('is_budget_collection', null)->orderBy('category_name', 'asc')->get();
        $data['subcategories']      = Subcategory::where('status', 1)->where('is_budget_collection', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['budget_plan']        = BudgetPlanIT::find($id);
        $data['user_linked_subcat'] = BudgetPlanRelation::where('user_id', auth()->user()->id)->where('plan_budget_id', $id)->get();
        foreach ($data['user_linked_subcat'] as $link) {
            $relation = BudgetPlanRelation::where('user_id', auth()->user()->id)->where('plan_budget_id', $id)->where('subcategory_id', $link->subcategory_id)->first();
            $checkk   = LinkedSubcategory::where('subcategory_id', $link->subcategory_id)->first();
            foreach ($data['subcategories'] as $subcat) {
                if ($checkk != null) {
                    if ($link->subcategory_id == $subcat->id) {
                        $subcat->upgradedQty = $relation->upgraded_qty;
                        $subcat->newQty      = $relation->new_qty;
                        $subcat->approx_cost = $relation->approx_cost;
                        $subcat->remarks     = $relation->remarks;
                    } else {
                        if ($subcat->id != $checkk->subcategory_id) {
                            $notrelation = BudgetPlanRelation::where('user_id', auth()->user()->id)->where('plan_budget_id', $id)->where('subcategory_id', $subcat->id)->first();
                            if ($notrelation != null) {
                                $subcat->upgradedQty = $notrelation->upgraded_qty;
                                $subcat->newQty      = $notrelation->new_qty;
                                $subcat->approx_cost = $notrelation->approx_cost;
                                $subcat->remarks     = $notrelation->remarks;
                            } else {
                                $subcat->upgradedQty = "";
                                $subcat->newQty      = "";
                                $subcat->approx_cost = '';
                                $subcat->remarks     = '';
                            }
                        }
                    }
                }
            }
        }
        $data['approx_price']  = array();
        $data['linked_subcat'] = array();
        foreach ($data['subcategories'] as $sub_cat) {
            $budget_items = Budgetitem::whereIn("year_id", [83, 85, 87, 88])->where("subcategory_id", $sub_cat->id)->whereNotNull('unit_price_dollar')->orderBy('created_at', 'desc')->first();
            if ($budget_items) {
                array_push($data['approx_price'], $budget_items->unit_price_dollar);
            } else {
                array_push($data['approx_price'], "0");
            }
            $linked_sub = LinkedSubcategory::where('subcategory_id', $sub_cat->id)->get();
            if (!$linked_sub->isEmpty()) {
                array_push($data['linked_subcat'], $linked_sub);
            }
        }
//        $data['types'] = Type::orderBy('type', 'asc')->get();
        $data['years'] = Year::where('locked', 0)->where('is_budget_collection', 1)->select('year', 'id')->orderBy('year', 'asc')->first();
        return view('edit_budget_plan', $data);
    }

    public function show_budget()
    {
        $data               = array();
        $data['years']      = Year::orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['budgets']    = array();
        $data['filters']    = (object)array('catid' => '', 'yearid' => '');
        return view('show_budget', $data);
    }

    public function show_subcategory_budget()
    {
        $data                 = array();
        $data['years']        = Year::orderBy('year', 'asc')->get();
        $data['categories']   = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['capex_budget'] = array();
        $data['opex_budget']  = array();
        $data['filters']      = (object)array('catid' => '', 'yearid' => '');
        return view('show_subcategory_budget', $data);
    }

    public function show_subcategory_budget_adv()
    {
        $data                       = array();
        $data['years']              = Year::orderBy('year', 'asc')->get();
        $data['categories']         = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['capex_budget_items'] = array();
        $data['opex_budget_items']  = array();
        $data['filters']            = (object)array('catid' => '', 'yearid' => '');
        return view('show_subcategory_budget_adv', $data);
    }

    public function show_subcategory_budget_summary()
    {
        $data          = array();
        $data['years'] = Year::orderBy('year', 'asc')->get();
//        $data['categories'] = Category::where('status',1)->orderBy('category_name', 'asc')->get();
        $data['capex_budget_year'] = array();
        $data['opex_budget_year']  = array();
        $year                      = array();
        $data['filters']           = (object)array('catid' => '', 'yearid' => '', 'prev_year_id' => [], 'prev_year_id_new' => '', 'prev_year_name' => $year, 'prev_year_name_new' => $year);
        return view('show_subcategory_budget_summary', $data);
    }

    public function budget_comparison()
    {
        $data                      = array();
        $data['years']             = Year::orderBy('year', 'asc')->get();
        $data['categories']        = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['capex_budget_year'] = array();
        $data['capex_budget_from'] = array();
        $data['opex_budget_year']  = array();
        $data['opex_budget_from']  = array();
        $year                      = array();
        $data['filters']           = (object)array('category_id' => '', 'to_year_id' => '', 'from_year_id' => '', 'year_name' => $year);

        return view('budget_compare', $data);
    }

    public function summary()
    {

        return view('summary',
            ['filter' => '',
             'types'  => array(),
             'years'  => Year::orderBy('year', 'asc')->get()]
        );
    }

    public function summaryDollar(Request $request)
    {
        $data['yearList']   = Year::orderBy('year', 'asc')->get();
        $data['capexTotal'] = 0;
        $data['opexTotal']  = 0;
        return view('summaryDollar', $data);
    }

    public function summary2()
    {
        return view('summary2', ['filter' => '', 'types' => array(), 'years' => Year::orderBy('year', 'asc')->get()]);
    }

    public function add_store()
    {
        $user     = User::where('role_id', 2)->orderBy('name', 'asc')->get();
        $location = Location::orderBy('location', 'asc')->get();
        return view('add_store', ['users' => $user, 'locations' => $location]);
    }

    public function add_user()
    {
        $role = Role::orderBy('role', 'asc')->get();
        return view('add_user', ['roles' => $role]);
    }

    public function add_employee()
    {

        $Link = DB::table('links')->get()[0]->url;
//        $api = $this->callAPI('GET', $Link . 'branchdataall.php?uid=1', false);
//        dd($Link.'deptdataall.php?uid=1');

        $api        = $this->callAPI('GET', $Link . 'deptdataall.php?uid=1', false);
        $department = Department::all();

        foreach ($department as $dept) {
            $dept->DEPARTMENT    = $dept->department_name . ' (' . $dept->department_code . ')';
            $dept->DEPARTMENT_ID = $dept->department_code;
        }
        $json_branches     = json_decode($api, true);
        $branches['Login'] = array_merge($json_branches['Login'], $department->toArray());
        return view('add_employee', compact('branches'));
    }

    public function get_employee_new($emp_code)
    {
        $accessKey   = "2698D657-6185-402B-A033-206DD3B8238B";
        $secretKey   = "A2sQ262qWDXQ2";
        $clientIndex = "1137";
        $url         = "https://api-edm-501.mydecibel.com/restdataservice.svc/GetEmployeesData?AccessKey=" . $accessKey . "&Secret=" . $secretKey . "&Client=" . $clientIndex . "&ListEmployeeId=" . $emp_code;
        $curl        = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $headers = array(
            'Content-Type: application/json'
//            'Authorization: Basic api_prod_user:bs9atsw5s46m@$'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERPWD, "api_prod_user:bs9atsw5s46m@$");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return json_decode($result, true);
    }

    public function add_inventory()
    {
        $data                  = array();
        $data['categories']    = \request()->user()['role_id'] == 3 ? Category::whereIn('id', [27, 82])->where('status', 1)->orderBy('category_name', 'asc')->get() : Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        // $data['departments'] = Department::where('status',1)->get();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        // $data['branches'] = Branch::where('status',1)->get();
        $data['stores']         = Store::orderBy('store_name', 'asc')->get();
        $data['models']         = Modal::where('status', 1)->orderBy('model_name', 'asc')->get();
        $data['makes']          = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['vendors']        = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes']    = Devicetype::where('status', 1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures']    = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = \request()->user()['role_id'] == 3 ? Inventorytype::whereIn('id', [1, 25, 61])->where('status', 1)->orderBy('inventorytype_name', 'asc')->get() : Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['types']          = Type::orderBy('type', 'asc')->get();
        $data['years']          = \request()->user()['role_id'] == 3 ? Year::whereIn('id', [41, 92])->where('inventory_allowed', 1)->get() : Year::where('inventory_allowed', 1)->get();
        return view('add_inventory', $data);
    }

    public function add_sla()
    {
        $data                  = array();
        $data['subcategories'] = Subcategory::where('status', 1)->where('category_id', '163')->orderBy('sub_cat_name', 'asc')->get();
        $data['vendors']       = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['types']         = Type::orderBy('type', 'asc')->get();
        $data['years']         = Year::where('is_current_year', 1)->orderBy('year', 'asc')->get();
        return view('add_sla', $data);
    }

    public function add_sla_log()
    {
        $data        = array();
        $vendors     = array();
        $sla_vendors = SLA::select('vendor_id')->distinct()->orderBy('vendor_id', 'ASC')->get();
        foreach ($sla_vendors as $vendor) {
            $vendors[] = Vendor::where('id', $vendor->vendor_id)->orderBy('vendor_name', 'asc')->first();
        }

        $data['subcategories']    = Subcategory::where('status', 1)->where('category_id', '163')->orderBy('sub_cat_name', 'asc')->get();
        $data['types']            = Type::orderBy('type', 'asc')->get();
        $data['years']            = Year::where('is_current_year', 1)->orderBy('year', 'asc')->get();
        $data['issue_product_sn'] = Inventory::select('id', 'product_sn')->distinct()->orderBy('product_sn', 'asc')->get();
        $data['sla_types']        = SlaLogType::all();
        return view('add_sla_log', ['data' => $data, 'vendors' => $vendors]);
    }

    public function add_invoice_recording()
    {
        $data                   = array();
        $data['categories']     = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories']  = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['years']          = Year::where('is_current_year', 1)->get();
        $data['types']          = Type::find(2);
        $data['locations']      = Location::orderBy('location', 'asc')->get();
        $data['stores']         = Store::orderBy('store_name', 'asc')->get();
        $data['models']         = Modal::where('status', 1)->orderBy('model_name', 'asc')->get();
        $data['makes']          = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['vendors']        = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes']    = Devicetype::where('status', 1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures']    = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        return view('add_invoice_recording', $data);
    }

    public function add_vendor_term()
    {
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        // $data['departments'] = Department::where('status',1)->get();
        $data['years']   = Year::whereNull('locked')->get();
        $data['types']   = Type::all();
        $data['terms']   = DB::table('vendorterm')
            ->select('id', 'term_type')
            ->get();
        $data['vendors'] = Vendor::orderBy('vendor_name', 'asc')->get();
        return view('add_vendor_term', $data);
    }

    public function add_with_grn()
    {
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        // $data['departments'] = Department::where('status',1)->get();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        // $data['branches'] = Branch::where('status',1)->get();
        $data['stores']         = Store::orderBy('store_name', 'asc')->get();
        $data['models']         = Modal::where('status', 1)->orderBy('model_name', 'asc')->get();
        $data['makes']          = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['vendors']        = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes']    = Devicetype::where('status', 1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures']    = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['types']          = Type::orderBy('type', 'asc')->get();
        $data['years']          = Year::where('inventory_allowed', 1)->get();
        //where('locked', null)->orderBy('year', 'asc')->get()
        return view('addwithgrn', $data);
    }

    public function add_with_grn_multiple()
    {
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        // $data['departments'] = Department::where('status',1)->get();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        // $data['branches'] = Branch::where('status',1)->get();
        $data['stores']         = Store::orderBy('store_name', 'asc')->get();
        $data['models']         = Modal::where('status', 1)->orderBy('model_name', 'asc')->get();
        $data['makes']          = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['vendors']        = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes']    = Devicetype::where('status', 1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures']    = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['types']          = Type::orderBy('type', 'asc')->get();
        $data['years']          = Year::where('inventory_allowed', 1)->get();
        //where('locked', null)->orderBy('year', 'asc')->get()
        return view('addwithgrn_multiple', $data);
    }

    public function add_with_grn_bulk()
    {
        $data                  = array();
        $data['categories']    = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        // $data['departments'] = Department::where('status',1)->get();
        $data['locations'] = Location::orderBy('location', 'asc')->get();
        // $data['branches'] = Branch::where('status',1)->get();
        $data['stores']         = Store::orderBy('store_name', 'asc')->get();
        $data['models']         = Modal::where('status', 1)->orderBy('model_name', 'asc')->get();
        $data['makes']          = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
        $data['vendors']        = Vendor::orderBy('vendor_name', 'asc')->get();
        $data['devicetypes']    = Devicetype::where('status', 1)->orderBy('devicetype_name', 'asc')->get();
        $data['itemnatures']    = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
        $data['inventorytypes'] = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
        $data['types']          = Type::orderBy('type', 'asc')->get();
        $data['years']          = Year::where('inventory_allowed', 1)->get();
        //where('locked', null)->orderBy('year', 'asc')->get()
        return view('addwithgrn_bulk', $data);
    }

    public function add_make()
    {
        return view('add_make');
    }

    public function add_vendor()
    {
        return view('add_vendor');
    }

    public function add_transfer_inventory_request()
    {
        return view('add_transfer_inventory_request');
    }

    public function issue_inventory()
    {
        $Link = DB::table('links')->get()[0]->url;
//        $api = $this->callAPI('GET', $Link . 'branchdataall.php?uid=1', false);
        $api      = $this->callAPI('GET', $Link . 'deptdataall.php?uid=1', false);
        $branches = json_decode($api, true);
//        ->whereIn('status', [1, 2])
        $inventory  = Inventory::where('issued_to', NULL)->where('devicetype_id', '!=', 5)->where('carry_forward_status_id', '!=', 3)->whereNotIn('devicetype_id', [1])->orderBy('id', 'desc')->get();
        $year       = Year::where('locked', null)->orderBy('year', 'asc')->get();
        $categories = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('issue_inventory', ['years' => $year, 'inventories' => $inventory, 'categories' => $categories, 'branches' => $branches]);
    }

    public function issue_inventory_bulk()
    {
        $Link = DB::table('links')->get()[0]->url;
//        $api = $this->callAPI('GET', $Link . 'branchdataall.php?uid=1', false);
        $api      = $this->callAPI('GET', $Link . 'deptdataall.php?uid=1', false);
        $branches = json_decode($api, true);
//        ->whereIn('status', [1, 2])
        $inventory  = Inventory::where('issued_to', NULL)->where('devicetype_id', '!=', 5)->where('carry_forward_status_id', '!=', 3)->whereNotIn('devicetype_id', [1])->orderBy('id', 'desc')->get();
        $year       = Year::where('locked', null)->orderBy('year', 'asc')->get();
        $categories = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('issue_inventory_bulk', ['years' => $year, 'inventories' => $inventory, 'categories' => $categories, 'branches' => $branches]);
    }

    public function submitt_issue_with_bulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inv_id'        => 'required',
            'employee_code' => 'required',
            'budget_id'     => 'required',
            'branch_id'     => 'required',
//            kjky56593
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $employee_data = Employee::where('emp_code', $request->employee_code)->first();
        $employee      = EmployeeBranch::where('branch_id', $request->branch_id)->first();
        $dept_id       = $employee->branch_id;
        $itemnames     = null;
        $itemsin       = null;
        $available     = false;
        $loggedin_user = Auth::id();
        foreach ($request->inv_id as $id) {
            $inventory = Inventory::find($id);
//            if ($request->budget_id != null) {
//                $budget = Budget::where('dept_id', $dept_id)->where('year_id', $inventory->year_id)->whereIn('id', $request->budget_id)->get();
//            }
//            if (empty($budget)) {
//                return redirect()->back()->with('msg', 'Budget not available in this employee`s department');
//            }
//            $budgets = Budget::where('dept_id', $dept_id)->where('year_id', $inventory->year_id)->where('subcategory_id', $inventory->subcategory_id)->whereIn('id', $request->budget_id)->get();

//            if (count($budgets) == 0) {
            $itemnames .= $inventory->subcategory->sub_cat_name . ', ';
//            } else {
//                foreach ($budgets as $b) {
//                    if ($b->consumed < $b->qty) {
//                        $budget = $b;
//                        break;
//                    } else {
//                        $budget = $b;
//                    }
//                }
//                if ($budget->consumed >= $budget->qty) {
//
            $itemnames .= $inventory->subcategory->sub_cat_name . ', ';
//
//                } else {
//                    $b_fields = array(
//                        'consumed' => $budget->consumed + 1,
//                        'remaining' => $budget->remaining - 1
//                    );
            $available = true;
            $itemsin   .= $inventory->subcategory->sub_cat_name . ', ';
//                    $b_fields = array(
//                        'consumed' => $budget->consumed + 1,
//                        'remaining' => $budget->remaining - 1
//                    );
//                    $b_update = Budget::where('id', $budget->id)->update($b_fields);
            $update       = Inventory::where('id', $inventory->id)->update(['issued_to' => $request->employee_code, 'issued_by' => $loggedin_user, 'devicetype_id' => 3, 'branch_id' => $request->branch_id, 'branch_name' => $request->branch_name, 'department_id' => $request->branch_id]);
            $insert       = Issue::create(['employee_id' => $request->employee_code, 'inventory_id' => $id, 'year_id' => $inventory->year_id, 'remarks' => $request->remarks]);
            $insert_issue = InventoryIssueRecord::create([
                'employee_id'     => $employee_data->id,
                'employee_code'   => $request->employee_code,
                'inventory_id'    => $id,
                'year_id'         => $inventory->year_id,
                'received_status' => 0,
                'issued_at'       => date('Y-m-d'),
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
//                }
//            }
        }

        $itemnames = rtrim($itemnames, ", ");
        $itemsin   = rtrim($itemsin, ", ");
        if ($available == true && $itemnames == null) {
            $data       = [
                'emp_id'         => $employee->id,
                'emp_code'       => $request->employee_code,
                'emp_email'      => $employee_data->email,
                'emp_name'       => $employee_data->name,
                'email_message'  => 'Inventory has been issued.',
                'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                'issue_PK'       => $insert_issue->id,
                'make'           => Makee::find($inventory->make_id)['make_name'],
                'product_sn'     => $inventory->product_sn,
                'subcategory'    => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
            ];
            $send_email = EmailController::SendUserMail($data);
            $log        = SystemLogs::Add_logs('issues', $request->all(), 'insert');
            return redirect()->back()->with('msg', 'Selected inventory issued to ' . $employee_data->name);
        } else if ($available == true && $itemnames != null) {
            return redirect()->back()->with('msg', $itemsin . ' issued to ' . $employee_data->name . ', ' . $itemnames . ' not available in budget!');
        } else {
            return redirect()->back()->with('msg', 'Budget not available for selected inventory');
        }
    }

    public function submitt_issue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inv_id'        => 'required',
            'employee_code' => 'required',
            'budget_id'     => 'required',
            'branch_id'     => 'required',
//            kjky56593
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $employee_data = Employee::where('emp_code', $request->employee_code)->first();
        $employee      = EmployeeBranch::where('branch_id', $request->branch_id)->first();
        $dept_id       = $employee->branch_id;
        $itemnames     = null;
        $itemsin       = null;
        $available     = false;
        $loggedin_user = Auth::id();
        foreach ($request->inv_id as $id) {
            $inventory = Inventory::find($id);
            if ($request->budget_id != null) {
                $budget = Budget::where('dept_id', $dept_id)->where('year_id', $inventory->year_id)->whereIn('id', $request->budget_id)->get();
            }
            if (empty($budget)) {
                return redirect()->back()->with('msg', 'Budget not available in this employee`s department');
            }
            $budgets = Budget::where('dept_id', $dept_id)->where('year_id', $inventory->year_id)->where('subcategory_id', $inventory->subcategory_id)->whereIn('id', $request->budget_id)->get();

            if (count($budgets) == 0) {
                $itemnames .= $inventory->subcategory->sub_cat_name . ', ';
            } else {
                foreach ($budgets as $b) {
                    if ($b->consumed < $b->qty) {
                        $budget = $b;
                        break;
                    } else {
                        $budget = $b;
                    }
                }
                if ($budget->consumed >= $budget->qty) {

                    $itemnames .= $inventory->subcategory->sub_cat_name . ', ';

                } else {
                    $b_fields     = array(
                        'consumed'  => $budget->consumed + 1,
                        'remaining' => $budget->remaining - 1
                    );
                    $available    = true;
                    $itemsin      .= $inventory->subcategory->sub_cat_name . ', ';
                    $b_fields     = array(
                        'consumed'  => $budget->consumed + 1,
                        'remaining' => $budget->remaining - 1
                    );
                    $b_update     = Budget::where('id', $budget->id)->update($b_fields);
                    $update       = Inventory::where('id', $inventory->id)->update(['issued_to' => $request->employee_code, 'issued_by' => $loggedin_user, 'devicetype_id' => 3, 'branch_id' => $request->branch_id, 'branch_name' => $request->branch_name, 'department_id' => $request->branch_id]);
                    $insert       = Issue::create(['employee_id' => $request->employee_code, 'inventory_id' => $id, 'year_id' => $inventory->year_id, 'remarks' => $request->remarks]);
                    $insert_issue = InventoryIssueRecord::create([
                        'employee_id'     => $employee_data->id,
                        'employee_code'   => $request->employee_code,
                        'inventory_id'    => $id,
                        'year_id'         => $inventory->year_id,
                        'received_status' => 0,
                        'issued_at'       => date('Y-m-d'),
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        $itemnames = rtrim($itemnames, ", ");
        $itemsin   = rtrim($itemsin, ", ");
        if ($available == true && $itemnames == null) {
            $data       = [
                'emp_id'         => $employee->id,
                'emp_code'       => $request->employee_code,
                'emp_email'      => $employee_data->email,
                'emp_name'       => $employee_data->name,
                'email_message'  => 'Inventory has been issued.',
                'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                'issue_PK'       => $insert_issue->id,
                'make'           => Makee::find($inventory->make_id)['make_name'],
                'product_sn'     => $inventory->product_sn,
                'subcategory'    => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
            ];
            $send_email = EmailController::SendUserMail($data);
            $log        = SystemLogs::Add_logs('issues', $request->all(), 'insert');
            return redirect()->back()->with('msg', 'Selected inventory issued to ' . $employee_data->name);
        } else if ($available == true && $itemnames != null) {
            return redirect()->back()->with('msg', $itemsin . ' issued to ' . $employee_data->name . ', ' . $itemnames . ' not available in budget!');
        } else {
            return redirect()->back()->with('msg', 'Budget not available for selected inventory');
        }
    }


    //Acknowledge Received Inventories
//    public function inventory_received(Request $request){
//        if (! $request->hasValidSignature()) {
//            abort(401);
//        }else{
//
//            $find_issue = Issue::where('id', $request->issued_PK)->where('employee_id' , $request->emp_code)->first();
//            if($find_issue != null) {
//                if ($find_issue->received_status == 0) {
//                    $issued = Issue::where('id', $request->issued_PK)->where('employee_id', $request->emp_code)->update(['received_status' => 1, 'received_at' => Carbon::now()]);
//                    $status = "Thank You for confirmation, Inventory issued to you.";
//                    return view('emails.issued', compact('status'));
//                } else {
//                    $status = 'Already Issued to you';
//                    return view('emails.issued', compact('status'));
//                }
//            }else{
//                $status = 'Something went wrong!';
//                return view('emails.issued', compact('status'));
//            }
//        }
//    }

    public function issue_with_gin()
    {
        $inventory  = Inventory::where('issued_to', NULL)->where('devicetype_id', '!=', 5)->where('carry_forward_status_id', '!=', 3)->whereIn('status', [1, 4, 2])->whereNotIn('devicetype_id', [1])->orderBy('id', 'desc')->get();
        $year       = Year::where('locked', null)->orderBy('year', 'asc')->get();
        $categories = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('issuewithgin', ['years' => $year, 'inventories' => $inventory, 'categories' => $categories]);
    }

    public function submit_gin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inv_id'        => 'required',
            'employee_code' => 'required',
            'branch_id'     => 'required',
            'budget_id'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $employee_data = Employee::where('emp_code', $request->employee_code)->first();
        $employee      = EmployeeBranch::where('branch_id', $request->branch_id)->first();
        $dept_id       = $employee->branch_id;
//        $dept_id = $employee->dept_id;
        $itemnames     = null;
        $itemsin       = null;
        $available     = false;
        $loggedin_user = Auth::id();

        foreach ($request->inv_id as $id) {
            $inventory = Inventory::find($id);
            $budget    = Budget::where('dept_id', $dept_id)->where('year_id', $inventory->year_id)->whereIn('id', $request->budget_id)->get();
            if (empty($budget)) {
                return redirect()->back()->with('msg', 'Budget not available in this employee`s department');
            }
            $budgets = Budget::where('dept_id', $dept_id)->where('year_id', $inventory->year_id)->where('subcategory_id', $inventory->subcategory_id)->whereIn('id', $request->budget_id)->get();
            if (count($budgets) == 0) {
                $itemnames .= $inventory->subcategory->sub_cat_name . ', ';
            } else {
                foreach ($budgets as $b) {
                    if ($b->consumed < $b->qty) {
                        $budget = $b;
                        break;
                    } else {
                        $budget = $b;
                    }
                }
                if ($budget->consumed >= $budget->qty) {
                    $itemnames .= $inventory->subcategory->sub_cat_name . ', ';
                } else {
                    $b_fields     = array(
                        'consumed'  => $budget->consumed + 1,
                        'remaining' => $budget->remaining - 1
                    );
                    $available    = true;
                    $itemsin      .= $inventory->subcategory->sub_cat_name . ', ';
                    $b_fields     = array(
                        'consumed'  => $budget->consumed + 1,
                        'remaining' => $budget->remaining - 1
                    );
                    $b_update     = Budget::where('id', $budget->id)->update($b_fields);
                    $update       = Inventory::where('id', $inventory->id)->update(['issued_to' => $request->employee_code, 'issued_by' => $loggedin_user, 'status' => 3, 'devicetype_id' => 3, 'branch_id' => $request->branch_id, 'branch_name' => $request->branch_name, 'department_id' => $request->branch_id]);
                    $insert       = Issue::create(['employee_id' => $request->employee_code, 'inventory_id' => $id, 'year_id' => $inventory->year_id, 'remarks' => $request->remarks]);
                    $insert_issue = InventoryIssueRecord::create([
                        'employee_id'     => $employee_data->id,
                        'employee_code'   => $request->employee_code,
                        'inventory_id'    => $id,
                        'year_id'         => $inventory->year_id,
                        'received_status' => 0,
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        $itemnames = rtrim($itemnames, ", ");
        $itemsin   = rtrim($itemsin, ", ");

        if ($available == true && $itemnames == null) {
            $data       = [
                'emp_id'         => $employee_data->id,
                'emp_code'       => $employee_data->emp_code,
                'emp_email'      => $employee_data->email,
                'emp_name'       => $employee_data->name,
                'email_message'  => 'Inventory has been issued.',
                'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                'issue_PK'       => $insert_issue->id,
                'make'           => Makee::find($inventory->make_id)['make_name'],
                'product_sn'     => $inventory->product_sn,
                'subcategory'    => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
            ];
            $send_email = EmailController::SendUserMail($data);
            $log        = SystemLogs::Add_logs('issues', $request->all(), 'insert');
            return redirect()->back()->with('msg', 'Selected inventory issued to ' . $employee_data->name);
        } else if ($available == true && $itemnames != null) {
            return redirect()->back()->with('msg', $itemsin . ' issued to ' . $employee_data->name . ', ' . $itemnames . ' not available in budget!');
        } else {
            return redirect()->back()->with('msg', 'Budget not available for selected inventory');
        }
    }

    public function transfer_inventory()
    {
        $inventory = Inventory::whereNotNull('issued_to')->where('devicetype_id', '!=', 5)->orderBy('id', 'desc')->count();
//        dd($inventory);
//        foreach ($inventory as $inv) {
//            $user = Employee::where('emp_code', $inv->issued_to)->first();
//            if ($user) {
//                $inv['user'] = $user;
//            }
//        }
        return view('transfer_inventory', ['inventories' => $inventory]);
    }

    public function filter_inventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_employee_code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $inventory = Inventory::where('issued_to', $request->from_employee_code)->where('devicetype_id', '!=', 5)->orderBy('id', 'desc')->get();
        foreach ($inventory as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
        }
        $emp_dep = EmployeeBranch::where('emp_code', $request->from_employee_code)->get();
        $emp     = Employee::where('emp_code', $request->from_employee_code)->first();
        return view('transfer_inventory', ['inventories' => $inventory, 'filter' => 1, 'from_emp' => $emp, 'emp_dep' => $emp_dep]);
    }

    public function submitt_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inv_id'           => 'required',
            'to_employee_code' => 'required',
            'branch'           => 'required',
            'branch_name'      => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $to_employee = Employee::where('emp_code', $request->to_employee_code)->first();
        if (!$to_employee) {
            return redirect()->back()->with('to_emp_code', 'to employee code does not exists');
        }
        $loggedin_user = Auth::id();
        foreach ($request->inv_id as $id) {
            $update = Inventory::where('id', $id)->update(['issued_to' => $request->to_employee_code, 'department_id' => $request->branch, 'branch_id' => $request->branch, 'branch_name' => $request->branch_name, 'issued_by' => $request->$loggedin_user]);
            $issue  = Issue::where('inventory_id', $id)->update(['employee_id' => $request->to_employee_code]);
            $insert = Transfer::create(['from_employee_id' => $request->from_employee_code, 'to_employee_id' => $request->to_employee_code, 'inventory_id' => $id, 'remarks' => $request->remarks]);
            $log    = SystemLogs::Add_logs('transfers', [Inventory::find($id), $request->all()], 'insert');
        }
        return redirect('transfer_inventory')->with('msg', 'Inventory transfered to ' . $to_employee->name);
    }

    public function update_transfer_inventory_request(Request $request)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'id'      => 'required',
            'status'  => 'required',
            'remarks' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $transferInventoryRequest = TransferInventoryRequest::find($request->id);
//        dd($transferInventoryRequest);
        if (!empty($transferInventoryRequest) && $transferInventoryRequest->status == 'pending' && $request->status == 'transferred') {
            $to_employee       = Employee::where('emp_code', $transferInventoryRequest->to_employee_id)->first();
            $to_employeeBranch = EmployeeBranch::where('emp_code', $transferInventoryRequest->to_employee_id)->first();
//           dd( $to_employeeBranch);
            if (!$to_employee) {
                return redirect()->back()->with('to_emp_code', 'to employee code does not exists');
            }
            $loggedin_user = Auth::id();
            foreach (json_decode($transferInventoryRequest->inventory_list) as $id) {
                $update = Inventory::where('id', $id)->update(['issued_to' => $transferInventoryRequest->to_employee_id, 'department_id' => $to_employeeBranch->branch_id, 'branch_id' => $to_employeeBranch->branch_id, 'branch_name' => $to_employeeBranch->branch_name, 'issued_by' => $loggedin_user]);
                $issue  = Issue::where('inventory_id', $id)->update(['employee_id' => $transferInventoryRequest->to_employee_id]);
                $insert = Transfer::create(['from_employee_id' => $request->from_employee_code, 'to_employee_id' => $transferInventoryRequest->to_employee_id, 'inventory_id' => $id, 'remarks' => $request->remarks]);
                $log    = SystemLogs::Add_logs('transfers', [Inventory::find($id), $request->all()], 'insert');
            }
            $transferInventoryRequest->status   = $request->status;
            $transferInventoryRequest->comments = $request->remarks;
            $transferInventoryRequest->save();
            return redirect('list_transfer_inventory_request')->with('msg', 'Inventory transfered to ' . $to_employee->name);
        }
        if ($request->status == 'rejected') {
            $transferInventoryRequest->status   = $request->status;
            $transferInventoryRequest->comments = $request->remarks;
            $transferInventoryRequest->save();
            return redirect('list_transfer_inventory_request')->with('msg', 'Request rejected!');
        } else {
            return redirect('list_transfer_inventory_request')->with('msg', 'Invalid data!');
        }
    }

    public function transfer_inventory_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_employee_code' => 'required',
            'to_employee_code'   => 'required',
            'to_name'            => 'required',
            'to_location'        => 'required',
            'to_email'           => 'required',
            'inventoryId'        => 'required',
            'remarks'            => 'nullable'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $transferInventory                       = new TransferInventoryRequest();
        $transferInventory->from_employee_id     = $request->from_employee_code;
        $transferInventory->to_employee_id       = $request->to_employee_code;
        $transferInventory->to_employee_name     = $request->to_name;
        $transferInventory->to_employee_location = $request->to_location;
        $transferInventory->to_employee_email    = $request->to_email;
        $transferInventory->inventory_list       = json_encode($request->inventoryId);
        $transferInventory->remarks              = $request->remarks ?? '';
        $transferInventory->status               = 'pending';

        $transferInventory->save();

        return redirect('list_transfer_inventory_request')->with('msg', 'Request Submitted!');
    }


    public function return_inventory()
    {
        $inventory = Inventory::whereNotNull('issued_to')->orderBy('id', 'desc')->get();
//        foreach ($inventory as $inv) {
//            $user = Employee::where('emp_code', $inv->issued_to)->first();
//            if ($user) {
//                $inv['user'] = $user;
//            }
//        }
        return view('return_inventory', ['inventories' => $inventory]);
    }

    public function show_transfer_inventory_request()
    {
        $inventory = TransferInventoryRequest::orderBy('id', 'DESC')->get();

        return view('transfer_inventory_request', ['users' => $inventory]);
    }

    public function filter_return(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $inventory = Inventory::where('issued_to', $request->employee_code)->orderBy('id', 'desc')->get();

        foreach ($inventory as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
        }
        $emp = Employee::where('emp_code', $request->employee_code)->first();

        $emp_branch = EmployeeBranch::select(DB::raw('group_concat(branch_id) as branch_id'),
            DB::raw('group_concat(branch_name) as branch_name'),
            'emp_code')
            ->where('emp_code', $request->employee_code)
            ->groupBy('emp_code')
            ->get();
        $branch     = explode(',', $emp_branch[0]->branch_id);

        foreach ($branch as $branch_data) {
            $emp_data[] = EmployeeBranch::where('branch_id', $branch_data)->first();

        }
        return view('return_inventory', ['inventories' => $inventory, 'filter' => 1, 'emp_code' => $emp, 'employee_dept' => $emp_data[0]->branch_name]);
    }

    public function submitt_return(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inv_id'        => 'required',
            'employee_code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        foreach ($request->inv_id as $id) {
//            , 'status' => '4'
            $update = Inventory::where('id', $id)->update([
                'current_location' => NULL,
                'issued_to'        => NULL,
                'issued_by'        => NULL,
                'current_consumer' => NULL,
                'department_id'    => NULL,
                'branch_id'        => NULL,
                'branch_name'      => NULL,
                'remarks'          => NULL,
                'devicetype_id'    => '4'
            ]);
            $issue  = Issue::where('inventory_id', $id)->first();
            if (!empty($issue)) {
                $issue->delete();
            }
            $insert = Rturn::create(['employee_id' => $request->employee_code, 'inventory_id' => $id, 'remarks' => $request->remarks]);
            $log    = SystemLogs::Add_logs('returns', [Inventory::find($id), $request->all()], 'insert');
        }
        return redirect('return_inventory')->with('msg', 'Inventory Returned!');
    }

    public function repair()
    {
        $inventory  = Inventory::orderBy('id', 'desc')->select('id', 'product_sn')->get();
        $categories = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        return view('repair_inventory', ['categories' => $categories, 'inventories' => $inventory]);
    }

    public function repair_inventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id'            => 'required',
            'date'               => 'required',
            'actual_price_value' => 'required',
            'price_value'        => 'required',
            'category_id'        => 'required',
            'subcategory_id'     => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $fields                       = $request->all();
        $fields['price_value']        = str_replace(",", "", $fields['price_value']);
        $fields['actual_price_value'] = str_replace(",", "", $fields['actual_price_value']);
        $repair                       = Repairing::create($fields);
        if ($repair) {
            $log = SystemLogs::Add_logs('repairings', $request->all(), 'insert');
            return redirect()->back()->with('msg', 'Repairing asset Added Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not add repairing asset, Try Again!');
        }
    }

    public function repair_items()
    {
        $repairing_items = Repairing::all();
        $category        = Category::all();
        return view('repairing_items', ['repairing_items' => $repairing_items, 'categories' => $category]);
    }

    public function edit_repair_items($id)
    {
        $repairing_item = Repairing::find($id);
        $category       = Category::all();
        return view('edit_repair_items', ['categories' => $category, 'repairing_item' => $repairing_item]);
    }

    public function edit_transfer_inventory_request($id)
    {
        $transferInventoryRequest = TransferInventoryRequest::find($id);
        return view('edit_transfer_inventory_request', ['request' => $transferInventoryRequest]);
    }

    public function update_asset_repair(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'item_id'            => 'required',
            'date'               => 'required',
            'actual_price_value' => 'required',
            'price_value'        => 'required',
            'category_id'        => 'required',
            'subcategory_id'     => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $update = Repairing::where('id', $id)->update([
            'category_id'        => $request->category_id,
            'subcategory_id'     => $request->subcategory_id,
            'item_id'            => $request->item_id,
            'date'               => $request->date,
            'remarks'            => $request->remarks,
            'actual_price_value' => str_replace(",", "", $request->actual_price_value),
            'price_value'        => str_replace(",", "", $request->price_value)
        ]);
        if ($update) {
            $log = SystemLogs::Add_logs('repairings', Repairing::find($id), 'update');
            return redirect()->back()->with('msg', 'Asset Repair Updated Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not update asset repair, Try Again!');
        }
    }

    public function pendings()
    {
        $inventory = Inventory::where('status', 0)->orderBy('id', 'desc')->get();
        return view('addtogrn', ['inventories' => $inventory]);
    }

    public function pending_gins()
    {
        $inventory = Inventory::where('status', 3)->orderBy('id', 'desc')->get();
        return view('addtogin', ['inventories' => $inventory]);
    }

    public function model_by_make($id)
    {
        $get = Modal::where('make_id', $id)->where('status', 1)->orderBy('model_name', 'asc')->get();
        return $get;
    }

    public function subcat_by_category($id)
    {
        $get = Subcategory::where('category_id', $id)->where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        return $get;
    }

    public function getEmployeeByBranchId($id)
    {
        $get = Employee::where('dept_id', $id)->orderBy('name', 'ASC')->get();
        return $get;
    }

    public function linkedsubcat_by_category($id)
    {
        $get = LinkedSubcategory::where('subcategory_id', $id)->get();
        return $get;
    }

    public function get_year_by_type($id)
    {
        $data_array = array();
        $data       = Budgetitem::where('type_id', $id)->orderBy('year_id', 'asc')->get();
        foreach ($data->unique('year_id') as $year) {
            $find = Year::where('id', $year->year_id)->where('is_current_year', 1)->get();
            foreach ($find as $year_data) {
                $data_array[] = $year_data;
            }
        }
        return $data_array;
    }

    public function get_year_by_type_SLA($id)
    {
        $data_array = array();
        $data       = SLA::where('type_id', $id)->orderBy('year_id', 'asc')->get();
        foreach ($data->unique('year_id') as $year) {
            $find = Year::where('id', $year->year_id)->where('is_current_year', 1)->get();
            foreach ($find as $year_data) {
                $data_array[] = $year_data;
            }
        }
        return $data_array;
    }

    public function get_subcat_by_year($id, $type_id)
    {
        $data_arr = array();
        $data     = Budgetitem::where('year_id', $id)->where('type_id', $type_id)->where('category_id', 163)->orderBy('id', 'asc')->get();
        foreach ($data->unique('subcategory_id') as $sub_cat) {
            $find = Subcategory::where('id', $sub_cat->subcategory_id)->where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
            foreach ($find as $sub_category) {
                $data_arr[] = $sub_category;
            }
        }
        return $data_arr;
    }

    public function get_vendor_by_sub_cat_year($id, $type_id, $year_id)
    {
        $data_arr     = array();
        $data         = SLA::where('subcategory_id', $id)->where('year_id', $year_id)->where('type_id', $type_id)->where('category_id', 163)->orderBy('id', 'asc')->first();
        $vendor       = Vendor::find($data['vendor_id']);
        $vendor_arr[] = $vendor;
        return $vendor_arr;
    }

//    public function get_vendor_by_sub_cat_year_invoice($id,$type_id,$year_id,$cat_id)
//    {
//        $data_arr = array();
//        $data = Budgetitem::where('subcategory_id', $id)->where('year_id', $year_id)->where('type_id', $type_id)->where('category_id', $cat_id)->orderBy('id', 'asc')->first();
//        foreach ($data->unique('vendor_id') as  $vendors){
//            $find = Vendor::where('id',$vendors->vendor_id)->where('status',1)->orderBy('id', 'asc')->get();
//            foreach ($find as $vendor){
//                $data_arr[] = $vendor;
//            }
//        }
//        return $data_arr;
//    }


    public function get_subcat_by_year_SLA($id, $type_id)
    {
        $data_arr = array();
        $data     = SLA::where('year_id', $id)->where('type_id', $type_id)->where('category_id', 163)->orderBy('id', 'asc')->get();
        foreach ($data->unique('subcategory_id') as $sub_cat) {
            $find = Subcategory::where('id', $sub_cat->subcategory_id)->where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
            foreach ($find as $sub_category) {
                $data_arr[] = $sub_category;
            }
        }
        return $data_arr;
    }

    public function get_cat_by_year($id, $type_id)
    {
        $data_arr = array();
        $data     = Budgetitem::where('year_id', $id)->where('type_id', $type_id)->orderBy('id', 'asc')->get();
        foreach ($data->unique('category_id') as $cat) {
            $find = Category::where('id', $cat->category_id)->where('status', 1)->orderBy('id', 'asc')->get();
            foreach ($find as $category) {
                $data_arr[] = $category;
            }
        }
        return $data_arr;
    }

    public function get_product_sn()
    {
        $data = Inventory::select('id', 'product_sn')->distinct()->get();
        return $data;
    }


    public function get_make_model_by_psn($id)
    {
        $data_arr  = array();
        $inventory = Inventory::find($id);
        $make      = Makee::find($inventory->make_id);
        $model     = Modal::find($inventory->model_id);
        $employee  = Employee::where('emp_code', $inventory->issued_to)->first();
        $data_arr  = [
            'make_id'      => $inventory->make_id ?? null,
            'model_id'     => $inventory->model_id ?? null,
            'issued_to_id' => $inventory->issued_to ?? null,
            'make_name'    => $make->make_name ?? null,
            'model_name'   => $model->model_name ?? null,
            'issued_to'    => $employee->name ?? null,
        ];
        return $data_arr;
    }

    public function get_subcat_by_cat_year($id, $type_id, $year_id)
    {
        $data_arr = array();
        $data     = Budgetitem::where('category_id', $id)->where('year_id', $year_id)->where('type_id', $type_id)->orderBy('id', 'asc')->get();
//        dd(count($data->unique('subcategory_id')));
        foreach ($data->unique('subcategory_id') as $subcat) {
            $find = Subcategory::where('id', $subcat->subcategory_id)->where('status', 1)->orderBy('id', 'asc')->get();
            foreach ($find as $subcategory) {
                $data_arr[] = $subcategory;
            }
        }
        return $data_arr;
    }

    public function get_catBy_year_invoice($year_id)
    {
        $data_arr = array();
        $data     = Invoicerelation::where('year_id', $year_id)->select('category_id')->orderBy('id', 'asc')->get();
        foreach ($data->unique('category_id') as $cat) {
            $find = Category::where('id', $cat->category_id)->orderBy('id', 'asc')->get();
            foreach ($find as $category) {
                $data_arr[] = $category;
            }
        }
        return $data_arr;
    }

    public function get_subcatBy_year_invoice($category_id, $year_id)
    {
        $data_arr = array();
        $data     = Invoicerelation::where('year_id', $year_id)->where('category_id', $category_id)->select('subcategory_id')->orderBy('id', 'asc')->get();
        foreach ($data->unique('subcategory_id') as $subcat) {
            $find = Subcategory::where('id', $subcat->subcategory_id)->orderBy('id', 'asc')->get();
            foreach ($find as $subcategory) {
                $data_arr[] = $subcategory;
            }
        }
        return $data_arr;
    }

    public function get_vendorBy_year_invoice($subcategory_id, $category_id, $year_id)
    {
        $data_arr = array();
        $data     = Invoicerelation::where('year_id', $year_id)->where('category_id', $category_id)->where('subcategory_id', $subcategory_id)->select('vendor_id')->orderBy('id', 'asc')->get();
        foreach ($data->unique('vendor_id') as $vendor) {
            $find = Vendor::where('id', $vendor->vendor_id)->orderBy('id', 'asc')->get();
            foreach ($find as $vend) {
                $data_arr[] = $vend;
            }
        }
        return $data_arr;
    }


//    public function get_vendor_by_subcat($id,$type_id,$year_id)
//    {
//        $data_arr = array();
//        $data = Budgetitem::where('subcategory_id', $id)->where('type_id', $type_id)->where('year_id', $year_id)->where('category_id', 163)->orderBy('id', 'asc')->get();
//        foreach ($data->unique('subcategory_id') as $vendor){
//            $find = Subcategory::where('id',$vendor->vendor_id)->where('status',1)->orderBy('sub_cat_name', 'asc')->get();
//            foreach ($find as $sub_category){
//                $data_arr[] = $sub_category;
//            }
//        }
//        return $data_arr;
//    }

    public function get_category()
    {
        $get = Category::all();
        return $get;
    }

    public function get_make()
    {
        $get = Makee::all();
        return $get;
    }

    public function pkr_by_year($id)
    {
        $get = Dollar::where('year_id', $id)->first();
        return $get;
    }

    function check_issue_email()
    {
        date_default_timezone_set('Asia/karachi');
        $before_seven = Carbon::now()->subDays(6)->format('Y-m-d');
        $issue        = InventoryIssueRecord::where('issued_at', $before_seven)->where('received_status', 0)->get();
        if (count($issue) > 0) {
            foreach ($issue as $issue_data) {
                $user = Employee::find($issue_data->employee_id);
                if ($user != null) {
                    $inventory  = Inventory::find($issue_data->inventory_id);
                    $email_data = [
                        'emp_id'         => $issue_data->employee_id,
                        'emp_code'       => $issue_data->employee_code,
                        'emp_email'      => $user->email,
                        'emp_name'       => $user->name,
                        'email_message'  => 'Inventory has been issued.',
                        'inventory_name' => Modal::find($inventory->model_id)['model_name'],
                        'issue_PK'       => $issue_data->id,
                        'make'           => Makee::find($inventory->make_id)['make_name'],
                        'product_sn'     => $inventory->product_sn,
                        'subcategory'    => Subcategory::find($inventory->subcategory_id)['sub_cat_name'],
                    ];
                    if (isset($user)) {
                        $data = array(
                            'user'       => $email_data['emp_name'],
                            'message'    => $email_data['email_message'],
                            'data'       => $email_data,
                            'url'        => Url::signedRoute('received-email', ['issued_PK' => $issue_data->id, 'emp_id' => $issue_data->employee_id, 'status' => 'yes']),
                            'url_reject' => Url::signedRoute('received-email', ['issued_PK' => $issue_data->id, 'emp_id' => $issue_data->employee_id, 'status' => 'no']),
                        );
                        Mail::send('emails.user_email', ['data' => $data], function ($message) use ($user) {
                            $message->to('muhammadirfan5891@gmail.com')->subject
                            ('Inventory Verification');
                            $message->from('itstore@efulife.com', 'Support IT Store');
                        });
                        $log = SystemLogs::Add_logs('email', $data, 'email');
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    function inventory_flow(Request $request)
    {
        date_default_timezone_set('Asia/karachi');
        $data                = array();
        $data['inventories'] = Inventory::where('devicetype_id', '!=', 5)->orderBy('id', 'asc')->get();
        $data['filters']     = array();
        $arr                 = array();
//        $main = array();
        $transfer_arr = array();
        $return_arr   = array();
        $dispose_arr  = array();
        $issue_arr    = array();
        if (empty($request->all())) {
            $arr = array();
        } else {
            $data['inventory'] = Inventory::findorfail($request->inventory_id);
            $data['issue']     = Issue::where('inventory_id', $data['inventory']->id)->get();
            foreach ($data['issue'] as $issue) {
                $arr['inv']           = $data['inventory'];
                $issue->employee_name = Employee::where('emp_code', $issue->employee_id)->first()['name'];
                $issue->issued_at     = $issue->created_at;
                $issue->action        = "issue";
                array_push($issue_arr, $issue);
//                array_push($main,$issue);
                $arr['inv']['issue'] = $issue_arr;
            }
            $data['rturn'] = Rturn::where('inventory_id', $data['inventory']->id)->orderBy('created_at', 'asc')->get();
            foreach ($data['rturn'] as $rturn) {
                $rturn->return_by_name = Employee::where('emp_code', $rturn->employee_id)->first()['name'];
                $rturn->action         = "return";
                array_push($return_arr, $rturn);
//                array_push($main,$rturn);
                $arr['inv']['rturn'] = $return_arr;
            }
            $data['transfer'] = Transfer::where('inventory_id', $data['inventory']->id)->orderBy('created_at', 'asc')->get();
            foreach ($data['transfer'] as $transfer) {
                $transfer->to_employee_name   = Employee::where('emp_code', $transfer->to_employee_id)->first()['name'];
                $transfer->from_employee_name = Employee::where('emp_code', $transfer->from_employee_id)->first()['name'];
                $transfer->action             = "transfer";
                array_push($transfer_arr, $transfer);
//                array_push($main, $transfer);
                $arr['inv']['transfer'] = $transfer_arr;
            }
            $data['dispose'] = Disposal::where('inventory_id', $data['inventory']->id)->orderBy('created_at', 'asc')->get();
            foreach ($data['dispose'] as $dispose) {
                $dispose->status = Disposalstatus::find($dispose->disposalstatus_id)['d_status'];
                $dispose->action = "dispose";
                array_push($dispose_arr, $dispose);
//                array_push($main, $dispose);
                $arr['inv']['dispose'] = $dispose_arr;
            }
        }
//        $sorting = collect($main)->sortBy('created_at')->all();
//        $array = array();
//        foreach ($sorting as $data){
//            $array[$data->action]['action'] = $data->action;
//            $array[$data->action]['issue_employee_name'] = $data->employee_name;
//            $array[$data->action]['issued_at'] = $data->issued_at;
//        }
//        dd($array);
        return view('inventory_flow', $data, compact('arr'));
    }

    public function assign_priviliges_view()
    {
        $users      = User::all();
        $priviliges = Privilige::all();
        return view('assign_priviliges', compact('users', 'priviliges'));
    }

    public function assign_priviliges()
    {
        $users      = User::all();
        $priviliges = Privilige::all();
        return view('assign_privilige_new', compact('users', 'priviliges'));
    }

    public function replicate_priviliges()
    {
        $users      = User::orderBy('name', 'ASC')->get();
        $priviliges = Privilige::all();
        return view('replicate_previliges', compact('users', 'priviliges'));
    }

    public function get_user_except($user_id)
    {
        $user = User::all()->except($user_id);
        if ($user != null) {
            return $user;
        }
        return false;
    }

    public function run_query()
    {

        $product_sn = [
            'sn_no'    => [
                'HA15HMBP200333X',
                '101INPTA8080',
                'UNN182330978',
                '7039U3CY05431',
                'HA15HMBP200451W',
                'V1NK176',
                'VNCX742735',
                'Z7DQB8GC4B007VE',
                'KLJU15154',
                'KLJU40498',
                'FHK1428F05N',
                'FCZ113170JY',
                'FHK1335F0FQ',
                '????122271HE',
                'FHK1428F060',
                'FCZ100472BY',
                'FCZ111671GH',
                'FD01240ZOMS',
                'AU3A0618012861',
                'T6Q124321281',
                '2178003R08641',
                '2197024014613',
                '216C924007151',
                '2197024014610',
                '4BKPL02',
                'WB10108155'],
            'emp_code' => [
                '3',
                '3',
                '3',
                '3',
                '7186',
                '7186',
                '5',
                '5',
                '4167',
                '4167',
                '1359',
                '1359',
                '1359',
                '8041',
                '8041',
                '7767',
                '7767',
                '9403',
                '9403',
                '9403',
                '9403',
                '252',
                '1',
                '1',
                '4167',
                '4',
                '183',
                '3'
            ]
        ];
        // done 1604280046


        foreach ($product_sn['sn_no'] as $key => $data) {
            $inv = Inventory::where('product_sn', $data)->first();
            if ($inv) {
                $emp = Employee::where('emp_code', $product_sn['emp_code'][$key])->first();
                if ($emp) {
                    $update       = $inv->update(['issued_to' => $emp->emp_code, 'issued_by' => '81', 'devicetype_id' => 3, 'branch_id' => $emp->branch_id, 'branch_name' => $emp->department, 'department_id' => $emp->dept_id]);
                    $insert       = Issue::create(['employee_id' => $emp->emp_code, 'inventory_id' => $inv->id, 'year_id' => '41', 'remarks' => "Inventory updated after shuffling of users and brand admins in lahore liberty office"]);
                    $insert_issue = InventoryIssueRecord::create([
                        'employee_id'     => $emp->id,
                        'employee_code'   => $emp->emp_code,
                        'inventory_id'    => $inv->id,
                        'year_id'         => '41',
                        'received_status' => 0,
                        'issued_at'       => date('Y-m-d'),
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                $emp = Employee::where('emp_code', $product_sn['emp_code'][$key])->first();
                if ($emp) {
                    $inventory             = new Inventory();
                    $inventory->product_sn = $data;
                    $inventory->issued_to  = $emp->emp_code;
                    $inventory->year_id    = 41;
                    $inventory->save();
                    $update       = $inventory->update(['issued_to' => $emp->emp_code, 'issued_by' => '81', 'devicetype_id' => 3, 'branch_id' => $emp->branch_id, 'branch_name' => $emp->department, 'department_id' => $emp->dept_id]);
                    $insert       = Issue::create(['employee_id' => $emp->emp_code, 'inventory_id' => $inventory->id, 'year_id' => '41', 'remarks' => "Inventory updated after shuffling of users and brand admins in lahore liberty office"]);
                    $insert_issue = InventoryIssueRecord::create([
                        'employee_id'     => $emp->id,
                        'employee_code'   => $emp->emp_code,
                        'inventory_id'    => $inventory->id,
                        'year_id'         => '41',
                        'received_status' => 0,
                        'issued_at'       => date('Y-m-d'),
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        dd("Issued Successfully");


    }


//    public function barcode(Request $request){
//        date_default_timezone_set('Asia/karachi');
//        $data = array();
//        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
//        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
//        $data['locations'] = Location::orderBy('location', 'asc')->get();
//        $data['invtypes'] = Inventorytype::where('status', 1)->orderBy('inventorytype_name', 'asc')->get();
//        $data['makes'] = Makee::where('status', 1)->orderBy('make_name', 'asc')->get();
//        $data['stores'] = Store::orderBy('store_name', 'asc')->get();
//        $data['itemnatures'] = Itemnature::where('status', 1)->orderBy('itemnature_name', 'asc')->get();
//        $data['vendors'] = Vendor::orderBy('vendor_name', 'asc')->get();
//        $data['years'] = Year::orderBy('year', 'asc')->get();
//        $data['filters'] = array();
//        $invs = array();
//        if (empty($request->all())) {
//            $data['inventories'] = array();
//        } else {
//            $fields = array_filter($request->all());
//            unset($fields['_token']);
//            $fields['issued_to'] = null;
//            $data['filters'] = $fields;
//            if (isset($fields['from_date']) && isset($fields['to_date'])) {
//                $from = $fields['from_date'];
//                $to = strtotime($fields['to_date'] . '+1 day');
//                unset($fields['from_date']);
//                unset($fields['to_date']);
//                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1','5'])->where('product_sn','not like' ,"%-CR-%" )->whereBetween('created_at', [$from, date('Y-m-d', $to)])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
//            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
//                $from = $fields['from_date'];
//                unset($fields['from_date']);
//                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1','5'])->where('product_sn','not like' ,"%-CR-%" )->where('carry_forward_status_id', '!=', 3)->whereBetween('created_at', [$from, date('Y-m-d', strtotime('+1 day'))])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
//            } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
//                $to = strtotime($fields['to_date'] . '+1 day');
//                unset($fields['to_date']);
//                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1','5'])->where('product_sn','not like' ,"%-CR-%" )->where('carry_forward_status_id', '!=', 3)->whereBetween('created_at', ['', date('Y-m-d', $to)])
//                    ->whereNotIn('status', [0])
//                    ->orderBy('id', 'desc')->get();
//            } else {
//                $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1','5'])->where('product_sn','not like' ,"%-CR-%" )->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
//            }
//            foreach ($invs as $inv) {
//                $inv->added_by = User::find($inv->added_by);
//                $inv->return_remarks = Rturn::where('inventory_id',$inv->id)->select('remarks')->latest('created_at')->first();
//            }
//            $data['inventories'] = $invs;
//        }
//        return view('generate_barcode', $data);
//    }


}
