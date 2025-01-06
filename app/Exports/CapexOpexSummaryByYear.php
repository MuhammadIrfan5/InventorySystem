<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CapexOpexSummaryByYear implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $records;

    public function __construct($data)
    {
        $this->record = $data;
    }

    public function view(): View
    {
        $records = $this->record;
        $optionalData = $records['total'];
        unset($records['total']);
//        $records = array_merge($records,$value);
        return view('capexOpexSummaryDollar',['records'=>($records) , 'data' => $optionalData]);
    }
}
