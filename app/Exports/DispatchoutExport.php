<?php

namespace App\Exports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class DispatchoutExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Date OUT',
            'Item',
            'Product s#',
            'Branch',
            'BR.Code',
            'Insured',
            'Cost',
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
