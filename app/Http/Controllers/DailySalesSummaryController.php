<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DailySalesSummary;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailySalesSummaryExport;
use App\Models\ThirtyDayReport;

class DailySalesSummaryController extends Controller
{
    /**
     * Display a listing of the daily sales summary grouped by 30-day chunks.
     */
    public function index()
    {
        $allSummaries = DailySalesSummary::get();

        $chunks = $allSummaries->chunk(30);

        $summaryGroups = $chunks->map(function ($chunk) {
            $totalSales = $chunk->sum('total_sales');
            $totalCost = $chunk->sum('total_cost');
            $totalProfit = $chunk->sum('total_profit');

            return [
                'data' => $chunk,
                'totals' => [
                    'sales' => $totalSales,
                    'cost' => $totalCost,
                    'profit' => $totalProfit,
                ],
            ];
        });

        return view('admin.profits.index', compact('summaryGroups'));
    }

    /**
     * Export daily sales summary to Excel.
     */
    public function export()
    {
        return Excel::download(new DailySalesSummaryExport, 'daily_sales_summary.xlsx');
    }

    protected static function booted()
    {
        static::created(function () {
            $unsummarized = DailySalesSummary::whereNull('summarized_at')
                           ->orderBy('date')
                           ->take(30)
                           ->get();
    
            if ($unsummarized->count() === 30) {
                $startDate = $unsummarized->first()->date;
                $endDate = $unsummarized->last()->date;
                $now = now(); // Get current timestamp
    
                ThirtyDayReport::create([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_sales' => $unsummarized->sum('total_sales'),
                    'total_profit' => $unsummarized->sum('total_profit'),
                    'total_cost' => $unsummarized->sum('total_cost'),
                    'created_at' => $now,  // Add this
                    'updated_at' => $now   // Add this
                ]);
    
                // Mark them as summarized
                DailySalesSummary::whereIn('id', $unsummarized->pluck('id'))
                    ->update(['summarized_at' => $now]);
            }
        });
    }
}
