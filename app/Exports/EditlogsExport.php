<?php

namespace App\Exports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class EditlogsExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Sub Category',
            'Make',
            'Model',
            'Product s#',
            'Purchase Date',
            'Po Number',
            'Vendor',
            'Warrenty Period',
            'Remarks',
            'Price',
            'Itemnature'
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
