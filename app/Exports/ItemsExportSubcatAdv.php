<?php

namespace App\Exports;

use App\Category;
use App\Inventory;
use App\Subcategory;
use App\User;
use App\Year;
use App\Dollar;
use App\Type;
use App\Budgetitem as Budget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\AfterSheet;
use Maatwebsite\Excel\Concerns\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
class ItemsExportSubcatAdv implements FromView,WithStyles,WithColumnWidths
{
    public $filters;

    public function __construct($data)
    {
        $this->filters = $data;
    }

    public function columnWidths(): array
    {
        return [
            'B' => 65,
            'H' => 65,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $cellRange = 'A1:H1';
        $sheet->getStyle('A5:H5')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('B')->getAlignment()->setWrapText(true);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
    }


    public function view(): View
    {
        $filters = json_decode($this->filters);
        dd($filters);
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

        return view('itemsexport_view_adv', [
            'capex_budget_items' => $data['capex_budget_items'],
            'opex_budget_items' => $data['opex_budget_items'],
            'year'  =>  $data['filter'],
            'category'  =>   $data['category_name']
        ]);
    }

}
