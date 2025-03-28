<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expensive;
use Carbon\Carbon;

class ExpensiveController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'reason' => 'required|string',
        ]);

        $today = Carbon::today()->toDateString(); // Get current date

        // Check if an entry for today already exists
        $expense = Expensive::whereDate('created_at', $today)->first();

        if ($expense) {
            // Decode existing amounts and reasons
            $amounts = json_decode($expense->amount, true) ?? [];
            $reasons = json_decode($expense->reason, true) ?? [];
        } else {
            // If no record exists, create new arrays
            $amounts = [];
            $reasons = [];
            $expense = new Expensive();
        }

        // Append new values
        $amounts[] = $request->amount;
        $reasons[] = $request->reason;

        // Save updated data
        $expense->amount = json_encode($amounts);
        $expense->reason = json_encode($reasons);
        $expense->created_at = $today; // Ensure correct date
        $expense->save();

        return redirect()->back()->with('success', 'Expense added successfully!');
    }
}
