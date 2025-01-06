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
class BudgetCompareExportNew implements FromView,WithStyles,WithColumnWidths
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
        return view('itemexport_budget_compare_new',$data,compact('filters'));
    }

}
