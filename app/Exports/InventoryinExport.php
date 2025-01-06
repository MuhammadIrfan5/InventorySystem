<?php

namespace App\Exports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class InventoryinExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Item Category',
            'Product s#',
            'Make',
            'Model',
            'Price',
            'Po Number',
            'Purchase Date',
            'DC No',
            'Vendor',
            'Initial Status',
            'Current Condition',
            'Base Remarks',
            'Return Remarks',
            'Enter By'
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
