<?php

namespace App\Http\Controllers;

use App\BudgetPlanIT;
use App\Exports\BudgetCompleteExport;
use App\Exports\CapexOpexSummaryByYear;
use App\Exports\ItemsExportSubcatAdv;
use App\Makee;
use App\Modal;
use App\Rturn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Subcategory;
use App\Grn;
use App\Gin;
use App\SLA;
use App\SLAComplainLog;
use App\Inventory;
use App\InventoryInventory;
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
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BudgetExport;
use App\Exports\BudgetExport2;
use App\Exports\ItemsExport;
use App\Exports\SLAConsumptionExport;
use App\Exports\ItemsExportSubcat;
use App\Exports\BudgetCompareExport;
use App\Exports\BudgetCompareExportNew;
use App\Exports\InventoryExport;
use App\Exports\EditlogsExport;
use App\Exports\InventoryinExport;
use App\Exports\InventoryoutExport;
use App\Exports\InvoiceInventoryExport;
use App\Exports\BalanceExport;
use App\Exports\BincardExport;
use App\Exports\AssetrepairingExport;
use App\Exports\DisposalExport;
use App\Exports\DispatchinExport;
use App\Exports\DispatchoutExport;
use App\Exports\VendorbuyingExport;
use App\Exports\ReorderlevelExport;
use App\Exports\SlaExport;
use App\Exports\SlaComplainExport;
use App\Exports\BudgetSummarySubcatExport;

class ExcelController extends Controller
{

    public function export_budget($data)
    {
        $filters = json_decode($data);
        $year = Year::find($filters->yearid);
        $category = Category::find($filters->catid);
        return Excel::download(new ItemsExport($data), $category->category_name . '_report_' . $year->year . '.xlsx');
    }

    public function export_budget_subcat($data)
    {
        $filters = json_decode($data);
        $year = Year::find($filters->yearid);
        $category = Category::find($filters->catid);
        return Excel::download(new ItemsExportSubcatAdv($data), $category->category_name . '_report_' . $year->year . '.xlsx');
    }

    public function export_budget_subcat_adv($data)
    {
        $filters = json_decode($data);
        $year = Year::find($filters->yearid);
        $category = Category::find($filters->catid);
        return Excel::download(new ItemsExportSubcat($data), $category->category_name . '_report_' . $year->year . '.xlsx');
    }

    public function export_budget_summary_subcat($data)
    {
        $filters = json_decode($data);
        $year = Year::find($filters->yearid);
        return Excel::download(new BudgetSummarySubcatExport($data), $year->year_name . '_report_' . $year->year . '.xlsx');
    }

    public function export_compare_budget_subcat($data)
    {
        $filters = json_decode($data);
        $year = Year::find($filters->to_year_id);
        return Excel::download(new BudgetCompareExport($data), $year->year . '_report_' . '.xlsx');
    }

    public function export_compare_budget_subcat_new($data)
    {
        $filters = json_decode($data);
        $year = Year::find($filters->to_year_id);
        return Excel::download(new BudgetCompareExportNew($data), $year->year . '_report_' . '.xlsx');
    }

