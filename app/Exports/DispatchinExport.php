<?php

namespace App\Exports;

use App\Dispatchin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class DispatchinExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Date IN',
            'Item',
            'Product s#',
            'Assigned To',
            'Branch',
            'BR.Code',
            'Make',
            'Model',
            'Other Accessories',
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
