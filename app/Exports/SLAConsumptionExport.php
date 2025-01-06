<?php

namespace App\Exports;

use App\Category;
use App\Inventory;
use App\SLA;
use App\SLAComplainLog;
use App\Subcategory;
use App\User;
use App\Vendor;
use App\Year;
use App\Dollar;
use App\Type;
use App\Budgetitem as Budget;
use Carbon\Carbon;
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
class SLAConsumptionExport implements FromView,WithStyles,WithColumnWidths
{
    public $filters;

    public function __construct($json_data)
    {
        $this->filters = $json_data;
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
        $fields = (array)json_decode($this->filters);

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
        return view('itemexport_sla_consumption',compact('arr','data'));
    }

}
