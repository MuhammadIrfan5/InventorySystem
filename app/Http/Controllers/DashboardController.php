<?php

namespace App\Http\Controllers;

use App\Department;
use App\Dispatchin;
use App\Employee;
use App\EmployeeBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Inventory;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pieChartlist = new Inventory();
        $pieChartlist = $pieChartlist->where('devicetype_id', '!=', 1)
            ->whereNull('issued_to')
            ->whereNotIn('category_id', [162, 161])
//            ->whereNotIn('subcategory_id', [860, 861,51,88])
            ->where('product_sn', 'not like', "%-CR-%")
            ->select('category_id','subcategory_id', DB::raw('count(*) as count'))->distinct('subcategory_id')
            ->orderBy('count', 'DESC')
            ->groupBy('subcategory_id')
            ->get();
        $pieChart = [];
        foreach ($pieChartlist as $item) {
            if ($item->count > 10) {
                $pieChart[] = [
//                    $item-> subcategory_id,
                    'month_name' => $item->subcategory->sub_cat_name .'-'.$item->category->category_name. ' (' . $item->count . ')' ?? '',
                    'count' => $item->count,
                ];
            }
        }

        /*Bar Chart*/
        $pieChartlist1 = new Inventory();
        $pieChartlist1 = $pieChartlist1->where('devicetype_id', '!=', 1)->whereNull('issued_to')->where('product_sn', 'not like', "%-CR-%")
            ->select('category_id','subcategory_id', DB::raw('count(*) as count'))->distinct('subcategory_id')
            ->orderBy('count', 'ASC')
            ->groupBy('subcategory_id')
            ->get();
        $pieChart1 = [];
        $barChart1 = [];
        foreach ($pieChartlist1 as $item) {
            if ($item->subcategory->threshold > 0) {
                $pieChart1[] = [
                    'month_name' => $item->subcategory->sub_cat_name ?? '',
                    'count' => $item->count,
                ];
                $barChart1[] = [
                    'subcategoryName' => $item->subcategory->sub_cat_name.' ('.$item->category->category_name.')' ?? '',
                    'threshold' => $item->subcategory->threshold,
                ];
            }
        }

        /*Dispatch In Bar Chart*/
        $inventoryDispatchins = Dispatchin::select('inventory_id')->where('created_at', '>', Carbon::now()->subDays(5))->get();
        $ids = [];
        $branchInventory = array();
        foreach ($inventoryDispatchins as $item) {
            array_push($ids, $item->inventory_id);
        }
        $list = Inventory::whereIn('id', $ids);
        $list = $list->select('subcategory_id','branch_id', DB::raw('count(*) as count'))
            ->orderBy('count', 'DESC')
            ->groupBy('branch_id')
            ->get();
        foreach ($list as $i) {
            $branchInventory[] = [
                'label' => EmployeeBranch::where('branch_id',$i->branch_id)->first()['branch_name']??'',
                'y' => $i->count,
            ];
        }
        return view('dashboard', ["barChart" => $barChart1, 'pieChart' => $pieChart, 'pieChart1' => $pieChart1, 'branchInventory' => $branchInventory]);
    }

}
