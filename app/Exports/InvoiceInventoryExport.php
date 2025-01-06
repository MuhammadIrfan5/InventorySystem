<?php

namespace App\Exports;

use App\InventoryInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class InvoiceInventoryExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'Year',
            'Invoice Number',
            'Invoice Date',
            'Category',
            'Sub Category',
            'Vendor',
            'Price',
            'Tax',
//            'Price After Tax',
            'Contract Issued Date',
            'Contract End Date',

        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
