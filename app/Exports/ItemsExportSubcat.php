<?php

namespace App\Exports;

use App\Category;
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
class ItemsExportSubcat implements FromView,WithStyles,WithColumnWidths
{
    public $filters;

    public function __construct($data)
    {
        $this->filters = $data;
    }
//    public function columnFormats(): array
//    {
//        return [
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//            'H' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
//        ];
//    }

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

//
//    public function headings(): array
//    {
//        return [
//            'Item',
//            'Description',
//            'Unit Cost $',
//            'Unit Cost PKR',
//            'Unit price PKR',
//            'Qty',
//            'One Off PKR',
//            'One Off Dollar',
//            'Remarks'
//        ];
//    }

    public function view(): View
    {
        $filters = json_decode($this->filters);
        $items_capex = Budget::select(DB::raw('group_concat(description) as mydescription'),
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

        $items_opex = Budget::select(DB::raw('group_concat(description) as mydescription'),
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
            ->orderBy('subcategory_id')
            ->get();

        $year_name = Year::find($filters->yearid);
        $category_name = Category::find($filters->catid);

        return view('itemsexport_view', [
            'opex_budget' => $items_opex,
            'capex_budget' => $items_capex,
            'year'  =>  $year_name->year,
            'category'  =>  $category_name->category_name
        ]);
    }

}
