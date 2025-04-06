<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use PDF; // Use the correct facade

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

        $sales = $query->latest()->get();
        return view('admin.invoices.index', compact('sales'));
    }

    public function showBill($id)
    {
        $sale = Sale::findOrFail($id); // Find sale by ID
        return view('admin.invoices.bill', compact('sale'));
    }

    public function downloadInvoice($id)
    {
        $sale = Sale::findOrFail($id);
        $pdf = Pdf::loadView('admin.invoices.printbill', compact('sale'));
        $pdf->setPaper([0, 0, 595.28, 421.26]);
        return $pdf->download($sale->invoice_number . '.pdf');
    }
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id); // Find the sale by ID or fail
        $sale->delete(); // Delete the sale record

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }


}