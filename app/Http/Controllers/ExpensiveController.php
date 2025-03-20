<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expensive;

class ExpensiveController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        Expensive::create([
            'amount' => $request->amount,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Expensive amount added successfully.');
    }

    // Display all expenses (optional)
    public function index()
    {
        $expenses = Expensive::all();
        return view('expenses.index', compact('expenses'));
    }
}
