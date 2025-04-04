<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\DailySalesSummary;
use Illuminate\Http\Request;
use App\Models\Expensive;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get today's date
        $today = Carbon::today()->toDateString();

        // Get total sales for today
        $totalSales = Sale::getDateSalesTotal($today);

        // Get total profit for today
        $profit = Sale::getDateProfit($today);

        // Get total cost for today
        $totalCost = Sale::getDateTotalCost($today);

        // Fetch all expenses
        $expenses = Expensive::all();

        // Fetch today's sales summary
        $todaysSummary = DailySalesSummary::where('date', $today)->first();

        // Return the view with the data
        return view('admin.report.index', compact('totalSales', 'profit', 'totalCost', 'expenses', 'todaysSummary'));
    }
}
