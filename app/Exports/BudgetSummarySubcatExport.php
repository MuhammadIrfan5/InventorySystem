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
class BudgetSummarySubcatExport implements FromView,WithStyles,WithColumnWidths
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
//        $sheet->getStyle('A5:H5')->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
    }


    public function view(): View
    {
        $filters = json_decode($this->filters);
//        dd($filters);
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
            $inv[] = Inventory::select('item_price','id')->whereIn('year_id', $prev_year_id)->where('category_id', $cat_id->category_id)->sum('item_price');
        }
        $data['actual_used'] = array_sum($inv) ? array_sum($inv) : 0;
        $data['dollar_rate'] = Dollar::where('year_id',$filters->yearid)->first();
        $pre_year_name = array();
        foreach ($filters->prev_year_id as $key => $prev_year_name){
            $pre_year_name[$key]['name'] = $prev_year_name->year;
            $pre_year_name[$key]['id'] = $prev_year_name->id;
        }
        $data['prev_year_name'] = $pre_year_name;
        $data['year_id'] = $filters->yearid;
        $data['year_name'] = $filters->year_name;

        return view('itemexport_budget_summary',compact('data'));
    }

}
