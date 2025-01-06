<?php

namespace App\Http\Controllers;

use App\InventoryIssueRecord;
use App\Rturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\EmployeeBranch;
use App\Subcategory;
use App\Grn;
use App\Gin;
use App\SLA;
use App\Dollar;
use App\SLAComplainLog;
use App\Inventory;
use App\InventoryInvoice;
use App\Invoicerelation;
use App\Employee;
use App\Category;
use App\Type;
use App\Year;
use App\User;
use App\Budgetitem as Budget;
use App\Issue;
use App\Vendor;
use App\Repairing;
use App\Disposal;
use App\Dispatchin;
use App\Dispatchout;
use App\Modal;
use App\Makee;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
class PDFController extends Controller
{
    public function generatePDF()
    {
        $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }

    public function generateSummaryByYear($data)
    {
        $data=json_decode($data,true);
        $data['data'] = $data;
        $data['capexTotal'] = 0;
        $data['opexTotal'] = 0;
        $pdf = PDF::loadView('summary_by_year', $data);
        return $pdf->download('summaryCapexOpexByYear.pdf');
    }

    public function generateBudgetPlanPDF($data)
    {
        $other_data = [
            'year' => Year::find($data[0]["year_id"])['year'],
            'employee_code' => $data[0]["employee_code"],
            'emp_name' => Employee::where('emp_code',$data[0]["employee_code"])->first()['name'],
            'other_req' => $data[0]["other_req"],
        ];
      $pdf = PDF::loadView('budget_plan_report', ['data' => $data , 'other_data' => $other_data]);
//        return $pdf->download('budgetplan.pdf');
        return $pdf->download('budgetplan.pdf');
    }

    public function generateGRN($id, $from, $to)
    {
        $range = array('from' => $from, 'to' => $to);
        $grn = GRN::where('id', $id)->first();
        $inv = json_decode($grn->inv_id);
        $inventories = array();
        $user = '';

        foreach ($inv as $inv_id) {
            $inventory = Inventory::find($inv_id);
            if ($inventory) {
                $user = isset($inventory->added_by) ? User::find($inventory->added_by) : '';
                $inventories[] = $inventory;
            }
        }
        $data = array('inventories' => $inventories, 'user' => $user, 'grn_date' => $grn->created_at, 'range' => $range);
        $pdf = PDF::loadView('grnreport', $data)->setPaper('a4', 'landscape');

        return $pdf->download($grn->grn_no . '.pdf');
    }

    public function generateGIN($id, $from, $to)
    {
        $range = array('from' => $from, 'to' => $to);
        $gin = GIN::where('id', $id)->first();
        $inv = json_decode($gin->inv_id);
        $inventories = array();
        $employee = '';

        foreach ($inv as $inv_id) {
            $inventory = Inventory::find($inv_id);
            $employee = Employee::where('emp_code', $inventory->issued_to)->first();
            $inventory->employee = $employee;
            $inventories[] = $inventory;
        }
        $data = array('inventories' => $inventories, 'employee' => $employee, 'gin' => $gin, 'range' => $range);
        //return view('grnreport', $data);
        $pdf = PDF::loadView('ginreport', $data);

        return $pdf->download($gin->gin_no . '.pdf');
    }

