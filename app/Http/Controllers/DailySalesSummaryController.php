<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DailySalesSummary;
use Illuminate\Http\Request;

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
}
