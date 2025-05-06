<?php

namespace App\Http\Controllers;

use App\Models\ThirtyDayReport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ThirtyDayReportController extends Controller
{
    public function index()
    {
        // Get reports grouped by 30-day periods
        $reports = ThirtyDayReport::orderBy('start_date', 'desc')
            ->get()
            ->groupBy(function($item) {
                // Group by 30-day periods
                $startDate = Carbon::parse($item->start_date);
                return $startDate->format('Y-m');
            });
            
        // Calculate totals for each group
        $groupedReports = $reports->map(function($group) {
            return [
                'data' => $group,
                'totals' => [
                    'sales' => $group->sum('total_sales'),
                    'cost' => $group->sum('total_cost'),
                    'profit' => $group->sum('total_profit')
                ]
            ];
        });
        
        // Calculate grand totals
        $grandTotals = [
            'sales' => ThirtyDayReport::sum('total_sales'),
            'cost' => ThirtyDayReport::sum('total_cost'),
            'profit' => ThirtyDayReport::sum('total_profit')
        ];

        return view('admin.30days.index', [
            'reports' => $groupedReports,
            'grandTotals' => $grandTotals
        ]);
    }
}