    public function budgetexport($data)
    {
        $budget = Budget::where('year_id', $data)->first();

        if (!empty($budget)) {

            $types = Type::all();
            $year_data = Year::find($data);
            if($year_data->year_start_date != null && $year_data->year_end_date != null) {
                foreach ($types as $type) {
                    $category = Category::where('status', 1)->get();
                    foreach ($category as $cat) {
                        $consumed_price_dollar = 0;
                        $consumed_price_pkr = 0;
                        $remaining_price_dollar = 0;
                        $remaining_price_pkr = 0;
//                        $fetch = Inventory::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $old_fetch = Inventory::where('category_id', $cat->id)->where('devicetype_id','!=',5)->where('year_id', $year_data->id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $new_fetch = Invoicerelation::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $fetch = $old_fetch->merge($new_fetch);
                        foreach ($fetch as $get) {
                            $consumed_price_dollar += round($get->item_price) / round($get->dollar_rate);
                            $consumed_price_pkr += round($get->item_price);
                        }

                        $cat['total_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->sum('total_price_dollar');
                        $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->sum('total_price_pkr');
                        $cat['qty'] = Budget::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->sum('qty');
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
        //return $types;
        $year = Year::find($data);
        $pdf = PDF::loadView('summaryreport2', ['types' => $types, 'year' => $year->year])->setPaper('a4', 'landscape');
        return $pdf->download('Summaryreport_' . $year->year . '.pdf');
    }

    public function budgetexport2($data)
    {
        $budget = Budget::where('year_id', $data)->first();
        if (!empty($budget)) {
            $types = Type::all();
            $year_data = Year::find($data);
            if($year_data->year_start_date != null && $year_data->year_end_date != null) {
                foreach ($types as $type) {
                    $category = Category::where('status', 1)->get();
                    foreach ($category as $cat) {
                        $consumed_price_dollar = 0;
                        $consumed_price_pkr = 0;
                        $remaining_price_dollar = 0;
                        $remaining_price_pkr = 0;
//                        $fetch = Inventory::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $old_fetch = Inventory::where('category_id', $cat->id)->where('devicetype_id','!=',5)->where('year_id', $year_data->id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $new_fetch = Invoicerelation::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        $fetch = $old_fetch->merge($new_fetch);
                        foreach ($fetch as $get) {
                            $consumed_price_dollar += round($get->item_price) / round($get->dollar_rate);
                            $consumed_price_pkr += round($get->item_price);
                        }

                        $cat['total_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->sum('total_price_dollar');
                        $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->sum('total_price_pkr');
                        $cat['qty'] = Budget::where('category_id', $cat->id)->where('year_id', $year_data->id)->where('type_id', $type->id)->sum('qty');
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
        //return $types;
        $year = Year::find($data);
        $pdf = PDF::loadView('summaryreport3', ['types' => $types, 'year' => $year->year])->setPaper('a4', 'landscape');
        return $pdf->download('Summaryreport_' . $year->year . '.pdf');
    }

    public function itemexport($data)
    {
        $filters = json_decode($data);
        $types = Type::all();
        foreach ($types as $type) {
            $type->budgets = Budget::where('year_id', $filters->yearid)->where('category_id', $filters->catid)->where('type_id', $type->id)->get();
        }
        $year = Year::find($filters->yearid);
        $category = Category::find($filters->catid);
        $pdf = PDF::loadView('itemsreport', ['types' => $types, 'year' => $year->year, 'category' => $category->category_name])->setPaper('a4', 'landscape');
        return $pdf->download($category->category_name . '_report_' . $year->year . '.pdf');
    }

    public function itemexport_subcategory($data)
    {
        $filters = json_decode($data);
        $types = Type::all();
//        foreach ($types as $type) {
//            $type->budgets = Budget::where('year_id', $filters->yearid)->where('category_id', $filters->catid)->where('type_id', $type->id)->get();
//        }
            $capex_budget = Budget::select( DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $filters->yearid)
            ->where('category_id',$filters->catid)
            ->where('type_id',1)
            ->groupBy('subcategory_id')
            ->orderBy('subcategory_id')
            ->get();
        $opex_budget=Budget::select( DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $filters->yearid)
            ->where('category_id',$filters->catid)
            ->where('type_id',2)
            ->groupBy('subcategory_id')
            ->get();
        $year = Year::find($filters->yearid);
        $category = Category::find($filters->catid);
        $pdf = PDF::loadView('itemsreport_subcategory', ['capex_budget'=> $capex_budget,'opex_budget'=> $opex_budget,'types' => $types, 'year' => $year->year, 'category' => $category->category_name])->setPaper('a4', 'landscape');
        return $pdf->download($category->category_name . '_report_' . $year->year . '.pdf');
    }

    public function itemexport_subcategory_adv($data){
        $filters = json_decode($data);
        $data = array();
        $data['years'] = Year::orderBy('year', 'asc')->get();
        $data['categories'] = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
        $data['filter'] = Year::find($filters->yearid);
        $data['category_name'] = Category::find($filters->catid);
        $data['filters'] = (object)array('catid' => $filters->catid, 'yearid' => $filters->yearid, 'year_name' => $data['filter']->year, 'category_name' => $data['category_name']->category_name);
        $data['capex_budget_items'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $filters->yearid)
            ->where('category_id', $filters->catid)
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
            ->where('year_id', $filters->yearid)
            ->where('category_id', $filters->catid)
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
            ->where('year_id', $filters->yearid)
            ->where('category_id', $filters->catid)
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
            ->where('year_id', $filters->yearid)
            ->where('category_id', $filters->catid)
            ->where('type_id', 2)
            ->whereIn('subcategory_id', $subcat_array_opex)
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
                if ( $inv->dollar_rate != '') {
                    $dollar_rate = $inv->consumed_pkr / ($inv->dollar_rate / $inv->consumed_qty);
                    $data['opex_budget_items'][$key]['dollar_amount'] = $dollar_rate;
                }
            }
        }

        $pdf = PDF::loadView('itemsreport_subcategory_adv', $data)->setPaper('a4', 'landscape');
        return $pdf->download($filters->category_name . '_report_' . $filters->year_name . '.pdf');

    }

    public function itemexport_subcategory_summary($old_data)
    {
        $filters = json_decode($old_data);
        foreach ($filters->prev_year_id as $id){
            $prev_year_id[] = $id->id;
        }

        $data = array();
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
            ->where('year_id', $filters->yearid)
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
                    $val->percentage = (($val->myunit_price_pkr - $budget->myunit_prev_price_pkr)/$val->myunit_price_pkr)*100;
                }
            }
        }
        $data['opex_budget_year'] =Budget::select( DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'category_id',
            'year_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $filters->yearid)
            ->where('type_id',2)
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
                    $val->percentage = (($val->myunit_price_pkr - $budget->myunit_prev_price_pkr)/$val->myunit_price_pkr)*100;
                }
            }
        }

        $inv = array();
        $data['prev'] = collect($data['capex_budget_prev'])->merge($data['opex_budget_prev']);
        $data['categories_data'] = $data['prev']->unique('category_id');
        foreach ( $data['categories_data'] as $cat_id){
            $inv[] = Inventory::select('item_price','id')->whereIn('year_id', $prev_year_id)->where('category_id', $cat_id->category_id)->where('devicetype_id','!=',5)->sum('item_price');
        }
        $data['actual_used'] = array_sum($inv) ? array_sum($inv) : 0;
        $data['dollar_rate'] = Dollar::where('year_id',$filters->yearid)->first();
//        ['capex_budget_year'=> $data['capex_budget_year'],'opex_budget'=> $opex_budget,'types' => $types, 'year' => $year->year, 'category' => $category->category_name]
        $pdf = PDF::loadView('itemsreport_subcategory_summary', $data)->setPaper('a4', 'landscape');
        return $pdf->download('summary_report_' . $filters->year_name . '.pdf');
    }

    public function compare_budget_subcategory($old_data){
        $filters = json_decode($old_data);
        $data = array();
        $data['filters'] = $filters;
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
            ->where('year_id', $filters->to_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id','year_id')
            ->orderBy('subcategory_id')
            ->get();
        $data['capex_budget_from'] = Budget::select(
            'subcategory_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as year1_qty'),
            DB::raw('COUNT(id) as total_rows'),
            DB::raw('SUM(unit_price_dollar) as year1_qty_unit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as unit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as total_price_dollar'),
            DB::raw('SUM(total_price_pkr) as total_price_pkr'))
            ->where('year_id', $filters->from_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id')
            ->get();

        $year_one_capex_array = array();
        if(!$data['capex_budget_year']->isEmpty()) {
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
        }else{
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
            ->where('year_id', $filters->to_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id')
            ->get();

        $data['opex_budget_from'] = Budget::select(
            'subcategory_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as year2_to_qty'),
            DB::raw('COUNT(id) as total_rows'),
            DB::raw('SUM(unit_price_dollar) as year2_to_unit_price_dollar'),
//            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(unit_price_pkr) as myunit_prev_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $filters->from_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id')
            ->get();

        $year_one_opex_array = array();
        if(!$data['opex_budget_year']->isEmpty()){
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
                    }
                }
            }
        }else{
            foreach ($data['opex_budget_from'] as $val) {
                foreach ($data['opex_budget_year'] as $budget) {
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
                }
            }
        }
        $year2_capex_array = collect($year_one_opex_array);
        $pdf = PDF::loadView('budget_compare_subcategory', $data,compact('year2_capex_array','year1_capex_array'))->setPaper('a4', 'landscape');
        return $pdf->download('compare_budget_report_' . $filters->to_year_name . '.pdf');

    }

    public function compare_budget_subcategory_new($old_data){
        $filters = json_decode($old_data);
        $data = array();
        $data['filters'] = $filters;
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
            ->where('year_id', $filters->to_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id','asc')
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
            ->where('year_id', $filters->from_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id','asc')
            ->get();

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
            ->where('year_id', $filters->to_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id','asc')
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
            ->where('year_id', $filters->from_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 2)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id','asc')
            ->get();

        $pdf = PDF::loadView('budget_compare_subcategory_new', $data, compact('filters'))->setPaper('a4', 'landscape');
        return $pdf->download('compare_budget_report_' . $filters->to_year_name . '.pdf');

    }

    public function inventoryexport($data)
    {
        $fields = (array)json_decode($data);
        $key = $fields['inout'][0];
        $op = $fields['inout'][1];
        $val = $fields['inout'][2];
        unset($fields['inout']);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['from_date']);
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)
                ->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->where('devicetype_id','!=',5)
                ->orderBy('id', 'desc')->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)
                ->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                ->whereNotIn('status', [0])
                ->where('devicetype_id','!=',5)
                ->orderBy('id', 'desc')->get();
        } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)
                ->whereBetween('updated_at', ['', date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->where('devicetype_id','!=',5)
                ->orderBy('id', 'desc')->get();
        } else {
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
        }
        foreach ($inventories as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
        }
        $pdf = PDF::loadView('inventoryreport', ['inventories' => $inventories])->setPaper('a4', 'landscape');
        return $pdf->download('inventoryreport.pdf');
    }

    public function invoiceinventoryexport($data)
    {
        $fields = (array)json_decode($data);
        $key = $fields['inout'][0];
        $op = $fields['inout'][1];
        $val = $fields['inout'][2];
        unset($fields['inout']);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['from_date']);
            unset($fields['to_date']);
//            $inventories = Invoicerelation::where([[$fields]])->where($key, $op, $val)
//                ->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
//                ->whereNotIn('status', [0])
//                ->orderBy('id', 'desc')->get();
            $inventories = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
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
                ->whereBetween('invoice_date', [$from, date('Y-m-d', $to)])
                ->groupBy('invoice_number')
                ->groupBy('invoice_date')
//                    ->orderBy('id')
                ->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
//            $inventories = Invoicerelation::where([[$fields]])->where($key, $op, $val)
//                ->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
//                ->whereNotIn('status', [0])
//                ->orderBy('id', 'desc')->get();
            $inventories = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
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
//            $inventories = Invoicerelation::where([[$fields]])->where($key, $op, $val)
//                ->whereBetween('updated_at', ['', date('Y-m-d', $to)])
//                ->whereNotIn('status', [0])
//                ->orderBy('id', 'desc')->get();
            $inventories = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
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
//            $inventories = Invoicerelation::where([[$fields]])->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            $inventories = Invoicerelation::select(DB::raw('group_concat(category_id) as category_id'),
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
        }
        foreach ($inventories as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
            $inv->cat_name = explode(',',$inv->category_id);
            $inv->sub_cat_name = explode(',',$inv->subcategory_id);
        }
        $pdf = PDF::loadView('invoiceinventory_report', ['inventories' => $inventories])->setPaper('a4', 'landscape');
        return $pdf->download('invoiceinventoryreport.pdf');
    }

