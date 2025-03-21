<?php

namespace App\Exports;

use App\Models\DailySalesSummary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailySalesSummaryExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DailySalesSummary::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Total Sales',
            'Total Profit',
            'Created At',
            'Updated At',
        ];
    }
}