    public function export_complete_summary(){
        $budget = BudgetPlanIT::all();
        $summary=[];
        foreach ($budget as $item) {
            $summary[]=[
                Employee::where('emp_code',$item->employee_code)->first()['name'],
                Employee::where('emp_code',$item->employee_code)->first()['department'],
                Year::find($item->year_id)['year'],
                ];
            foreach ($item->planBudgetRelation as $i) {
                $summary[]=[

                    Category::find($i->category_id)['category_name'],
                    Subcategory::find($i->subcategory_id)['sub_cat_name'],
                    $i->upgraded_qty,
                    $i->new_qty,
                    $i->approx_cost,
                    $i->remarks,
                    $i->approx_cost,
                ];
            }
        }
        return Excel::download(new BudgetCompleteExport(json_encode($summary)), 'Summaryreport_' . 23 . '.xlsx');

    }
    public function export_summary($data)
    {
        $budget = Budget::where('year_id', $data)->first();
        $record = array();
        if (!empty($budget)) {
            $grand_u_d = 0;
            $grand_u_p = 0;
            $grand_t_d = 0;
            $grand_t_p = 0;
            $grand_qty = 0;
            $grand_c = 0;
            $grand_r = 0;
            $grand_c_u_d = 0;
            $grand_c_u_p = 0;
            $grand_r_t_d = 0;
            $grand_r_t_p = 0;
            $types = Type::all();
            $year_data = Year::find($data);
            if ($year_data->year_start_date != null && $year_data->year_end_date != null) {
                foreach ($types as $type) {
                    $unit_b_d = 0;
                    $unit_b_p = 0;
                    $total_b_d = 0;
                    $total_b_p = 0;
                    $t_qty = 0;
                    $c = 0;
                    $r = 0;
                    $c_b_d = 0;
                    $c_b_p = 0;
                    $r_b_d = 0;
                    $r_b_p = 0;
                    $record[] = (object)array('', '', '', '', '', $type->type, '', '', '', '', '', '');
                    $category = Category::where('status', 1)->get();
                    foreach ($category as $cat) {
                        $consumed_price_dollar = 0;
                        $consumed_price_pkr = 0;
                        $remaining_price_dollar = 0;
                        $remaining_price_pkr = 0;
                        $fetch = Inventory::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->where('devicetype_id', '!=', 5)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        foreach ($fetch as $get) {
                            $consumed_price_dollar += round($get->item_price) / $get->dollar_rate;
                            $consumed_price_pkr += round($get->item_price);
                        }
                        $cat['unit_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('unit_price_dollar');
                        $cat['unit_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('unit_price_pkr');
                        $cat['total_price_dollar'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('total_price_dollar');
                        $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('total_price_pkr');
                        $cat['qty'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('qty');
                        $cat['consumed'] = Inventory::where('category_id', $cat->id)->where('devicetype_id', '!=', 5)->where('year_id', $data)->where('type_id', $type->id)->count();
                        $cat['consumed_price_dollar'] = $consumed_price_dollar;
                        $cat['consumed_price_pkr'] = $consumed_price_pkr;
                        $cat['remaining_price_dollar'] = ($cat->total_price_dollar - $consumed_price_dollar);
                        $cat['remaining_price_pkr'] = ($cat->total_price_pkr - $consumed_price_pkr);
                        $cat['remaining'] = ($cat->qty - $cat->consumed);

                        $unit_b_d += $cat->unit_price_dollar;
                        $unit_b_p += $cat->unit_price_pkr;
                        $total_b_d += $cat->total_price_dollar;
                        $total_b_p += $cat->total_price_pkr;
                        $t_qty += $cat->qty;
                        $c += $cat->consumed;
                        $r += $cat->remaining;
                        $c_b_d += $cat->consumed_price_dollar;
                        $c_b_p += $cat->consumed_price_pkr;
                        $r_b_d += $cat->remaining_price_dollar;
                        $r_b_p += $cat->remaining_price_pkr;

                        unset($cat->id);
                        unset($cat->threshold);
                        unset($cat->status);
                        unset($cat->created_at);
                        unset($cat->updated_at);
                        $record[] = $cat;
                    }
                    $record[] = (object)array('Total', $unit_b_d, $unit_b_p, $total_b_d, $total_b_p, $t_qty, $c, $c_b_d, $c_b_p, $r_b_d, $r_b_p, $r);
                    $grand_u_d += $unit_b_d;
                    $grand_u_p += $unit_b_p;
                    $grand_t_d += $total_b_d;
                    $grand_t_p += $total_b_p;
                    $grand_qty += $t_qty;
                    $grand_c += $c;
                    $grand_r += $r;
                    $grand_c_u_d += $c_b_d;
                    $grand_c_u_p += $c_b_p;
                    $grand_r_t_d += $r_b_d;
                    $grand_r_t_p += $r_b_p;
                }
            }
            $record[] = (object)array('Grand Total', $grand_u_d, $grand_u_p, $grand_t_d, $grand_t_p, $grand_qty, $grand_c, $grand_c_u_d, $grand_c_u_p, $grand_r_t_d, $grand_r_t_p, $grand_r);

        }
        $year = Year::find($data);
        return Excel::download(new BudgetExport(json_encode($record)), 'Summaryreport_' . $year->year . '.xlsx');
    }

    public function export_summary2($data)
    {
        $budget = Budget::where('year_id', $data)->first();
        $record = array();
        if (!empty($budget)) {
            $grand_u_p = 0;
            $grand_t_p = 0;
            $grand_qty = 0;
            $grand_c = 0;
            $grand_r = 0;
            $grand_c_u_p = 0;
            $grand_r_t_p = 0;
            $types = Type::all();
            $year_data = Year::find($data);
            if ($year_data->year_start_date != null && $year_data->year_end_date != null) {
                foreach ($types as $type) {
                    $unit_b_p = 0;
                    $total_b_p = 0;
                    $t_qty = 0;
                    $c = 0;
                    $r = 0;
                    $c_b_p = 0;
                    $r_b_p = 0;
                    $record[] = (object)array('', '', '', $type->type, '', '', '', '');
                    $category = Category::where('status', 1)->get();
                    foreach ($category as $cat) {
                        $consumed_price_pkr = 0;
                        $remaining_price_pkr = 0;
                        $fetch = Inventory::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->where('devicetype_id', '!=', 5)->whereBetween('purchase_date', [$year_data->year_start_date, $year_data->year_end_date])->get();
                        foreach ($fetch as $get) {
                            $consumed_price_pkr += round($get->item_price);
                        }
                        $cat['unit_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('unit_price_pkr');
                        $cat['total_price_pkr'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('total_price_pkr');
                        $cat['qty'] = Budget::where('category_id', $cat->id)->where('year_id', $data)->where('type_id', $type->id)->sum('qty');
                        $cat['consumed'] = Inventory::where('category_id', $cat->id)->where('devicetype_id', '!=', 5)->where('year_id', $data)->where('type_id', $type->id)->count();
                        $cat['consumed_price_pkr'] = $consumed_price_pkr;
                        $cat['remaining_price_pkr'] = ($cat->total_price_pkr - $consumed_price_pkr);
                        $cat['remaining'] = ($cat->qty - $cat->consumed);

                        $unit_b_p += $cat->unit_price_pkr;
                        $total_b_p += $cat->total_price_pkr;
                        $t_qty += $cat->qty;
                        $c += $cat->consumed;
                        $r += $cat->remaining;
                        $c_b_p += $cat->consumed_price_pkr;
                        $r_b_p += $cat->remaining_price_pkr;

                        unset($cat->id);
                        unset($cat->threshold);
                        unset($cat->status);
                        unset($cat->created_at);
                        unset($cat->updated_at);
                        $record[] = $cat;
                    }
                    $record[] = (object)array('Total', $unit_b_p, $total_b_p, $t_qty, $c, $c_b_p, $r_b_p, $r);
                    $grand_u_p += $unit_b_p;
                    $grand_t_p += $total_b_p;
                    $grand_qty += $t_qty;
                    $grand_c += $c;
                    $grand_r += $r;
                    $grand_c_u_p += $c_b_p;
                    $grand_r_t_p += $r_b_p;
                }
            }
            $record[] = (object)array('Grand Total', $grand_u_p, $grand_t_p, $grand_qty, $grand_c, $grand_c_u_p, $grand_r_t_p, $grand_r);
        }
        $year = Year::find($data);
        return Excel::download(new BudgetExport2(json_encode($record)), 'Summaryreport_' . $year->year . '.xlsx');
    }

    public function export_capexOpexSummaryDollar($data)
    {
        $capexTotal = 0;
        $opexTotal = 0;
        $value = [];
        $total = 0;
        foreach (json_decode($data, true) as $datum) {
            if ($datum['type'] == "Capital Expenditure") {
                $capexTotal += $datum['dollarAmount'];
            } else {
                $opexTotal += $datum['dollarAmount'];
            }
        }
        $value['total'] = [
            'capexTotal' => $capexTotal,
            'opexTotal' => $opexTotal,
            'total' => $capexTotal + $opexTotal,
        ];
        $records = array();
        $record=json_decode($data,true);
        $records=array_merge($record,$value);
        return Excel::download(new CapexOpexSummaryByYear($records), 'CopexOpexSummaryreport'  . '.xlsx');
    }

    public function export_inventory($data)
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
                ->whereBetween('purchase_date', [$from, date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->where('devicetype_id', '!=', 5)
                ->orderBy('id', 'desc')->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)
                ->whereBetween('purchase_date', [$from, date('Y-m-d', strtotime('+1 day'))])
                ->whereNotIn('status', [0])
                ->where('devicetype_id', '!=', 5)
                ->orderBy('id', 'desc')->get();
        } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where($key, $op, $val)
                ->whereBetween('purchase_date', ['', date('Y-m-d', $to)])
                ->whereNotIn('status', [0])
                ->where('devicetype_id', '!=', 5)
                ->orderBy('id', 'desc')->get();
        } else {
            $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where($key, $op, $val)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
        }
        $record = array();
        foreach ($inventories as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
            $record[] = (object)array(
                'make' => $inv->make_id ? $inv->make->make_name : '',
                'model' => $inv->model_id ? $inv->model->model_name : '',
                'type' => $inv->type_id ? $inv->type->type : '',
                'year' => $inv->year_id ? $inv->year->year : '',
                'product_sn' => $inv->product_sn,
                'purchase_date' => date('d-M-Y', strtotime($inv->purchase_date)),
                'subcategory' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'item_price' => round($inv->item_price),
                'user' => empty($inv->user) ? '' : $inv->user->name,
                'location' => empty($inv->location) ? '' : $inv->location->location,
                'inventorytype' => empty($inv->inventorytype) ? '' : $inv->inventorytype->inventorytype_name,
                'devicetype' => empty($inv->devicetype) ? '' : $inv->devicetype->devicetype_name,
                'remarks' => $inv->remarks
            );
        }

        return Excel::download(new InventoryExport(json_encode($record)), 'inventoryreport.xlsx');
    }

    public function export_invoice_inventory($data)
    {
//        dd($data);
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
        $record = array();
        $category = array();
        $sub_category = array();
        $categoryList = array();
        foreach ($inventories as $inv) {
            $user = Employee::where('emp_code', $inv->issued_to)->first();
            if ($user) {
                $inv['user'] = $user;
            }
//            $inv->cat_name = explode(',', $inv->category_id);
            $categoryList = explode(',', $inv->category_id);
            ($categoryList);
            $inv->sub_cat_name = explode(',', $inv->subcategory_id);
            foreach ($inv->cat_name as $cat) {
                $category[] = Category::find($cat)['category_name'];
            }
            foreach ($inv->sub_cat_name as $subcat) {
                $sub_category = Subcategory::find($subcat)['sub_cat_name'] . ',';
            }
            $record[] = (object)array(
                'year' => empty($inv->year_id) ? '' : $inv->year->year,
                'invoice_number' => empty($inv->invoice_number) ? '' : $inv->invoice_number,
                'invoice_date' => $inv->invoice_date ?? '',
                'category_id' =>implode(',',$category),
                'subcategory' => $sub_category,
                'vendor' => $inv->vendor->vendor_name,
                'item_price' => round($inv->item_price),
                'tax' => $inv->tax,
//                'item_price_tax' => round($inv->item_price_tax),
                'contract_issue_date' => empty($inv->contract_issue_date) ? '' : $inv->contract_issue_date,
                'contract_end_date' => empty($inv->contract_end_date) ? '' : $inv->contract_end_date,
            );
        }
        dd('us',$record);
        return Excel::download(new InvoiceInventoryExport(json_encode($record)), 'invoiceinventoryreport.xlsx');
    }

    public function export_editlogs($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
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
        $record = array();
        foreach ($inventories as $inv) {
            $record[] = (object)array(
                'subcategory' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'product_sn' => $inv->product_sn,
                'make' => $inv->make_id ? $inv->make->make_name : '',
                'model' => $inv->model_id ? $inv->model->model_name : '',
                'purchase_date' => date('d-M-Y', strtotime($inv->purchase_date)),
                'po_number' => $inv->po_number,
                'vendor' => empty($inv->vendor) ? '' : $inv->vendor->vendor_name,
                'warrenty_period' => $inv->warrenty_period,
                'remarks' => $inv->remarks,
                'item_price' => round($inv->item_price),
                'itemnature_name' => empty($inv->itemnature) ? '' : $inv->itemnature->itemnature_name

            );
        }

        return Excel::download(new EditlogsExport(json_encode($record)), 'inventoryeditlogsreport.xlsx');
    }

    public function export_inventoryin($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['from_date']);
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereBetween('created_at', [$from, date('Y-m-d', $to)])
                ->whereNull('issued_to')->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
            $from = $fields['from_date'];
            unset($fields['from_date']);
            $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereBetween('created_at', [$from, date('Y-m-d', strtotime('+1 day'))])
                ->whereNull('issued_to')->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else if (!isset($fields['from_date']) && isset($fields['to_date'])) {
            $to = strtotime($fields['to_date'] . '+1 day');
            unset($fields['to_date']);
            $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->whereBetween('created_at', ['', date('Y-m-d', $to)])
                ->whereNull('issued_to')->whereNotIn('status', [0])
                ->orderBy('id', 'desc')->get();
        } else {
            if ( isset($fields['purchase_date_from']) && isset($fields['purchase_date_to'])) {
                $inventories = new Inventory();
                $inventories = $inventories->whereNotIn('devicetype_id', ['1', '5'])->where('product_sn', 'not like', "%-CR-%")->whereNotIn('status', [0])->orderBy('id', 'desc')->whereNull('issued_to');
                $inventories = $inventories->whereBetween('purchase_date', [$fields['purchase_date_from'], $fields['purchase_date_to']])->get();
            } else {
                unset($fields['purchase_date_from']);
                unset($fields['purchase_date_to']);
                $inventories = Inventory::where([[$fields]])->whereNull('issued_to')->whereNotIn('devicetype_id', ['1', '5'])->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }
        $record = array();
        foreach ($inventories as $inv) {
            $inv->added_by = User::find($inv->added_by);
            $record[] = (object)array(
                'subcategory' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'product_sn' => $inv->product_sn,
                'make' => $inv->make_id ? $inv->make->make_name : '',
                'model' => $inv->model_id ? $inv->model->model_name : '',
                'item_price' => round($inv->item_price),
                'po_number' => $inv->po_number,
                'purchase_date' => $inv->purchase_date,
                'dc_number' => '',
                'vendor' => empty($inv->vendor) ? '' : $inv->vendor->vendor_name,
                'initial_status' => empty($inv->inventorytype) ? '' : $inv->inventorytype->inventorytype_name,
                'current_condition' => empty($inv->devicetype) ? '' : $inv->devicetype->devicetype_name,
                'remarks' => $inv->remarks,
                'return_remarks' => $inv->return_remarks = Rturn::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first()['remarks'],
                'enter_by' => empty($inv->added_by) ? '' : $inv->added_by->name

            );
        }
        return Excel::download(new InventoryinExport(json_encode($record)), 'inventoryinreport.xlsx');
    }

    public function export_inventoryout($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $dept_id = $fields['dept_id'] ?? null;
        unset($fields['dept_id']);
        $key = 'issued_to';
        $op = '>';
        $val = 0;
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
            $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where($key, $op, $val)->whereIn('id', $ids)->orderBy('id', 'desc')->get();
        } else {
            if(isset($fields['purchase_date_from']) || isset($fields['purchase_date_to'])){
                $inventories =new Inventory();
                $inventories=$inventories->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->whereNotIn('status', [0])->orderBy('id', 'desc');
                $inventories=$inventories->whereBetween('purchase_date',[$fields['purchase_date_from'],$fields['purchase_date_to']])->get();
            }
            else{
                $inventories = Inventory::where([[$fields]])->where('issued_to', '!=', null)->whereNotIn('status', [0])->orderBy('id', 'desc')->get();
            }
        }
//        dd('US',$inventories);
//        $items = array();
//        foreach ($inventories as $inv) {
//            $inv->user = Employee::where('emp_code', $inv->issued_to)->first();
//            $inv->issued_by = User::find($inv->issued_by);
//            $inv->issue_date = Issue::where('inventory_id', $inv->id)->select('created_at')->orderBy('id', 'desc')->first();
//            $inv->issue_remarks = Issue::where('inventory_id', $inv->id)->select('remarks')->latest('created_at')->first();
//            if (!empty($dept_id)&& $dept_id == $inv->user->department) {
//                $items[] = $inv;
//            }
//        }
//        if ($dept_id) {
//            $inventories = $items;
//        }

        $record = array();
        foreach ($inventories as $inv) {
            $record[] = (object)array(
                'subcategory' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'product_sn' => $inv->product_sn,
                'make' => $inv->make_id ? $inv->make->make_name : '',
                'model' => $inv->model_id ? $inv->model->model_name : '',
                'issued_to' => empty($inv->issued_to) ? '' : $inv->employee->name,
//                'location' => empty($inv->location) ? '' : $inv->location->location,
                'location' => empty($inv->issued_to) ? '' : $inv->employee->department,
//                'issued_by' => empty($inv->issued_by) ? '' : $inv->issued_by->name,
                'issued_date' => empty($inv->issue) ? '' : date('d-M-Y', strtotime($inv->issue->created_at)),
                'purchase_date'=>empty($inv->purchase_date)?'':date('d-M-Y' ,strtotime($inv->purchase_date)),
//                'initial_status' => empty($inv->inventorytype) ? '' : $inv->inventorytype->inventorytype_name,
//                'current_condition' => empty($inv->devicetype) ? '' : $inv->devicetype->devicetype_name,
                'remarks' => $inv->remarks,
                'issuance_remarks' => $inv->issue_remarks['remarks']
            );
        }
        return Excel::download(new InventoryoutExport(json_encode($record)), 'inventoryoutreport.xlsx');
    }
    public function export_inventoryout1($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = json_decode($data,true);
        $fields = array_filter($fields);
        $dept_id = $fields['dept_id'] ?? null;
        unset($fields['dept_id']);
        $key = 'issued_to';
        $op = '>';
        $val = 0;
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
//                where($key, $op, $val)
            $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where('carry_forward_status_id', '!=', 3)->where('issued_to', '!=', null)->whereIn('id', $ids)->orderBy('id', 'desc')->get();
        } else {
            if (!empty($fields['purchase_date_from']) || !empty($fields['purchase_date_to'])) {
                $inventories = Inventory::whereBetween('purchase_date', [$fields['purchase_date_from'], $fields['purchase_date_to']])->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->orderBy('id', 'desc')->get();

            } else {
                $inventories = Inventory::where([[$fields]])->where('devicetype_id', '!=', '5')->where('issued_to', '!=', null)->orderBy('id', 'desc')->get();
            }
        }

        $record = array();
        foreach ($inventories as $inv) {
            $record[] = (object)array(
                'subcategory' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'product_sn' => $inv->product_sn,
                'make' => $inv->make_id ? $inv->make->make_name : '',
                'model' => $inv->model_id ? $inv->model->model_name : '',
                'issued_to' => $inv->employee->name ?? '',
//                'location' => empty($inv->location) ? '' : $inv->location->location,
                'location' =>  $inv->employee->department??'',
//                'issued_by' => empty($inv->issued_by) ? '' : $inv->issued_by->name,
                'issued_date' => empty($inv->issue) ? '' : date('d-M-Y', strtotime($inv->issue->created_at)),
                'purchase_date'=>empty($inv->purchase_date)?'':date('d-M-Y' ,strtotime($inv->purchase_date)),
//                'initial_status' => empty($inv->inventorytype) ? '' : $inv->inventorytype->inventorytype_name,
//                'current_condition' => empty($inv->devicetype) ? '' : $inv->devicetype->devicetype_name,
                'remarks' => $inv->remarks,
                'issuance_remarks' => $inv->issue_remarks['remarks']
            );
        }
        return Excel::download(new InventoryoutExport(json_encode($record)), 'inventoryoutreport.xlsx');
    }

    public function export_subcategory()
    {
        $record = array();
        $subcategories = Subcategory::where('status', 1)->orderBy('sub_cat_name',"ASC")->get();
        foreach ($subcategories as $subcat) {
            $record[] = (object)array(
                'category' => $subcat->category->category_name ?? '',
                'subcategory' => $subcat->sub_cat_name,
                'in' => 1,
                'out' => 1,
                'balance' => 1
            );
        }
        return Excel::download(new BalanceExport(json_encode($record)), 'subCategory.xlsx');
    }
    public function export_balance($data)
    {
        $fields = (array)json_decode($data);
        $record = array();
        $subcategories = Subcategory::where('status', 1)->get();
        foreach ($subcategories as $subcat) {
            $subcat->rem = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where('subcategory_id', $subcat->id)->where('issued_to', NULL)->count();
            $subcat->out = Inventory::where([[$fields]])->where('devicetype_id', '!=', 5)->where('subcategory_id', $subcat->id)->whereNotNull('issued_to')->count();
            $record[] = (object)array(
                'category' => $subcat->category->category_name ?? '',
                'subcategory' => $subcat->sub_cat_name,
                'in' => ($subcat->rem + $subcat->out),
                'out' => $subcat->out,
                'balance' => $subcat->rem
            );
        }
        return Excel::download(new BalanceExport(json_encode($record)), 'balancereport.xlsx');
    }

    public function export_bincard($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        if (isset($fields['from_date']) && isset($fields['to_date'])) {
            $from = $fields['from_date'];
            $to = strtotime($fields['to_date'] . '+1 day');
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
        $record = array();
        if (!empty($inventories)) {
            foreach ($inventories as $inv) {
                $inv->repairing = Repairing::where('item_id', $inv->id)->first();
                $inv->added_by = User::where('id', $inv->added_by)->first();
                $record[] = (object)array(
                    'subcategory' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                    'product_sn' => $inv->product_sn,
                    'make' => $inv->make_id ? $inv->make->make_name : '',
                    'model' => $inv->model_id ? $inv->model->model_name : '',
                    'location' => empty($inv->location) ? '' : $inv->location->location,
                    'initial_status' => empty($inv->inventorytype) ? '' : $inv->inventorytype->inventorytype_name,
                    'remarks' => $inv->remarks,
                    'action_date' => date('Y-m-d', strtotime($inv->updated_at)),
                    'actual_price' => number_format(round($inv->item_price), 2),
                    'cost_price' => empty($inv->repairing) ? '' : $inv->repairing->price_value,
                    'repaiting_remarks' => empty($inv->repairing) ? '' : $inv->repairing->remarks
                );
            }
        }
        return Excel::download(new BincardExport(json_encode($record)), 'bincardreport.xlsx');
    }

    public function export_assetrepairing($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $repairs = Repairing::where([[$fields]])->orderBy('item_id', 'desc')->get();
        $record = array();
        foreach ($repairs as $repair) {
            $total = $repair->actual_price_value + $repair->price_value;
            $repair->item->user = Employee::where('emp_code', $repair->item->issued_to)->first();
            $record[] = (object)array(
                'subcategory' => empty($repair->subcategory) ? '' : $repair->subcategory->sub_cat_name,
                'product_sn' => empty($repair->item) ? '' : $repair->item->product_sn,
                'make' => empty($repair->item->make) ? '' : $repair->item->make->make_name,
                'model' => empty($repair->item->model) ? '' : $repair->item->model->model_name,
                'issued_to' => empty($repair->item->user) ? '' : $repair->item->user->name,
                'location' => empty($repair->item->location) ? '' : $repair->item->location->location,
                'repairing_date' => date('d-M-Y', strtotime($repair->date)),
                'actual_price' => number_format($repair->actual_price_value, 2),
                'repairing_cost' => number_format($repair->price_value, 2),
                'cumulative_cost' => number_format($total, 2),
                'initial_status' => empty($repair->item->inventorytype) ? '' : $repair->item->inventorytype->inventorytype_name,
                'current_condition' => empty($repair->item->devicetype) ? '' : $repair->item->devicetype->devicetype_name,
                'remarks' => $repair->remarks
            );
        }
        return Excel::download(new AssetrepairingExport(json_encode($record)), 'assetrepairingreport.xlsx');
    }

    public function export_disposal($data)
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
        $record = array();
        if (!empty($inventories)) {
            foreach ($inventories as $inv) {
                $issue = Issue::where('inventory_id', $inv->inventory_id)->orderBy('id', 'DESC')->first();
                if ($issue) {
                    $user = Employee::where('emp_code', $issue->employee_id)->first();
                    if ($user) {
                        $inv->user = $user;
                    }
                }
                $record[] = (object)array(
                    'subcategory' => !empty($inv->subcategory) ? $inv->subcategory->sub_cat_name : '',
                    'make_model' => !empty($inv->inventory->make)?$inv->inventory->make->make_name.' / '.$inv->inventory->model->model_name:'',
                    'product_sn' => !empty($inv->inventory) ? $inv->inventory->product_sn : '',
                    'disposal_status' => !empty($inv->disposalstatus) ? $inv->disposalstatus->d_status : '',
                    'purchase_date' => !empty($inv->inventory) ? date('d-M-Y', strtotime($inv->inventory->purchase_date)) : '',
                    'disposal_date' => date('d-M-Y', strtotime($inv->dispose_date)),
                    'handover_date' => $inv->handover_date == null ? 'Null' : date('d-M-Y', strtotime($inv->handover_date)),
                    'remarks' => $inv->remarks
                );
            }
        }
        return Excel::download(new DisposalExport(json_encode($record)), 'assetdisposalreport.xlsx');
    }

    public function export_dispatchin($data)
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
        $record = array();
        if (!empty($inventories)) {
            foreach ($inventories as $inv) {
                if (!empty($inv->inventory)) {
                    $user = Employee::where('emp_code', $inv->inventory->issued_to)->first();
                    if ($user) {
                        $inv->user = $user;
                    }
                }
                $record[] = (object)array(
                    'date_in' => date('d-M-Y', strtotime($inv->dispatchin_date)),
                    'subcategory' => !empty($inv->subcategory) ? $inv->subcategory->sub_cat_name : '',
                    'product_sn' => !empty($inv->inventory) ? $inv->inventory->product_sn : '',
                    'assigned_to' => !empty($inv->user) ? $inv->user->name : '',
                    'branch' => !empty($inv->user) ? $inv->user->branch : '',
                    'br_code' => !empty($inv->user) ? $inv->user->branch_id : '',
                    'make' => !empty($inv->inventory->make) ? $inv->inventory->make->make_name : '',
                    'model' => !empty($inv->inventory->model) ? $inv->inventory->model->model_name : '',
                    'accessories' => !empty($inv->inventory) ? $inv->inventory->other_accessories : ''
                );
            }
        }
        return Excel::download(new DispatchinExport(json_encode($record)), 'dispatchinreport.xlsx');
    }

    public function export_dispatchout($data)
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
        $record = array();
        if (!empty($inventories)) {
            foreach ($inventories as $inv) {
                if (!empty($inv->inventory)) {
                    $user = Employee::where('emp_code', $inv->inventory->issued_to ?? '')->first();
                    if ($user) {
                        $inv->user = $user;
                    }
                }
                $record[] = (object)array(
                    'date_out' => date('d-M-Y', strtotime($inv->dispatchout_date)),
                    'subcategory' => !empty($inv->subcategory) ? $inv->subcategory->sub_cat_name : '',
                    'product_sn' => !empty($inv->inventory) ? $inv->inventory->product_sn : '',
                    'branch' => !empty($inv->user) ? $inv->user->branch : '',
                    'br_code' => !empty($inv->user) ? $inv->user->branch_id : '',
                    'insured' => $inv->insured,
                    'cost' => !empty($inv->inventory) ? number_format($inv->inventory->item_price, 2) : ''
                );
            }
        }

        return Excel::download(new DispatchoutExport(json_encode($record)), 'dispatchoutreport.xlsx');
    }

