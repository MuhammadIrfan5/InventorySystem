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
use function GuzzleHttp\Promise\all;

class BudgetCollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('permission:budget_collection', ['only' => ['index']]);
        $this->middleware('permission:add_budget_collection', ['only' => ['store']]);
        $this->middleware('permission:edit_budget_collection', ['only' => ['show', 'update']]);
        $this->middleware('permission:delete_budget_collection', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = array();
        $data['years'] = Year::where('locked',0)->where('is_budget_collection',1)->orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['budgets'] = array();
        $data['filters'] = (object)array('catid' => '', 'yearid' => '');
        return view('show_budget_collection', $data);
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
//        dd($fields,$request->all());
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
        return view('edit_budget_collection', $data);
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

    public function budget_collection_by_year(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|not_in:0',
            'year_id' => 'required|not_in:0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $data = array();
        $data['years'] = Year::where('locked',0)->where('is_budget_collection',1)->orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['budgets'] = Budget::where('year_id', $request->year_id)->where('category_id', $request->category_id)->get();
        $data['filter'] = Year::find($request->year_id);
        $data['filters'] = (object)array('catid' => $request->category_id, 'yearid' => $request->year_id);
        $data['selected_year'] = Year::find($request->year_id);
        return view('show_budget_collection', $data);
    }


}
