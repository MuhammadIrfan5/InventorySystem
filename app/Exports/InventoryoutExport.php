<?php

namespace App\Exports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
class InventoryoutExport implements FromCollection, WithHeadings,WithColumnWidths
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
            'Issue to',
            'Location',
//            'Issue By',
            'Issue Date',
            'Purchase Date',
//            'Initial Status',
//            'Current Condition',
            'Base Remarks',
            'Issuance Remarks'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 30,
            'I' => 20,
            'J' => 30
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
