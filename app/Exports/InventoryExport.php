<?php

namespace App\Exports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class InventoryExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Make',
            'Model',
            'Type',
            'Year',
            'Product s#',
            'Purchase Date',
            'Sub Category',
            'Price',
            'Issued to',
            'Location',
            'Initial Status',
            'Current Condition',
            'Remarks'
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
