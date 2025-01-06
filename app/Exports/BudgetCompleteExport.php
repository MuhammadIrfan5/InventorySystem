<?php

namespace App\Exports;

use App\Budgetitem as Budget;
use App\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class BudgetCompleteExport implements FromCollection, WithHeadings
{
    public $record;
    public function __construct($data)
    {
        $this->record = $data;
    }

    public function headings(): array
    {
        return [
            'User Name',
            'User Depart Name',
            'Year',
            'Category',
            'Subcategory',
            'upgraded_qty',
            'new_qty',
            'approx_cost',
            'remarks',
        ];
    }

    public function collection()
    {
        $record = json_decode($this->record);
        return collect($record);
    }
}
