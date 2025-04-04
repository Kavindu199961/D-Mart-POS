<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\DailySalesSummary;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::beginTransaction();

        try {
            // Validate the request
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'cashier_name' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.item_name' => 'required|string',
                'items.*.product_code' => 'required|string',
                'items.*.sale_price' => 'required|numeric|min:0',
                'items.*.cost_price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.warranty' => 'nullable|string',
            ]);

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();
            $dateTime = Carbon::now();
            $totalAmount = 0;
            $totalProfit = 0;
            $totalCost = 0;

            // Process each item
            foreach ($validated['items'] as $item) {
                $stockItem = Stock::where('product_code', $item['product_code'])->first();
                
                if (!$stockItem) {
                    throw new \Exception('Item not found: ' . $item['item_name']);
                }

                if ($stockItem->quantity < $item['quantity']) {
                    throw new \Exception('Insufficient stock for ' . $item['item_name'] . 
                                        ' (Available: ' . $stockItem->quantity . ')');
                }

                // Calculate item totals
                $itemTotal = $item['sale_price'] * $item['quantity'];
                $itemCost = $item['cost_price'] * $item['quantity'];
                $itemProfit = $itemTotal - $itemCost;

                $totalAmount += $itemTotal;
                $totalCost += $itemCost;
                $totalProfit += $itemProfit;

                // Reduce stock quantity
                $stockItem->decrement('quantity', $item['quantity']);
            }

            // Create sale record
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'cashier_name' => $validated['cashier_name'],
                'customer_name' => $validated['customer_name'],
                'phone_number' => $validated['phone_number'],
                'items' => $validated['items'],
                'total' => $totalAmount,
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit,
                'created_at' => $dateTime
            ]);

            // Update daily sales summary
            $this->updateDailySalesSummary($dateTime, $totalAmount, $totalCost, $totalProfit);

            // Generate PDF invoice
            $pdf = $this->generatePdfInvoice($sale, $validated, $dateTime);

            DB::commit();

            // Return PDF with proper headers
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="'.$invoiceNumber.'.pdf"');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice Generation Error: ' . $e->getMessage());
            Log::error('Request Data: ', $request->all());
            Log::error('Stack Trace: ', $e->getTrace());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }

    protected function generateInvoiceNumber()
    {
        $lastInvoice = DB::table('invoice_sequences')->first();
        $lastNumber = $lastInvoice ? (int) substr($lastInvoice->last_invoice_number, 4) : 0;
        $newNumber = $lastNumber + 1;
        $invoiceNumber = 'INV-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        DB::table('invoice_sequences')->updateOrInsert(
            ['id' => 1],
            [
                'last_invoice_number' => $invoiceNumber,
                'updated_at' => now()
            ]
        );

        return $invoiceNumber;
    }

    protected function updateDailySalesSummary($date, $totalSales, $totalCost, $totalProfit)
    {
        $dateString = Carbon::parse($date)->toDateString();

        DailySalesSummary::updateOrCreate(
            ['date' => $dateString],
            [
                'total_sales' => DB::raw("COALESCE(total_sales, 0) + $totalSales"),
                'total_cost' => DB::raw("COALESCE(total_cost, 0) + $totalCost"),
                'total_profit' => DB::raw("COALESCE(total_profit, 0) + $totalProfit"),
            ]
        );
    }

    protected function generatePdfInvoice($sale, $validated, $dateTime)
    {
        $pdf = Pdf::loadView('admin.billing.bill', [
            'sale' => $sale,
            'items' => $validated['items'],
            'customer_name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'cashier_name' => $validated['cashier_name'],
            'date_time' => $dateTime->format('Y-m-d H:i:s'),
            'invoice_number' => $sale->invoice_number,
            'total_amount' => $sale->total
        ]);

        $pdf->setPaper([0, 0, 595.28, 421.26]); // A5 paper size

        return $pdf;
    }
 

}