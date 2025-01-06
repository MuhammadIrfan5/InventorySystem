<?php

namespace App\Http\Controllers;

use App\EmployeeBranch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BudgetExport;
use App\Exports\ItemsExport;
use App\Category;
use App\Subcategory;
use App\Inventory;
use App\InventoryInvoice;
use App\Invoicerelation;
use App\User;
use App\Year;
use App\Dollar;
use App\Type;
use App\Budgetitem as Budget;
use App\Employee;
use App\SystemLogs;
use Illuminate\Support\Arr;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:add_budget', ['only' => ['store']]);
        $this->middleware('permission:swapping', ['only' => ['swapping']]);
        $this->middleware('permission:budget_transfer', ['only' => ['budget_transfer']]);
        $this->middleware('permission:transfer_product_sn', ['only' => ['transfer_product_sn', 'transfer_product_sn2']]);
        $this->middleware('permission:capexOpexSummaryDollar', ['only' => ['summaryDollar']]);
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'sub_cat_id' => 'required|not_in:0',
            'dept_id' => 'required|not_in:0',
            'dept_branch_type' => 'required|not_in:0',
            'type_id' => 'required|not_in:0',
            'year_id' => 'required|not_in:0',
            'unit_dollar' => 'required',
            'unit_pkr' => 'required',
            'qty' => 'required',
            'total_dollar' => 'required',
            'total_pkr' => 'required',
            'budget_nature' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $year = Year::where('id', $request->year_id)->first();
        if ($year->locked == 1) {
            return redirect()->back()->with('msg', 'Sorry, You can not add item in Locked Budget');
        }
        $fields = array(
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->sub_cat_id,
            'dept_id' => $request->dept_id,
            'dept_branch_type' => $request->dept_branch_type,
            'department' => $request->department,
            'type_id' => $request->type_id,
            'year_id' => $request->year_id,
            'description' => $request->description,
            'unit_price_dollar' => str_replace(",", "", $request->unit_dollar),
            'unit_price_pkr' => str_replace(",", "", $request->unit_dollar) * str_replace(",", "", $request->unit_pkr),
            'qty' => $request->qty,
            'consumed' => 0,
            'remaining' => $request->qty,
            'total_price_dollar' => str_replace(",", "", $request->total_dollar),
            'total_price_pkr' => str_replace(",", "", $request->total_pkr),
            'remarks' => $request->remarks,
            'budget_nature' => $request->budget_nature
        );
        $create = Budget::create($fields);
        if ($create) {
            $log = SystemLogs::create([
                'user_id' => Auth()->id(),
                'email' => Auth::user()->email,
                'table_name' => 'budgetitems',
                'meta_value' => json_encode($fields),
                'action_perform' => 'insert',
                'ip' => $request->ip(),
                'user_agent' => $request->header('user-agent'),
                'url' => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Budget Item Added Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not add budget item, Try Again!');
        }
    }

    public function show($id)
    {
        $data = array();
        $budget = Budget::find($id);
        $budget->unit_price_dollar = number_format($budget->unit_price_dollar);
        $budget->unit_price_pkr = number_format($budget->unit_price_pkr);
        $budget->total_price_dollar = number_format($budget->total_price_dollar);
        $budget->total_price_pkr = number_format($budget->total_price_pkr);
        $data['budget'] = $budget;
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['types'] = Type::orderBy('type', 'asc')->get();
        $data['years'] = Year::where('is_budget_collection', 1)
//        ->where('locked', null)
            ->orderBy('year', 'asc')->get();
        $data['pkr'] = Dollar::where('year_id', $budget->year_id)->first();
        // echo "<pre>";
        // print_r($data);
        return view('edit_budget', $data);
    }

    public function update(Request $request, $id)
    {
        $bd = Budget::find($id);
        if ($bd->consumed == 0) {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|not_in:0',
                'sub_cat_id' => 'required|not_in:0',
                'dept_id' => 'required|not_in:0',
                'dept_branch_type' => 'required|not_in:0',
                'type_id' => 'required|not_in:0',
                'year_id' => 'required|not_in:0',
                'unit_dollar' => 'required',
                'unit_pkr' => 'required',
                'qty' => 'required',
                'total_dollar' => 'required',
                'total_pkr' => 'required',
                'budget_nature' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $fields = array(
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'subcategory_id' => $request->sub_cat_id,
                'dept_id' => $request->dept_id,
                'dept_branch_type' => $request->dept_branch_type,
                'department' => $request->department,
                'type_id' => $request->type_id,
                'year_id' => $request->year_id,
                'description' => $request->description,
                'unit_price_dollar' => str_replace(",", "", $request->unit_dollar),
                'unit_price_pkr' => str_replace(",", "", $request->unit_dollar) * str_replace(",", "", $request->unit_pkr),
                'qty' => $request->qty,
                'total_price_dollar' => str_replace(",", "", $request->total_dollar),
                'total_price_pkr' => str_replace(",", "", $request->total_pkr),
                'remarks' => $request->remarks,
                'budget_nature' => $request->budget_nature
            );
            if ($bd->qty != $request->qty) {
                $quantity = 0;
                $rem = 0;
                if ($bd->qty < $request->qty) {
                    $quantity = ($request->qty - $bd->qty);
                    $rem = $bd->remaining + $quantity;
                } else if ($bd->qty > $request->qty) {
                    $quantity = ($bd->qty - $request->qty);
                    $rem = $bd->remaining - $quantity;
                }
                $fields['remaining'] = $rem;
            }
        } else {
            $fields = array(
                'user_id' => Auth::id(),
                'description' => $request->description,
                'remarks' => $request->remarks
            );
        }
        $create = Budget::where('id', $id)->update($fields);
        if ($create) {
            $log = SystemLogs::create([
                'user_id' => Auth()->id(),
                'email' => Auth::user()->email,
                'table_name' => 'budgetitems',
                'meta_value' => json_encode(Budget::find($id)),
                'action_perform' => 'update',
                'ip' => $request->ip(),
                'user_agent' => $request->header('user-agent'),
                'url' => $request->fullUrl()
            ]);
            return redirect()->back()->with('msg', 'Budget Item Updated Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not update budget item, Try Again!');
        }
    }

    public function destroy($id)
    {
        $find = Budget::find($id);
        $find->deleted_by = Auth::id();
        $find->update();
        $log = SystemLogs::create([
            'user_id' => Auth()->id(),
            'email' => Auth::user()->email,
            'table_name' => 'budgetitems',
            'meta_value' => json_encode($find),
            'action_perform' => 'delete',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'url' => url()->full()
        ]);
        return $find->delete() ? redirect()->back()->with('msg', 'Budget Deleted Successfully!') : redirect()->back()->with('msg', 'Could not delete budget, Try Again!');
    }

    public function budget_by_year(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'year_id' => 'required|not_in:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $data = array();
        $data['years'] = Year::orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['budgets'] = Budget::where('year_id', $request->year_id)->where('category_id', $request->category_id)->get();
        $data['filter'] = Year::find($request->year_id);
        $data['filters'] = (object)array('catid' => $request->category_id, 'yearid' => $request->year_id);
        $data['selected_year'] = Year::find($request->year_id);
        return view('show_budget', $data);
    }

    public function budget_by_year_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'year_id' => 'required|not_in:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $data = array();
        $data['years'] = Year::orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        // $data['capex_budget'] = Budget::where('year_id', $request->year_id)->where('category_id',$request->category_id)->where('type_id',1)->get();
        // $data['opex_budget'] = Budget::where('year_id', $request->year_id)->where('category_id',$request->category_id)->where('type_id',2)->get();
        $data['filter'] = Year::find($request->year_id);
        $data['category_name'] = Category::find($request->category_id);
        $data['filters'] = (object)array('catid' => $request->category_id, 'yearid' => $request->year_id, 'year_name' => $data['filter']->year, 'category_name' => $data['category_name']->category_name);
        $data['capex_budget'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();

        $data['opex_budget'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id')
            ->get();
        return view('show_subcategory_budget', $data);
    }

    public function budget_by_year_category_adv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'year_id' => 'required|not_in:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $data = array();
        $data['years'] = Year::orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['filter'] = Year::find($request->year_id);
        $data['category_name'] = Category::find($request->category_id);
        $data['filters'] = (object)array('catid' => $request->category_id, 'yearid' => $request->year_id, 'year_name' => $data['filter']->year, 'category_name' => $data['category_name']->category_name);
//        dd($request->all());
        $data['capex_budget_items'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();

        $subcat_array_capex = array();
        foreach ($data['capex_budget_items'] as $get_sub) {
            array_push($subcat_array_capex, $get_sub->subcategory_id);
        }

        $data['capex_budget_inv'] = Inventory::select(DB::raw('group_concat(product_sn) as myproduct_sn'),
            DB::raw('group_concat(remarks) as inv_remarks'),
            'subcategory_id',
            DB::raw('COUNT(id) as consumed_qty'),
            DB::raw('SUM(item_price) as consumed_pkr'),
            DB::raw('SUM(dollar_rate) as dollar_rate'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->whereIn('subcategory_id', $subcat_array_capex)
            ->where('type_id', 1)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();

        $data['capex_budget_inv_rel'] = Invoicerelation::select(DB::raw('group_concat(product_sn) as myproduct_sn'),
            'subcategory_id',
            DB::raw('COUNT(id) as consumed_qty'),
            DB::raw('SUM(item_price) as consumed_pkr'),
            DB::raw('SUM(dollar_rate) as dollar_rate'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->whereIn('subcategory_id', $subcat_array_capex)
            ->where('type_id', 1)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();

        foreach ($data['capex_budget_inv'] as $key => $inv) {
            if ($inv != null) {
                $data['capex_budget_items'][$key]['consumed_qty'] = $inv->consumed_qty;
                $data['capex_budget_items'][$key]['inv_remarks'] = $inv->inv_remarks;
                $data['capex_budget_items'][$key]['consumed_pkr'] = $inv->consumed_pkr;
                $data['capex_budget_items'][$key]['myproduct_sn'] = $inv->myproduct_sn;
                $data['capex_budget_items'][$key]['dollar_rate'] = $inv->dollar_rate;
                if ($inv->consumed_qty != 0 && $inv->dollar_rate != '') {
                    $dollar_rate = $inv->consumed_pkr / ($inv->dollar_rate / $inv->consumed_qty);
                    $data['capex_budget_items'][$key]['dollar_amount'] = $dollar_rate;
                }
            }
        }

        $data['opex_budget_items'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id')
            ->get();

        $subcat_array_opex = array();
        foreach ($data['opex_budget_items'] as $get_sub) {
            array_push($subcat_array_opex, $get_sub->subcategory_id);
        }
        $data['opex_budget_inv'] = Inventory::select(DB::raw('group_concat(product_sn) as myproduct_sn'),
            DB::raw('group_concat(remarks) as myremarks'),
            'subcategory_id',
            DB::raw('COUNT(id) as consumed_qty'),
            DB::raw('SUM(item_price) as consumed_pkr'),
            DB::raw('SUM(dollar_rate) as dollar_rate'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 2)
            ->whereIn('subcategory_id', $subcat_array_opex)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();


        $data['opex_budget_inv_rel'] = Invoicerelation::select(DB::raw('group_concat(product_sn) as myproduct_sn'),
            'subcategory_id',
            DB::raw('COUNT(id) as consumed_qty'),
            DB::raw('SUM(item_price) as consumed_pkr'),
            DB::raw('SUM(dollar_rate) as dollar_rate'))
            ->where('year_id', $request->year_id)
            ->where('category_id', $request->category_id)
            ->whereIn('subcategory_id', $subcat_array_opex)
            ->where('type_id', 2)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();

        foreach ($data['opex_budget_inv'] as $key => $inv) {
            if ($inv != null) {
                $data['opex_budget_items'][$key]['consumed_qty'] = $inv->consumed_qty;
                $data['opex_budget_items'][$key]['inv_remarks'] = $inv->inv_remarks;
                $data['opex_budget_items'][$key]['consumed_pkr'] = $inv->consumed_pkr;
                $data['opex_budget_items'][$key]['myproduct_sn'] = $inv->myproduct_sn;
                $data['opex_budget_items'][$key]['dollar_rate'] = $inv->dollar_rate;
                if ($inv->dollar_rate != '') {
                    $dollar_rate = $inv->consumed_pkr / ($inv->dollar_rate / $inv->consumed_qty);
                    $data['opex_budget_items'][$key]['dollar_amount'] = $dollar_rate;
                }
            }
        }

//        foreach ($data['opex_budget_items'] as $key1 => $items) {
//            foreach ($data['opex_budget_inv'] as $key2 => $inv) {
//                $data['opex_budget_inv'][$key2]['myqty'] = ($items->myqty ?? "");
//                $data['opex_budget_inv'][$key2]['mydescription'] = ($items->mydescription ?? "");
//                $data['opex_budget_inv'][$key2]['remarks'] = ($items->remarks ?? "");
//                $data['opex_budget_inv'][$key2]['myunit_price_dollar'] = ($items->myunit_price_dollar ?? "");
//                $data['opex_budget_inv'][$key2]['myunit_price_pkr'] = ($items->myunit_price_pkr ?? "");
//                $data['opex_budget_inv'][$key2]['mytotal_price_dollar'] = ($items->mytotal_price_dollar ?? "");
//                $data['opex_budget_inv'][$key2]['mytotal_price_pkr'] = ($items->mytotal_price_pkr ?? "");
//            }
//        }


        return view('show_subcategory_budget_adv', $data);
    }

    public function budget_by_year_category_summary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prev_year_id' => 'required',
            'year_id' => 'required|not_in:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $prev_year_id = explode(',', $request->prev_year_id);
        $data = array();
        $year = array();
        // dd();
        $data['years'] = Year::orderBy('year', 'asc')->get();
        $data['filter'] = Year::find($request->year_id);
//        $data['filter_pre'] = Year::find($prev_year_id[0]);
        foreach ($prev_year_id as $pre_id) {
            $year[] = Year::find($pre_id);
        }
//        $data['category_name'] = Category::find($request->category_id);
        $data['filters'] = (object)array('yearid' => $request->year_id, 'year_name' => $data['filter']->year, 'prev_year_id' => $year, 'prev_year_name' => $year);
//        dd($data['filters']);
        $data['capex_budget_year'] = Budget::select(
            'category_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->year_id)
            ->where('type_id', 1)
            ->groupBy('category_id', 'year_id')
            ->orderBy('category_id')
            ->get();
        $data['capex_budget_prev'] = Budget::select(
            'category_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_prev_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->whereIn('year_id', $prev_year_id)
            ->where('type_id', 1)
            ->groupBy('category_id', 'year_id')
            ->orderBy('category_id')
            ->get();

        foreach ($data['capex_budget_year'] as $val) {
            foreach ($data['capex_budget_prev'] as $budget) {
                if ($val->category_id == $budget->category_id) {
                    $val->prev_year_budget_amount = $budget->myunit_prev_price_pkr;
                    $val->percentage = (($val->myunit_price_pkr - $budget->myunit_prev_price_pkr) / $val->myunit_price_pkr) * 100;
                }
            }
        }
        $data['opex_budget_year'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'category_id',
            'year_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->year_id)
            ->where('type_id', 2)
            ->groupBy('category_id', 'year_id')
            ->orderBy('category_id')
            ->get();


        $data['opex_budget_prev'] = Budget::select(
            'category_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
//            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(unit_price_pkr) as myunit_prev_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->whereIn('year_id', $prev_year_id)
            ->where('type_id', 2)
            ->groupBy('category_id', 'year_id')
            ->orderBy('category_id')
            ->get();

        foreach ($data['opex_budget_year'] as $val) {
            foreach ($data['opex_budget_prev'] as $budget) {
                if ($val->category_id == $budget->category_id) {
                    $val->prev_year_budget_amount = $budget->myunit_prev_price_pkr;
                    $val->percentage = (($val->myunit_price_pkr - $budget->myunit_prev_price_pkr) / $val->myunit_price_pkr) * 100;
                }
            }
        }

        $inv = array();
        $data['prev'] = collect($data['capex_budget_prev'])->merge($data['opex_budget_prev']);
        $data['categories_data'] = $data['prev']->unique('category_id');
        foreach ($data['categories_data'] as $cat_id) {
            $inv[] = Inventory::select('item_price', 'id')->whereIn('year_id', $prev_year_id)->where('category_id', $cat_id->category_id)->sum('item_price');
        }
        $data['actual_used'] = array_sum($inv) ? array_sum($inv) : 0;
        $data['dollar_rate'] = Dollar::where('year_id', $request->year_id)->first();

        return view('show_subcategory_budget_summary', $data);
    }

    public function budget_compare_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_year_id' => 'required',
            'from_year_id' => 'required',
            'category_id' => 'required|not_in:0'
        ]);
//        dd($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $data = array();
        $year = array();
        $data['years'] = Year::orderBy('year', 'asc')->get();
        $data['categories'] = Category::orderBy('category_name', 'asc')->get();
        $data['filter_to_year'] = Year::find($request->to_year_id);
        $data['filter_from_year'] = Year::find($request->from_year_id);
        $data['category_id'] = Category::find($request->category_id);
        $data['filters'] = (object)array('category_name' => $data['category_id']->category_name, 'category_id' => $request->category_id, 'to_year_id' => $request->to_year_id, 'from_year_id' => $request->from_year_id, 'to_year_name' => $data['filter_to_year']->year, 'from_year_name' => $data['filter_from_year']->year);
        $data['capex_budget_year'] = Budget::select(
            'subcategory_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as my_qty'),
            DB::raw('COUNT(id) as total_rows'),
            DB::raw('SUM(unit_price_dollar) as  my_unit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as unit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as total_price_dollar'),
            DB::raw('SUM(total_price_pkr) as total_price_pkr'))
            ->where('year_id', $request->to_year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id', 'asc')
            ->get();
        $data['capex_budget_from'] = Budget::select(
            'subcategory_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as year1_qty'),
            DB::raw('COUNT(id) as total_rows'),
            DB::raw('SUM(unit_price_dollar) as year1_unit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as unit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as total_price_dollar'),
            DB::raw('SUM(total_price_pkr) as total_price_pkr'))
            ->where('year_id', $request->from_year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id', 'asc')
            ->get();
//        $data['capex_merge_year'] = collect($data['capex_budget_year'])->merge(collect($data['capex_budget_from']));
        $year_one_capex_array = array();
        if (!$data['capex_budget_year']->isEmpty()) {
            foreach ($data['capex_budget_year'] as $val) {
                foreach ($data['capex_budget_from'] as $budget) {
                    if ($val->subcategory_id == $budget->subcategory_id) {
                        array_push($year_one_capex_array,
                            [
                                'year1_unit_price_dollar' => $val->my_unit_price_dollar,
                                'year1_qty' => $val->my_qty,
                                'year1_subcategory_id' => $val->subcategory_id,
                                'year1_year_id' => $val->year_id,
                                'year2_unit_price_dollar' => $budget->year1_qty_unit_price_dollar,
                                'year2_qty' => $budget->year1_qty,
                                'year2_subcategory_id' => $budget->subcategory_id,
                                'year2_year_id' => $budget->year_id,
                                'total_rows' => $budget->total_rows,
                            ]
                        );
                    }
                }
            }
        } else {
            foreach ($data['capex_budget_from'] as $val) {
                foreach ($data['capex_budget_year'] as $budget) {
                    if ($val->subcategory_id == $budget->subcategory_id) {
                        array_push($year_one_capex_array,
                            [
                                'year1_unit_price_dollar' => $val->my_unit_price_dollar,
                                'year1_qty' => $val->my_qty,
                                'year1_subcategory_id' => $val->subcategory_id,
                                'year1_year_id' => $val->year_id,
                                'year2_unit_price_dollar' => $budget->to_unit_price_dollar,
                                'year2_qty' => $budget->to_qty,
                                'year2_subcategory_id' => $budget->subcategory_id,
                                'year2_year_id' => $budget->year_id,
                                'total_rows' => $budget->total_rows,
                            ]
                        );
                    }
                }
            }
        }
        $year1_capex_array = collect($year_one_capex_array);

        $data['opex_budget_year'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            'year_id',
            DB::raw('SUM(qty) as year1_to_qty'),
            DB::raw('COUNT(id) as total_rows'),
            DB::raw('SUM(unit_price_dollar) as year1_to_myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->to_year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id', 'asc')
            ->get();

        $data['opex_budget_from'] = Budget::select(
            'subcategory_id',
            'year_id as from_year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as year2_to_qty'),
            DB::raw('COUNT(id) as total_rows'),
            DB::raw('SUM(unit_price_dollar) as year2_to_unit_price_dollar'),
//            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(unit_price_pkr) as myunit_prev_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $request->from_year_id)
            ->where('category_id', $request->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id', 'asc')
            ->get();
//        $data['opex_merge_year'] = collect($data['opex_budget_year'])->merge(collect($data['opex_budget_from']));
        $year_one_opex_array = array();
        $year_two_opex_array = array();
        if (!$data['opex_budget_year']->isEmpty() && count($data['opex_budget_year']) > count($data['opex_budget_from'])) {
            foreach ($data['opex_budget_year'] as $val) {
                foreach ($data['opex_budget_from'] as $budget) {
                    if ($val->subcategory_id == $budget->subcategory_id) {
                        array_push($year_one_opex_array,
                            [
                                'year1_unit_price_dollar' => $val->year1_to_myunit_price_dollar,
                                'year1_qty' => $val->year1_to_qty,
                                'year1_subcategory_id' => $val->subcategory_id,
                                'year1_year_id' => $val->year_id,
                                'year2_unit_price_dollar' => $budget->year2_to_unit_price_dollar,
                                'year2_qty' => $budget->year2_to_qty,
                                'year2_subcategory_id' => $budget->subcategory_id,
                                'year2_year_id' => $budget->year_id,
                                'total_rows' => $budget->total_rows,
                            ]
                        );
                    } else {
                        array_push($year_two_opex_array,
                            [
                                'year1_unit_price_dollar' => $val->year1_to_myunit_price_dollar,
                                'year1_qty' => $val->year1_to_qty,
                                'year1_subcategory_id' => Subcategory::find($val->subcategory_id)['sub_cat_name'],
                                'year1_year_id' => Year::find($val->year_id)['year'],
                                'total_rows' => $val->total_rows,
                                'year2_unit_price_dollar' => '',
                                'year2_qty' => '',
                                'year2_subcategory_id' => '',
                                'year2_year_id' => '',
                                'total_rows' => '',
                            ]);
                        array_push($year_two_opex_array,
                            [
                                'year1_unit_price_dollar' => '',
                                'year1_qty' => '',
                                'year1_subcategory_id' => '',
                                'year1_year_id' => '',
                                'total_rows' => '',
                                'year2_unit_price_dollar' => $budget->year2_to_unit_price_dollar,
                                'year2_qty' => $budget->year2_to_qty,
                                'year2_subcategory_id' => Subcategory::find($budget->subcategory_id)['sub_cat_name'],
                                'year2_year_id' => $budget->year_id,
                                'total_rows' => $budget->total_rows,
                            ]
                        );
                    }
                }
            }
        } else {
            foreach ($data['opex_budget_from'] as $val) {
                foreach ($data['opex_budget_year'] as $budget) {
                    if ($val->subcategory_id == $budget->subcategory_id) {
                        array_push($year_one_opex_array,
                            [
                                'year1_unit_price_dollar' => $val->year1_to_myunit_price_dollar,
                                'year1_qty' => $val->year1_to_qty,
                                'year1_subcategory_id' => Subcategory::find($val->subcategory_id)['sub_cat_name'],
                                'year1_year_id' => $val->year_id,
                                'year2_unit_price_dollar' => $budget->year2_to_unit_price_dollar,
                                'year2_qty' => $budget->year2_to_qty,
                                'year2_subcategory_id' => Subcategory::find($budget->subcategory_id)['sub_cat_name'],
                                'year2_year_id' => $budget->year_id,
                                'total_rows' => $budget->total_rows,
                            ]
                        );
                    }
                }
            }
        }
        $year2_capex_array = collect($year_one_opex_array);
//        $check = array();
//        $check1 = array();
//        foreach ($data['capex_budget_from'] as $items){
//            array_push($check,
//                [
//                    'subcategory_id' => Subcategory::find( $items->subcategory_id)['sub_cat_name'],
//                    'my_unit_price_dollar' => $items->my_unit_price_dollar,
//                    'year1_qty' => $items->year1_qty,
//                    'year_id' => $items->year_id,
//                    'total_rows' => $items->total_rows,
//                ]
//            );
//        }
//        foreach ($data['capex_budget_year'] as $items){
//            array_push($check1,
//                [
//                    'subcategory_id' => Subcategory::find( $items->subcategory_id)['sub_cat_name'],
//                    'my_unit_price_dollar' => $items->year1_unit_price_dollar,
//                    'year1_qty' => $items->year1_qty,
//                    'year_id' => $items->year_id,
//                    'total_rows' => $items->total_rows,
//                ]
//            );
//        }
        return view('budget_compare', $data, compact('year1_capex_array', 'year2_capex_array'));
    }

    public function summary_by_year(Request $request)
    {
        $budget = Budget::where('year_id', $request->year_id)->first();

        // if(!empty($budget)){
        //     $types = Type::orderBy('type', 'asc')->get();
        //     foreach($types as $type){
        //     $category = Category::where('status',1)->get();
        //     foreach($category as $cat){


        //         $cat['unit_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('unit_price_dollar');
        //         $cat['unit_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('unit_price_pkr');
        //         $cat['total_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('total_price_dollar');
        //         $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('total_price_pkr');
        //         $cat['consumed'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('consumed');
        //         $cat['remaining'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('remaining');
        //         }
        //     $type->categories = $category;
        //     }
        // }
        // $budget = Budget::where('year_id', $data)->first();

        if (!empty($budget)) {

            $types = Type::all();
            $year_data = Year::find($request->year_id);
            if ($year_data->year_start_date != null && $year_data->year_end_date != null) {
                foreach ($types as $type) {
                    $category = Category::where('status', 1)->get();
                    foreach ($category as $cat) {
                        $consumed_price_dollar = 0;
                        $consumed_price_pkr = 0;
                        $remaining_price_dollar = 0;
                        $remaining_price_pkr = 0;
                        $old_fetch = Inventory::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $new_fetch = Invoicerelation::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $fetch = $old_fetch->merge($new_fetch);
                        foreach ($fetch as $get) {
                            $consumed_price_dollar += round($get->item_price) / round($get->dollar_rate);
                            $consumed_price_pkr += round($get->item_price);
                        }

                        $cat['total_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('total_price_dollar');
                        $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('total_price_pkr');
                        $cat['qty'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('qty');
                        $cat['consumed'] = $fetch->count();//Inventory::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->count();
                        $cat['consumed_price_dollar'] = $consumed_price_dollar;
                        $cat['consumed_price_pkr'] = $consumed_price_pkr;
                        $cat['remaining_price_dollar'] = ($cat->total_price_dollar - $consumed_price_dollar);
                        $cat['remaining_price_pkr'] = ($cat->total_price_pkr - $consumed_price_pkr);
                        $cat['remaining'] = ($cat->qty - $cat->consumed);
                    }
                    $type->categories = $category;
                }
            }
        } else {
            $types = array();
        }
        return view('summary', ['filter' => $request->year_id, 'types' => $types, 'years' => Year::orderBy('year', 'asc')->get()]);
    }

    public function summaryDollar(Request $request)
    {
        $data['yearList'] = Year::all();
        $data['data'] = array();
        $data1=[];
        $data2=[];
        $total=0;
        if (!empty($request)) {
            foreach ($request->summary as $item) {
                $typeOne = DB::table('budgetitems')->where('type_id', 1)->where('year_id', $item)
                    ->select(['year_id', 'total_price_dollar', 'type_id'])
                    ->get();
                $typeTwo = DB::table('budgetitems')->where('type_id', 2)->where('year_id', $item)
                    ->select(['year_id', 'total_price_dollar', 'type_id'])
                    ->get();
                if (count($typeOne)>0 && count($typeTwo)>0) {
                    $data1 = [
                        'type' => Type::find($typeOne[0]->type_id)['type'],
                        'dollarAmount' =>$typeOne->sum('total_price_dollar'),
                        'year' => Year::find($item)['year'],

                    ];
                    $data2 = [

                        'type' => Type::find($typeTwo[0]->type_id)['type']??'',
                        'dollarAmount' => $typeTwo->sum('total_price_dollar'),
                        'year' => Year::find($item)['year'],
                    ];
                    $total=$typeTwo->sum('total_price_dollar')+$typeOne->sum('total_price_dollar');
                }
                else{
                    $data2=[];
                }
                if(count($data1)>0){
                    array_push($data['data'], $data1);
                }
                if(count($data2)>0){
                    array_push($data['data'], $data2);
                }
            }
        } else {
            $data['data'] = [];
        }
//        dd($data);
//        $data['total']=;
        $data['data'] = (object)$data;
        return view('summaryDollar', $data);
    }

    public function summary_by_year2(Request $request)
    {
        $budget = Budget::where('year_id', $request->year_id)->first();
        if (!empty($budget)) {
            $year_data = Year::find($request->year_id);
            if ($year_data->year_start_date != null && $year_data->year_end_date != null) {
                $types = Type::all();
                foreach ($types as $type) {
                    $category = Category::where('status', 1)->get();
                    foreach ($category as $cat) {
                        $consumed_price_dollar = 0;
                        $consumed_price_pkr = 0;
                        $remaining_price_dollar = 0;
                        $remaining_price_pkr = 0;
                        $old_fetch = Inventory::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $new_fetch = Invoicerelation::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();

                        $fetch = $old_fetch->merge($new_fetch);

                        foreach ($fetch as $get) {
                            $consumed_price_dollar += round($get->item_price) / round($get->dollar_rate);
                            $consumed_price_pkr += round($get->item_price);
                        }

                        $cat['total_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('total_price_dollar');
                        $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('total_price_pkr');
                        $cat['qty'] = Budget::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->sum('qty');
                        $cat['consumed'] = $fetch->count(); //Inventory::where('category_id', $cat->id)->where('year_id', $request->year_id)->where('type_id', $type->id)->count();
                        $cat['consumed_price_dollar'] = $consumed_price_dollar;
                        $cat['consumed_price_pkr'] = $consumed_price_pkr;
                        $cat['remaining_price_dollar'] = ($cat->total_price_dollar - $consumed_price_dollar);
                        $cat['remaining_price_pkr'] = ($cat->total_price_pkr - $consumed_price_pkr);
                        $cat['remaining'] = ($cat->qty - $cat->consumed);
                    }
                    $type->categories = $category;
                }
            }
        } else {
            $types = array();
        }
        return view('summary2', ['filter' => $request->year_id, 'types' => $types, 'years' => Year::orderBy('year', 'asc')->get()]);
    }

    public function lock_budget($id)
    {
        $budget = Budget::where('year_id', $id)->first();

        if (!empty($budget)) {
            $year = Year::where('id', $id)->update(['locked' => 1]);
            if ($year) {
                return redirect()->back()->with('msg', 'Budget Locked Successfully!');
            } else {
                return redirect()->back()->with('msg', 'Could not lock budget, Try Again!');
            }
        } else {
            return redirect()->back()->with('msg', 'No any budget found in selected year, Kindly add budget and try again!');
        }
    }

    public function get_budget_items($inv_id, $dept_id)
    {
        $inv = Inventory::find($inv_id);
        $budgets = Budget::where('year_id', $inv->year_id)
            ->where('category_id', $inv->category_id)
            ->where('subcategory_id', $inv->subcategory_id)
            ->where('dept_id', $dept_id)
            ->where('remaining', '>', 0)
            ->get();

        //return [$inv,$budgets];
        return count($budgets) > 0 ? $budgets : '0';
        //return view('get_budget_items', ['budgets'=>$budgets]);
    }

    public function budget_transfer()
    {
        $record = Year::all();
        $from = array();
        $to = array();
        foreach ($record as $val) {
            $budget = Budget::where('year_id', $val->id)->count();
            if ($budget > 0) {
                $from[] = $val;
            } else {
                $to[] = $val;
            }
        }
        return view('budget_transfer', ['swap_from' => $from, 'swap_to' => $to]);
    }

    public function transfered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_year_id' => 'required|not_in:0',
            'to_year_id' => 'required|not_in:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $id = $request->from_year_id;
        $to = $request->to_year_id;
        $budgets = Budget::where('year_id', $id)->get();
        foreach ($budgets as $budget) {
            $fields = array(
                'user_id' => $budget->user_id,
                'category_id' => $budget->category_id,
                'subcategory_id' => $budget->subcategory_id,
                'type_id' => $budget->type_id,
                'dept_id' => $budget->dept_id,
                'dept_branch_type' => $budget->dept_branch_type,
                'department' => $budget->department,
                'year_id' => $to,
                'description' => $budget->description,
                'remarks' => $budget->remarks,
                'unit_price_dollar' => $budget->unit_price_dollar,
                'unit_price_pkr' => $budget->unit_price_pkr,
                'qty' => $budget->qty,
                'consumed' => $budget->consumed,
                'remaining' => $budget->remaining,
                'total_price_dollar' => $budget->total_price_dollar,
                'total_price_pkr' => $budget->total_price_pkr,
                'budget_nature' => $budget->budget_nature
            );
            $create = Budget::create($fields);
        }
        if ($create) {
            return redirect()->back()->with('msg', 'Budget Transferred Successfully!');
        } else {
            return redirect()->back()->with('msg', 'Could not Transfer budget , Try Again!');
        }
    }

    public function swapping()
    {

        $data = array();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['subcategories'] = Subcategory::where('status', 1)->orderBy('sub_cat_name', 'asc')->get();
        $data['types'] = Type::orderBy('type', 'asc')->get();
        $data['years'] = Year::where('is_current_year', 1)->orderBy('year', 'asc')->get();

        return view('swapping', $data);

    }

    public function swapping2(Request $request)
    {

        $messages = [
            'category_id.required' => 'Category name is required.',
            'sub_cat_id.required' => 'SubCategory name is required',
            'from_dept.required' => 'From Department name is required',
            'from_dept.not_in' => 'From Department value must be selected',
            'to_dept.required' => 'To Department name is required',
            'to_dept.not_in' => 'To Department value must be selected',
            'year_id.required' => 'Year name is required',
            'qty.required' => 'Quantity is required',
            'radio_budget_id.required' => 'Select budget radio button Value is required',
//            'to_remarks.required'  => 'To Remarks is required',
//            'to_description.required'  => 'To Description is required',
        ];
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'sub_cat_id' => 'required|not_in:0',
            'from_dept' => 'required|not_in:0',
            'to_dept' => 'required|not_in:0',
            'year_id' => 'required|not_in:0',
            'qty' => 'required',
            'radio_budget_id' => 'required',
            'swap_dept_to_name' => 'required',
//            'to_remarks' => 'required',
//            'to_description' => 'required',

        ], $messages);

        if ($validator->fails()) {
            return response()->json(['code' => 403, 'message' => $validator->errors()->all()], 200);
        }
        $qty = $request->qty;
//        $from = Budget::where('year_id', $request->year_id)->where('category_id', $request->category_id)->where('subcategory_id', $request->sub_cat_id)->where('dept_id', $request->from_dept)->first();
        $from = Budget::find($request->radio_budget_id);
        $to = Budget::where('year_id', $request->year_id)->where('category_id', $request->category_id)->where('subcategory_id', $request->sub_cat_id)->where('dept_id', $request->to_dept)->first();
        if ($from) {
            if ($qty > $from->remaining) {
//                return redirect()->back()->with('msg-error', 'Requested quantity must be less then or equal to available quantity!');
                return response()->json(['code' => 403, 'message' => 'Requested quantity must be less then or equal to available quantity!'], 200);
            }
            $from_qty = $from->qty - $qty;
            $from_remaining = $from->remaining - $qty;
            $from_total_price_dollar = $from->unit_price_dollar * $from_qty;
            $from_total_price_pkr = $from->unit_price_pkr * $from_qty;

            // TO Budget
//            $to_qty = $to->qty + $qty;
//            $to_remaining = $to->remaining + $qty;
//            $to_total_price_dollar = $to->total_price_do  llar * $to->remaining;
//            $to_total_price_pkr    = $to->total_price_pkr * $to->remaining;

            $from_fields = array(
                'qty' => $from_qty,
                'remaining' => $from_remaining,
                'total_price_dollar' => $from_total_price_dollar,
                'total_price_pkr' => $from_total_price_pkr,
                'remarks' => $request->from_remarks,
                'description' => $request->from_description,
            );
            $from_update = Budget::where('id', $from->id)->update($from_fields);
            $department = EmployeeBranch::where('branch_id', $request->to_dept)->first();
            $create = Budget::create([
                'user_id' => Auth::id(),
                'category_id' => $from->category_id,
                'subcategory_id' => $from->subcategory_id,
                'type_id' => $from->type_id,
                'dept_id' => $request->to_dept,
                'dept_branch_type' => $from->dept_branch_type,
                'department' => $request->swap_dept_to_name ?? '',
                'year_id' => $from->year_id,
                'description' => $request->to_description,
                'remarks' => $request->to_remarks,
                'unit_price_dollar' => $from->unit_price_dollar,
                'unit_price_pkr' => $from->unit_price_pkr,
                'qty' => $qty,
                'consumed' => 0,
                'remaining' => $qty,
                'total_price_dollar' => $from->unit_price_dollar * $request->qty,
                'total_price_pkr' => $from->unit_price_pkr * $request->qty,
                'budget_nature' => $from->budget_nature,
            ]);

//            $to_fields = array('qty' => $to_qty, 'remaining' => $to_remaining);
//            $to_update = Budget::where('id', $to->id)->update($to_fields);

//            $to_update
            if ($from_update && $create) {
//                return redirect()->back()->with('msg', 'Budget Swapped Successfully!');
                return response()->json(['code' => 200, 'message' => 'Budget Swapped Successfully!'], 200);
            } else {
//                return redirect()->back()->with('msg-error', 'Could not swap budget, Try Again!');
                return response()->json(['code' => 403, 'message' => 'Could not swap budget, Try Again!'], 200);
            }
        } else {
//            return redirect()->back()->with('msg-error', 'Budget not available!');
            return response()->json(['code' => 403, 'message' => 'Budget not available!'], 200);

        }

    }

    public function get_budget(Request $request)
    {
//        dd($request->all());
        $from = Budget::where('year_id', $request->year_id)->where('category_id', $request->category_id)->where('subcategory_id', $request->sub_cat_id)->where('dept_id', $request->from_dept)->where('remaining', '>', 0)->get();
        if ($from) {
            return $from;
        }
    }

    public function get_budget_single(Request $request)
    {
        $from = Budget::find($request->id);
        return $from;
    }

    public function budgetdetails($cat_id, $type_id, $year_id)
    {
        // $budget = Budget::find($id);
        //return $budget->category_id.' : '.$budget->year_id.' : '.$budget->type_id;
        $inventories = Inventory::where('category_id', $cat_id)->where('year_id', $year_id)->where('type_id', $type_id)->get();
        foreach ($inventories as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
        }
        return view('budgetdetails', ['inventories' => $inventories]);
    }

    public function transfer_product_sn()
    {
        $data = array();
        $data['years'] = Year::all();
        return view('transfer_product_sn', $data);
    }

    public function transfer_product_sn2(Request $request)
    {

        $messages = [
            'from_year_id.required' => 'From Year name is required',
            'from_year_id.not_in' => 'From Year value must be selected',
            'to_year_id.required' => 'To Year name is required',
            'to_year_id.not_in' => 'To Year value must be selected',
        ];
        $validator = Validator::make($request->all(), [
            'from_year_id' => 'required|not_in:0',
            'to_year_id' => 'required|not_in:0',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $inventory = Inventory::where('year_id', $request->from_year_id)->whereNull('issued_to')->get();
        if (count($inventory) > 0) {
            foreach ($inventory as $inv) {
                $fromyear = Year::find($request->from_year_id);
                $duplicate = $inv->replicate();
                $duplicate->carry_forward_year_id = $duplicate->year_id;
                $duplicate->year_id = $request->to_year_id;
                $duplicate->carry_forward_status_id = 2;
                $duplicate->remarks = $duplicate->remarks . ' - Carry Forward From Budget ' . $fromyear->year . '.';
                $duplicate->save();
                $year = Year::find($request->to_year_id);
                $inv->carry_forward_status_id = 3;
                $inv->product_sn = $inv->product_sn . '-CR-' . $year->year;
                $inv->remarks = $inv->remarks . ' - Carry forward to Budget ' . $year->year . '.';
                $inv->update();
            }
            return redirect()->back()->with('msg', 'Credit Transfer Successfully !');
        } else {
            return redirect()->back()->with('error-msg', 'No inventory found for credit transfer !');
        }


    }

}
