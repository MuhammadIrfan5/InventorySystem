<?php

namespace App\Exports;

use App\Repairing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class AssetrepairingExport implements FromCollection, WithHeadings
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
            'Issued To',
            'Location',
            'Repairing Date',
            'Actual Price',
            'Repairing Cost',
            'Cumulative Cost',
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
