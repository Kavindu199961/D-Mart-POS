<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\DailySalesSummary;
use Illuminate\Http\Request;
use App\Models\Expensive;


class ReportController extends Controller
{
    public function index()
    {
        $totalSales = Sale::getTodaysSalesTotal();
        $profit = Sale::getTodaysProfit();
        $expenses = Expensive::all();
        $todaysummery = DailySalesSummary::all();

        return view('admin.report.index', compact('totalSales', 'profit','expenses','todaysummery'));
    }
}
