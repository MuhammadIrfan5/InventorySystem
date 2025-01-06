<?php

namespace App\Exports;

use App\SlaComplainLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class SlaComplainExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Service Name',
            'Vendor',
            'Issue Product SN',
            'Issue Product Make',
            'Issue Product Model',
            'Issued To',
            'Replace Product SN',
            'Replace Product Make',
            'Replace Product Model',
            'Type',
            'Created by',
            'Created At'
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
