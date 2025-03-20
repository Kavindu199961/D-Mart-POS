<?php

namespace App\Http\Controllers;
use App\Models\Sale;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::query();

        // If there is a search query, filter by customer name or invoice number
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('invoice_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%');
        }

        $sales = $query->get();
        return view('admin.invoices.index', compact('sales'));
    }

    public function showBill($id)
    {
        $sale = Sale::findOrFail($id); // Find sale by ID
        return view('admin.invoices.bill', compact('sale'));
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id); // Find the sale by ID or fail
        $sale->delete(); // Delete the sale record

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
