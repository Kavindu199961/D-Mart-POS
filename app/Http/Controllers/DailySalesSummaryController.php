<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DailySalesSummary;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailySalesSummaryExport;

class DailySalesSummaryController extends Controller
{
    /**
     * Display a listing of the daily sales summary.
     */
    public function index()
    {
        $salesSummaries = DailySalesSummary::orderBy('date', 'desc')->get();
        return view('admin.profits.index', compact('salesSummaries'));
    }

    public function export()
    {
        return Excel::download(new DailySalesSummaryExport, 'daily_sales_summary.xlsx');
    }
}
