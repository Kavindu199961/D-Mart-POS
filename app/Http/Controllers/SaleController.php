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

        $sales = $query->get();
        return view('admin.invoices.index', compact('sales'));
    }

    public function showBill($id)
    {
        $sale = Sale::findOrFail($id); // Find sale by ID
        return view('admin.invoices.bill', compact('sale'));
    }

    public function downloadInvoice($invoiceNumber)
{
    // Fetch the sale record
    $sale = Sale::where('invoice_number', $invoiceNumber)->firstOrFail();

    // Generate the PDF
    $pdf = Pdf::loadView('admin.invoices.bill', [
        'sale' => $sale,
        'customer_name' => $sale->customer_name,
        'phone_number' => $sale->phone_number,
        'cashier_name' => $sale->cashier_name,
        'date_time' => $sale->created_at->format('Y-m-d H:i:s'),
        'show_download_button' => false,
    ]);

    // Set the paper size to A5
    $pdf->setPaper([0, 0, 595.28, 421.26]);

    // Download the PDF
    return $pdf->download($invoiceNumber . '.pdf');
}
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id); // Find the sale by ID or fail
        $sale->delete(); // Delete the sale record

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}