    public function balanceexport($data)
    {
        $fields = (array)json_decode($data);
        $subcategories = Subcategory::where('status', 1)->get();
        foreach ($subcategories as $subcat) {
            $subcat->rem = Inventory::where([[$fields]])->where('devicetype_id','!=',5)->where('subcategory_id', $subcat->id)->where('issued_to', NULL)->count();
            $subcat->out = Inventory::where([[$fields]])->where('devicetype_id','!=',5)->where('subcategory_id', $subcat->id)->whereNotNull('issued_to')->count();
        }
        //return $subcategories;
        $pdf = PDF::loadView('balanceexport', ['subcategories' => $subcategories]);
        return $pdf->download('balancereport.pdf');
    }

    public function editlogsexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['from_date']);
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
            $inventories = Inventory::where([[$fields]])->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->whereBetween('updated_at', ['', date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else {
            $inventories = Inventory::where([[$fields]])->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
        }
        $pdf = PDF::loadView('inventorylogsreport', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('inventory_editlogs_report.pdf');
    }

    public function inventoryinexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $inventories=array();
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['from_date']);
            unset($fields['to_date']);
            $invs = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->whereBetween('created_at', [$from, date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
            $inventories = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->where('carry_forward_status_id', '!=', 3)->whereBetween('created_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->where('carry_forward_status_id', '!=', 3)->whereBetween('created_at', ['', date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else {
            $inventories = Inventory::where([[$fields]])->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
        }
        foreach ($inventories as $inv) {
            $inv->added_by = User::find($inv->added_by);
            $inv->return_remarks = Rturn::where('inventory_id',$inv->id)->select('remarks')->latest('created_at')->first();
        }

        $pdf = PDF::loadView('inventoryinreport', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('inventory_in_report.pdf');
    }

    public function inventoryoutexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $dept_id = $fields['dept_id'] ?? null;
        unset($fields['dept_id']);
        $key = $fields['inout'][0];
        $op = $fields['inout'][1];
        $val = $fields['inout'][2];
        unset($fields['inout']);
        if (isset($fields['from_issuance']) || isset($fields['to_issuance'])) {
            if (isset($fields['from_issuance']) && isset($fields['to_issuance'])) {
                $from = $fields['from_issuance'];
                $to = strtotime($fields['to_issuance'] . '+1 day');
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
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)->whereIn('id', $ids)->orderBy('id', 'desc')->cursor();
        } else {
            if(isset($fields['purchase_date_from']) || isset($fields['purchase_date_to'])){
                $inventories =new Inventory();
                $inventories=$inventories->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->whereNotIn('status', [0])->orderBy('id', 'desc');
                $inventories=$inventories->whereBetween('purchase_date',[$fields['purchase_date_from'],$fields['purchase_date_to']])->get();
            }
            else{
                $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }

        $items = array();
        foreach ($inventories as $inv) {
            $inv->user = Employee::where('emp_code', $inv->issued_to)->first();
            $inv->issued_by = User::find($inv->issued_by);
            $inv->issue_date = Issue::where('inventory_id', $inv->id)->select('created_at')->orderBy('id', 'desc')->first();
            $inv->issue_remarks = Issue::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first();
            if ($dept_id == $inv->user->dept_id) {
                $items[] = $inv;
            }
        }
        if ($dept_id) {
            $inventories = $items;
        }
        $pdf = PDF::loadView('inventoryoutreport', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('inventory_out_report.pdf');
    }
    public function inventoryoutexport1($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields =(array) json_decode($data);

        $dept_id = $fields['dept_id'] ?? null;
        unset($fields['dept_id']);
        $key = 'issued_to';
        $op = '>';
        $val = 0;
        $inventories=array();
        unset($fields['inout']);
        if (!empty($fields['from_issuance']) || !empty($fields['to_issuance'])) {
            if (isset($fields['from_issuance']) && isset($fields['to_issuance'])) {

                $from = $fields['from_issuance'];
                $to = strtotime($fields['to_issuance'] . '+1 day');
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
            $issue = Inventory::where('devicetype_id', '!=', 5)->where($key, $op, $val)->whereIn('id', $ids)->orderBy('id', 'desc')->get();
            $inventories=$issue;
        } else {
            if(isset($fields['purchase_date_from']) || isset($fields['purchase_date_to'])){
                $inventories =new Inventory();
                $inventories=$inventories->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->whereNotIn('status', [0])->orderBy('id', 'desc');
                $inventories=$inventories->whereBetween('purchase_date',[$fields['purchase_date_from'],$fields['purchase_date_to']])->get();
            }
            else{
                $inventories = Inventory::where('issued_to', '!=', null)->where('devicetype_id', '!=', '5')->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }
//        $items = array();
//        foreach ($inventories as $inv) {
//            $inv->user = Employee::where('emp_code', $inv->issued_to)->first();
//            $inv->issued_by = User::find($inv->issued_by);
//            $inv->issue_date = Issue::where('inventory_id', $inv->id)->select('created_at')->orderBy('id', 'desc')->first();
//            $inv->issue_remarks = Issue::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first();
//            if ($dept_id == $inv->user->dept_id) {
//                $items[] = $inv;
//            }
//        }
//        if ($dept_id) {
//            $inventories = $items;
//        }
        $pdf = PDF::loadView('inventoryoutreport', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('inventory_out_report.pdf');
    }

    public function generate_barcode($data){
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $dept_id = $fields['dept_id'] ?? null;
        unset($fields['dept_id']);
        $key = $fields['inout'][0];
        $op = $fields['inout'][1];
        $val = $fields['inout'][2];
        unset($fields['inout']);
        if (isset($fields['from_issuance']) || isset($fields['to_issuance'])) {

            if (isset($fields['from_issuance']) && isset($fields['to_issuance'])) {
                $from = $fields['from_issuance'];
                $to = strtotime($fields['to_issuance'] . '+1 day');
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
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)->whereIn('id', $ids)->orderBy('id', 'desc')->get();
        } else {
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
        }
        $items = array();
        foreach ($inventories as $inv) {
            $inv->user = Employee::where('emp_code', $inv->issued_to)->first();
            $inv->issued_by = User::find($inv->issued_by);
            $inv->issue_date = Issue::where('inventory_id', $inv->id)->select('created_at')->orderBy('id', 'desc')->first();
            $inv->issue_remarks = Issue::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first();
            if ($dept_id == $inv->user->dept_id) {
                $items[] = $inv;
            }
        }
        if ($dept_id) {
            $inventories = $items;
        }
        $pdf = PDF::loadView('generate_barcode', ['inventories' => $inventories , 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('generate_barcode.pdf');
    }

    public function bincardexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['from_date']);
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where('devicetype_id','!=',5)->whereBetween('updated_at', [$from, date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
            $inventories = Inventory::where([[$fields]])->where('devicetype_id','!=',5)->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where('devicetype_id','!=',5)->whereBetween('updated_at', ['', date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else {
            $inventories = Inventory::where([[$fields]])->where('devicetype_id','!=',5)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
        }
        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                $inventory->repairing = Repairing::where('item_id', $inventory->id)->first();
                $inventory->added_by = User::where('id', $inventory->added_by)->first();
            }
        }
        $pdf = PDF::loadView('bincardreport', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('bin_card_report.pdf');
    }

    public function repairingexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $repairs = Repairing::where([[$fields]])->orderBy('item_id', 'desc')->get();
        foreach ($repairs as $repair) {
            $repair->item->user = Employee::where('emp_code', $repair->item->issued_to)->first();
        }
        $pdf = PDF::loadView('repairingreport', ['repairs' => $repairs])->setPaper('a4', 'landscape');
        return $pdf->download('asset_repairing_report.pdf');
    }

    public function disposalexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
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
        $pdf = PDF::loadView('disposalreport', ['disposals' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('disposal_report.pdf');
        // $data = ["disposals"=>$inventories , 'filters'=>$data];
        // return view('disposalreport', $data);
    }

    public function vendor_buyingexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);

        if (empty($fields['subcategory_id'])) {
            $subcat = Subcategory::where('status', 1)->get();
        } else {
            $subcat = Subcategory::where('id', $fields['subcategory_id'])->get();
        }

        $array = array();
        $i = 0;
        foreach ($subcat as $sub) {

            if (isset($fields['from_date']) && isset($fields['to_date'])) {
                $from = $fields['from_date'];
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['from_date']);
                unset($fields['to_date']);
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', $to)])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', $to)])->whereNotIn('status', [0])->sum('item_price');

            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])->whereNotIn('status', [0])->sum('item_price');

            } else if (!isset($fields['from_d ate']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', ['', date('Y-m-d', $to)])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', ['', date('Y-m-d', $to)])->whereNotIn('status', [0])->sum('item_price');

            } else {
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id','!=',5)->where('vendor_id', $fields['vendor_id'])->whereNotIn('status', [0])->sum('item_price');
            }
            if ($array[$i]['total_items'] == 0) {
                unset($array[$i]);
            }
            $i++;
        }
        $inventories = $array;
        $pdf = PDF::loadView('vendorbuyingreport', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('vendor_buying_report.pdf');
    }

    public function dispatchinexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
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
        $pdf = PDF::loadView('dispatchinreport', ['dispatches' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('dispatchin_report.pdf');
    }

    public function dispatchinexport_mail(){

//        return view('emails.dispatchin_report_email');
        $inventories = Dispatchin::where('dispatchin_date',Carbon::today()->toDateString())->orderBy('id', 'desc')->get();
        foreach ($inventories as $inventory) {
            if (!empty($inventory->inventory)) {
                $user = Employee::where('emp_code', $inventory->inventory->issued_to)->first();
                if ($user) {
                    $branch_id_data = EmployeeBranch::where('branch_name', $user->department)->where('emp_code', $inventory->inventory->issued_to)->first();
                    $inventory->user = $user;
                    $inventory->user->branch_id = $branch_id_data->branch_id;
                }
            }
        }

        $file_name = time() . 'dispatchin_report.pdf';
        $pdf = PDF::loadView('daily_disptachin_report', ['dispatches' => $inventories])->setPaper('a4', 'landscape')->save(public_path('dispatchin_reports/' . $file_name));
//        $pdf->download('dispatchin_report.pdf');
        $date = Carbon::parse(Carbon::today()->toDateString(), 'Asia/karachi');
        $format = $date->isoFormat('Do MMMM YYYY');
        $data = array(
            'dispatch_in_date' => $format,
            'file_link' => $file_name,
        );
        $user_emails = ['muhammadirfan5891@gmail.com', 'irfannadeem5@gmail.com'];
        Mail::send('emails.dispatchin_report_email', ['data' => $data], function ($message) use ($user_emails) {
            $date = Carbon::parse(Carbon::today()->toDateString(), 'Asia/karachi');
            $format = $date->isoFormat('Do MMMM YYYY');
            $message->to('muhammadirfan5891@gmail.com')->subject
            ('Dispatch IN Report ' . $format);
            $message->from('itstore@efulife.com', 'Support IT Store');
        });

    }

    public function dispatchoutexport_mail(){
//        return view('emails.dispatchin_report_email');
        date_default_timezone_set('Asia/karachi');
//        Carbon::yesterday()->toDateString()
        $inventories = Dispatchout::where('dispatchout_date', Carbon::today()->toDateString())->orderBy('id', 'desc')->get();
        foreach ($inventories as $inventory) {
            if (!empty($inventory->inventory)) {
                $user = Employee::where('emp_code', $inventory->inventory->issued_to)->first();
                if ($user) {
                    $branch_id_data = EmployeeBranch::where('branch_name', $user->department)->where('emp_code', $inventory->inventory->issued_to)->first();
                    $inventory->user = $user;
                    $inventory->user->branch_id = $branch_id_data->branch_id;
                }
            }
        }
        $file_name = time() . 'dispatchout_report.pdf';
        $pdf = PDF::loadView('daily_dispatchout_report', ['dispatches' => $inventories])->setPaper('a4', 'landscape')->save(public_path('dispatchout_reports/' . $file_name));
//        $pdf->download('dispatchin_report.pdf');
        $date = Carbon::parse(Carbon::today()->toDateString(), 'Asia/karachi');
        $format = $date->isoFormat('Do MMMM YYYY');
        $data = array(
            'dispatch_in_date' => $format,
            'file_link' => $file_name,
        );
        $user_emails = ['muhammadirfan5891@gmail.com', 'irfannadeem5@gmail.com'];
        Mail::send('emails.dispatchout_report_email', ['data' => $data], function ($message) use ($user) {
            $date = Carbon::parse(Carbon::today()->toDateString(), 'Asia/karachi');
            $format = $date->isoFormat('Do MMMM YYYY');
            $message->to('muhammadirfan5891@gmail.com')->subject
            ('Dispatch Out Report ' . $format);
            $message->from('itstore@efulife.com', 'Support IT Store');
        });
    }

    public function dispatchoutexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
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

        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {

                $user = Employee::where('emp_code', $inventory->inventory->issued_to ?? '')->first();
                if ($user) {
                    $inventory->user = $user;
                }

            }
        }
        $pdf = PDF::loadView('dispatchoutreport', ['dispatches' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('dispatchout_report.pdf');
    }

    public function dispatchoutexport_qrcode($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
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

        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                $inventory->inventory = Inventory::find($inventory->inventory_id);
                $user = Employee::where('emp_code', $inventory->inventory->issued_to ?? '')->first();
                if ($user) {
                    $inventory->user = $user;
                }

            }
        }
        $pdf = PDF::loadView('dispatchout_qrcode', ['inventories' => $inventories, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('dispatchout_report_qrcode.pdf');
    }

    public function reorderexport($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $from = date('Y-m-d', strtotime('-3 months'));
        $to = date('Y-m-d', strtotime('+1 day'));
        $records = array();
        $subcategories = Subcategory::where([[$fields]])->where('status', 1)->get();
        foreach ($subcategories as $subcategory) {
            $items_in_stock = Inventory::where('subcategory_id', $subcategory->id)->where('devicetype_id','!=',5)->where('issued_to', null)->whereNotIn('devicetype_id', [1])->count();
            $subcategory->in_stock = $items_in_stock;
            $subcategory->issued_count = 0;
            $inventories = Inventory::where('subcategory_id', $subcategory->id)->where('devicetype_id','!=',5)->whereNotNull('issued_to')->whereNotIn('devicetype_id', [1])->get();
            foreach ($inventories as $inv) {
                $subcategory->issued_count += Issue::where('inventory_id', $inv->id)->whereBetween('updated_at', [$from, $to])->count();
            }
            if ($items_in_stock <= $subcategory->threshold) {
                $records[] = $subcategory;
            }
        }
        $record['reorders'] = $records;
        //return $data;
        $pdf = PDF::loadView('reorderlevel_report', $record);
        return $pdf->download('reorderlevel_report.pdf');
    }

    public function slaexport($data)
    {
        $fields = (array)json_decode($data);
        date_default_timezone_set('Asia/karachi');
        $data = array();
        $fields = array_filter($data);
        unset($fields['_token']);
        $data['filters'] = $fields;
        $sla_data = SLA::where([[$fields]])->get();
        $data['reorders'] = $sla_data;
        $pdf = PDF::loadView('sla_report_view', ['sla_data' => $sla_data, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('sla.pdf');
    }

    public function slacomplainexport($data)
    {
        $fields = (array)json_decode($data);
        date_default_timezone_set('Asia/karachi');
        $data = array();
        $fields = array_filter($data);
        unset($fields['_token']);
        $data['filters'] = $fields;
        $sla_complain_data = SLAComplainLog::where([[$fields]])->get();
        $data['reorders'] = $sla_complain_data;
        $pdf = PDF::loadView('sla_complain_report_view', ['sla_complain_data' => $sla_complain_data, 'filters' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('sla_complain.pdf');
    }

    public function slaconsumptionexport($json_data)
    {
        $fields = (array)json_decode($json_data);

        date_default_timezone_set('Asia/karachi');
        $data = array();
        unset($fields['_token']);
        $data['months'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $data['diff'] = '';
        $data['year_y'] = '';
        $arr = array();

        $selected_year = Year::find($fields['year_id']);
        $data['diff'] = Carbon::parse($selected_year->year_start_date)->diffInMonths(Carbon::parse($selected_year->year_end_date), true);
        $data['year_y'] = Carbon::parse($selected_year->year_start_date)->format("Y");
        $log = SLAComplainLog::select(DB::raw('subcategory_id as subcategory_id'),
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
            ->where('year_id', $fields['year_id'])
            ->whereBetween('issue_occur_date', [$selected_year->year_start_date, $selected_year->year_end_date])
            ->groupBy(['subcategory_id','month'])
            ->get();

        $arr = array();
        foreach ($log as $log_data) {
            $sla = SLA::where('type_id',$log_data->type_id)->where('year_id',$log_data->year_id)
                ->where('subcategory_id',$log_data->subcategory_id)->first();
            $arr[$log_data->subcategory_id]['sub_cat_id'] = Subcategory::findorfail($log_data->subcategory_id)['sub_cat_name'];
            $arr[$log_data->subcategory_id]['vendor_id'] = Vendor::findorfail($log_data->vendor_id)['vendor_name'];
            $arr[$log_data->subcategory_id]['created_at'] = $log_data->created_at;
            $arr[$log_data->subcategory_id]['category_id'] = $log_data->category_id;
            $arr[$log_data->subcategory_id]['year_id'] = $log_data->year_id;
            $arr[$log_data->subcategory_id]['type_id'] = $log_data->type_id;
            $arr[$log_data->subcategory_id]['sla'] = $sla;
            $arr[$log_data->subcategory_id][$log_data->month]['month'] = $log_data->month;
            $arr[$log_data->subcategory_id][$log_data->month]['cost'] = $log_data->cost_occured;
        }
//        dd($data);
        $pdf = PDF::loadView('sla_consumption_report_view', ['arr' => $arr, 'data' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('sla_consumption_report.pdf');
    }


}
