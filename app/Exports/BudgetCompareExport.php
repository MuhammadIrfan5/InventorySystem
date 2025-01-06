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
class BudgetCompareExport implements FromView,WithStyles,WithColumnWidths
{
    public $filters;

    public function __construct($data)
    {
        $this->filters = $data;
    }

    public function columnWidths(): array
    {
        return [
            'B' => 30,
            'H' => 15
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
        $data = array();
        $data['filters'] = $filters;
        $data['capex_budget_year'] = Budget::select(
            'subcategory_id',
            'year_id',
            DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
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
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_prev_price_dollar'),
            DB::raw('SUM(unit_price_pkr) as myunit_prev_price_pkr'),
            DB::raw('SUM(total_price_dollar) as mytotal_price_dollar'),
            DB::raw('SUM(total_price_pkr) as mytotal_price_pkr'))
            ->where('year_id', $filters->from_year_id)
            ->where('category_id', $filters->category_id)
            ->where('type_id', 1)
            ->groupBy('subcategory_id', 'year_id')
            ->orderBy('subcategory_id')
            ->get();

        foreach ($data['capex_budget_year'] as $val) {
            foreach ($data['capex_budget_from'] as $budget) {
                if ($val->subcategory_id == $budget->subcategory_id) {
                    $val->from_year_budget_amount = $budget->myunit_prev_price_pkr;
                    $val->from_year_budget_qty = $budget->myqty;
                    $val->to_year_budget_qty = $val->myqty;
                    $val->from_year_u_price_pkr = $budget->myunit_prev_price_pkr;
                    $val->from_year_u_price_dollar = $budget->myunit_prev_price_dollar;
                    $val->to_year_u_price_pkr = $val->myunit_price_pkr;
                    $val->to_year_u_price_dollar = $val->myunit_price_dollar;
                    if ($val->myunit_price_dollar != 0) {
                        $val->percentage = (($val->myunit_price_dollar - $budget->myunit_prev_price_dollar) / $val->myunit_price_dollar) * 100;
                    }
                }
            }
        }

        $data['opex_budget_year'] = Budget::select(DB::raw('group_concat(description) as mydescription'),
            DB::raw('group_concat(remarks) as remarks'),
            'subcategory_id',
            'year_id',
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_price_dollar'),
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
            DB::raw('SUM(qty) as myqty'),
            DB::raw('SUM(unit_price_dollar) as myunit_prev_price_dollar'),
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

        foreach ($data['opex_budget_year'] as $val) {
            foreach ($data['opex_budget_from'] as $budget) {
                if ($val->subcategory_id == $budget->subcategory_id) {
                    $val->from_year_budget_amount = $budget->myunit_prev_price_pkr;
                    $val->from_year_budget_qty = $budget->myqty;
                    $val->to_year_budget_qty = $val->myqty;
                    $val->from_year_u_price_pkr = $budget->myunit_prev_price_pkr;
                    $val->from_year_u_price_dollar = $budget->myunit_prev_price_dollar;
                    $val->to_year_u_price_pkr = $val->myunit_price_pkr;
                    $val->to_year_u_price_dollar = $val->myunit_price_dollar;
                    if ($val->myunit_price_dollar != 0) {
                        $val->percentage = (($val->myunit_price_dollar - $budget->myunit_prev_price_dollar) / $val->myunit_price_dollar) * 100;
                    }
                }
            }
        }

        if (!$data['capex_budget_year']->isEmpty() && !$data['opex_budget_year']->isEmpty()) {
            $data['prev'] = collect($data['capex_budget_year'])->merge($data['opex_budget_year']);
        } else if ($data['opex_budget_year']->isEmpty()) {
            $data['prev'] = collect($data['capex_budget_year']);
        } else {
            $data['prev'] = collect($data['opex_budget_year']);
        }


        return view('itemexport_buget_compare',compact('data'));
    }

}