    public function export_vendorbuying($data)
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
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', $to)])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', $to)])->whereNotIn('status', [0])->sum('item_price');

            } else if (isset($fields['from_date']) && !isset($fields['to_date'])) {
                $from = $fields['from_date'];
                unset($fields['from_date']);
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', [$from, date('Y-m-d', strtotime('+1 day'))])->whereNotIn('status', [0])->sum('item_price');

            } else if (!isset($fields['from_d ate']) && isset($fields['to_date'])) {
                $to = strtotime($fields['to_date'] . '+1 day');
                unset($fields['to_date']);
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', ['', date('Y-m-d', $to)])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereBetween('updated_at', ['', date('Y-m-d', $to)])->whereNotIn('status', [0])->sum('item_price');

            } else {
                $array[$i]['subcategory'] = $sub->sub_cat_name;
                $array[$i]['vendor'] = Vendor::where('id', $fields['vendor_id'])->select('vendor_name')->first();
                $array[$i]['total_items'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereNotIn('status', [0])->count();
                $array[$i]['amount'] = Inventory::where('subcategory_id', $sub->id)->where('devicetype_id', '!=', 5)->where('vendor_id', $fields['vendor_id'])->whereNotIn('status', [0])->sum('item_price');
            }
            if ($array[$i]['total_items'] == 0) {
                unset($array[$i]);
            }
            $i++;
        }
        $inventories = $array;
        $record = array();
        if (!empty($inventories)) {
            foreach ($inventories as $inventory) {
                $record[] = (object)array(
                    'subcategory' => $inventory['subcategory'],
                    'vendor' => $inventory['vendor']->vendor_name,
                    'total_items' => number_format($inventory['total_items'], 2),
                    'amount' => number_format(round($inventory['amount']), 2),
                );
            }
        }
        return Excel::download(new VendorbuyingExport(json_encode($record)), 'vendorbuyingreport.xlsx');
    }

    public function export_reorderlevel($data)
    {
        date_default_timezone_set('Asia/karachi');
        $fields = (array)json_decode($data);
        $from = date('Y-m-d', strtotime('-3 months'));
        $to = date('Y-m-d', strtotime('+1 day'));
        $records = array();
        $subcategories = Subcategory::where([[$fields]])->where('status', 1)->get();
        foreach ($subcategories as $subcategory) {
            $items_in_stock = Inventory::where('subcategory_id', $subcategory->id)->where('devicetype_id', '!=', 5)->where('issued_to', null)->whereNotIn('devicetype_id', [1])->count();
            $subcategory->in_stock = $items_in_stock;
            $subcategory->issued_count = 0;
            $inventories = Inventory::where('subcategory_id', $subcategory->id)->where('devicetype_id', '!=', 5)->whereNotNull('issued_to')->whereNotIn('devicetype_id', [1])->get();
            foreach ($inventories as $inv) {
                $subcategory->issued_count += Issue::where('inventory_id', $inv->id)->whereBetween('updated_at', [$from, $to])->count();
            }
            if ($items_in_stock <= $subcategory->threshold) {
                $records[] = $subcategory;
            }
        }
        $record = array();
        foreach ($records as $reorder) {
            $record[] = (object)array(
                'subcategory' => $reorder->sub_cat_name,
                'threshold' => $reorder->threshold,
                'in_stock' => $reorder->in_stock,
                'issued_count' => $reorder->issued_count
            );
        }
        return Excel::download(new ReorderlevelExport(json_encode($record)), 'reorderlevelreport.xlsx');
    }

    public function export_sla($data)
    {
        $fields = (array)json_decode($data);
        date_default_timezone_set('Asia/karachi');
        $data = array();
        $fields = array_filter($data);
        unset($fields['_token']);
        $data['filters'] = $fields;
        $sla_data = SLA::where([[$fields]])->get();
        //$data['reorders'] = $sla_data;
        foreach ($sla_data as $inv) {
            $inv->added_by = User::find($inv->created_by);
            $data[] = (object)array(
                'service_name' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'vendor_name' => $inv->vendor->vendor_name,
                'agreement_start_date' => $inv->agreement_start_date ? $inv->agreement_start_date : '',
                'agreement_end_date' => $inv->agreement_end_date ? $inv->agreement_end_date : '',
                'poc_name' => $inv->vendor->contact_person,
                'poc_contact' => $inv->vendor->cell,
                'poc_email' => $inv->vendor->email,
                'created_by' => User::find($inv->created_by)['name'],
            );
        }
        return Excel::download(new SlaExport(json_encode($data)), 'slareport.xlsx');
    }

    public function export_sla_complain($data)
    {
        $fields = (array)json_decode($data);
        date_default_timezone_set('Asia/karachi');
        $data = array();
        $fields = array_filter($data);
        unset($fields['_token']);
        $data['filters'] = $fields;
        $sla_data = SLAComplainLog::where([[$fields]])->get();
        //$data['reorders'] = $sla_data;
        $replace_type = null;
        foreach ($sla_data as $inv) {
//            $inv->added_by = User::find($inv->added_by);
            if ($inv->replace_type == 1) {
                $replace_type = 'replace';
            } else if ($inv->replace_type == 2) {
                $replace_type = 'repair';
            } else {
                $replace_type = 'non replaceable';
            }
            $data[] = (object)array(
                'service_name' => empty($inv->subcategory) ? '' : $inv->subcategory->sub_cat_name,
                'vendor_name' => $inv->vendor->vendor_name,
                'issue_product_sn' => $inv->issue_product_sn ?? 'No SN',
                'issue_make_id' => Makee::find($inv->issue_make_id)['make_name'] ?? 'No Make',
                'issue_model_id' => Modal::find($inv->issue_model_id)['model_name'] ?? 'No Model',
                'issued_to' => Employee::where('emp_code', $inv->issued_to)->first()['name'] ?? 'Not Issued',
                'replace_product_sn' => $inv->replace_product_sn ?? 'No SN',
                'replace_product_make_id' => Makee::find($inv->replace_product_make_id)['make_name'] ?? 'No Make',
                'replace_product_model_id' => Modal::find($inv->replace_product_model_id)['model_name'] ?? 'No Model',
                'replace_type' => $replace_type,
                'added_by' => User::find($inv->added_by)['name'] ?? 'No User',
                'created_at' => $inv->created_at ?? 'No Date',
            );
        }
        return Excel::download(new SlaComplainExport(json_encode($data)), 'slacomplainreport.xlsx');
    }

    public function export_sla_consumption($json_data)
    {
        $filters = json_decode($json_data);
        $year = Year::find($filters->year_id);
        return Excel::download(new SLAConsumptionExport($json_data), 'sla_consumption_' . $year->year . '_report_' . '.xlsx');
    }

}
