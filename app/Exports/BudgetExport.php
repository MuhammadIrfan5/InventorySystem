<?php

namespace App\Exports;

use App\Budgetitem as Budget;
use App\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class BudgetExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'category_name',
            'unit_price_dollar',
            'unit_price_pkr',
            'total_price_dollar',
            'total_price_pkr',
            'qty',
            'consumed',
            'consumed_price_dollar',
            'consumed_price_pkr',
            'remaining_price_dollar',
            'remaining_price_pkr',
            'remaining'
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
