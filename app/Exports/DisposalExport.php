<?php

namespace App\Exports;

use App\Disposal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class DisposalExport implements FromCollection, WithHeadings
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
            'Make / Model',
            'Product s#',
            'Disposal Status',
            'Purchase Date',
            'Disposal Date',
            'Handed Over Date',
            'Remarks',
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
