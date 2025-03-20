<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\DailySalesSummary;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index()
    {
        $items = Stock::all();
        $cashiers = ['Deshan', 'Jane Smith', 'Michael Brown']; // Example cashiers
        return view('admin.billing.index', compact('items', 'cashiers'));
    }

    public function searchItem(Request $request)
    {
        $search = $request->input('search');
    
        $items = Stock::where('item_name', 'LIKE', "%{$search}%")
                      ->orWhere('product_code', 'LIKE', "%{$search}%")
                      ->get();
    
        return response()->json($items);
    }
    

    public function generateInvoice(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'cashier_name' => 'required|string',
            'items' => 'required|array|min:1', // Ensure items array is not empty
            'items.*.item_name' => 'required|string', // Validate item_name
            'items.*.product_code' => 'required|string', // Validate product_code
            'items.*.sale_price' => 'required|numeric', // Validate sale_price
            'items.*.quantity' => 'required|integer|min:1', // Validate quantity
        ]);

        // Fetch and increment the invoice number
        $lastInvoice = DB::table('invoice_sequences')->first();
        $lastInvoiceNumber = $lastInvoice->last_invoice_number;

        // Extract the numeric part and increment it
        $number = (int) substr($lastInvoiceNumber, 4); // Extract "100" from "INV-100"
        $newNumber = $number + 1;
        $invoiceNumber = 'INV-' . $newNumber;

        // Update the last invoice number in the database
        DB::table('invoice_sequences')->update([
            'last_invoice_number' => $invoiceNumber,
            'updated_at' => now(),
        ]);

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        // Calculate total and update stock
        $totalAmount = 0;
        $totalProfit = 0; // Initialize total profit

        foreach ($validated['items'] as $item) {
            $stockItem = Stock::where('product_code', $item['product_code'])->first();
            if (!$stockItem) {
                return response()->json(['error' => 'Item not found: ' . $item['item_name']], 404);
            }
            if ($stockItem->quantity < $item['quantity']) {
                return response()->json(['error' => 'Insufficient stock for ' . $item['item_name']], 400);
            }
            $stockItem->decrement('quantity', $item['quantity']); // Reduce stock

            // Calculate profit for this item
            $costPrice = $stockItem->cost_price; // Assuming cost_price is a column in the Stock table
            $profit = ($item['sale_price'] - $costPrice) * $item['quantity'];
            $totalProfit += $profit;

            $totalAmount += $item['sale_price'] * $item['quantity'];
        }

        // Save sale details
        $sale = Sale::create([
            'cashier_name' => $validated['cashier_name'],
            'customer_name' => $validated['customer_name'], // Save customer_name
            'phone_number' => $validated['phone_number'], // Save phone_number
            'invoice_number' => $invoiceNumber, // Use the new invoice number
            'items' => json_encode($validated['items']),
            'total' => $totalAmount,
            'created_at' => $dateTime
        ]);

        // Update or create daily sales summary
        $today = Carbon::today()->toDateString();
        DailySalesSummary::updateOrCreate(
            ['date' => $today],
            [
                'total_sales' => DB::raw('total_sales + ' . $totalAmount),
                'total_profit' => DB::raw('total_profit + ' . $totalProfit),
            ]
        );

        // Generate PDF invoice
        $pdf = Pdf::loadView('admin.billing.bill', [
            'sale' => $sale,
            'customer_name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'date_time' => $dateTime
        ]);

        return $pdf->download($invoiceNumber . '.pdf');

    } catch (\Exception $e) {
        // Log the error and return a JSON response
        \Log::error('Error generating invoice: ' . $e->getMessage());
        \Log::info('Request Data:', $request->all());
        return response()->json(['error' => 'An error occurred while generating the invoice.'], 500);
    }
}

}
