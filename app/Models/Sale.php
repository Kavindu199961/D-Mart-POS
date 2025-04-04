<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'cashier_name', 'customer_name', 'phone_number', 'items', 'total', 'total_cost'
    ];

    protected $casts = [
        'items' => 'array',
    ];

    protected static function booted()
    {
        static::deleted(function ($sale) {
            // Restock items when a sale is deleted
            self::restockItems($sale->items);

            // Update daily sales summary
            self::updateDailySalesSummary($sale->created_at, true);
        });
    }

    // Calculate total cost for a sale
    public function calculateTotalCost()
    {
        $totalCost = 0;
        $items = $this->items;

        if (is_array($items)) {
            foreach ($items as $item) {
                $totalCost += ($item['cost_price'] ?? 0) * ($item['quantity'] ?? 1);
            }
        }

        return $totalCost;
    }

    // Get total sales for a specific date
    public static function getDateSalesTotal($date)
    {
        return self::whereDate('created_at', $date)->sum('total');
    }

    // Get total profit for a specific date
    public static function getDateProfit($date)
    {
        return self::whereDate('created_at', $date)->sum('total') - 
               self::whereDate('created_at', $date)->sum('total_cost');
    }

    // Get total cost for a specific date
    public static function getDateTotalCost($date)
    {
        return self::whereDate('created_at', $date)->sum('total_cost');
    }

    // Update or create daily sales summary
    public static function updateDailySalesSummary($date, $isDeletion = false)
    {
        $date = Carbon::parse($date)->toDateString();
        
        $totalSales = self::getDateSalesTotal($date);
        $totalCost = self::getDateTotalCost($date);
        $totalProfit = $totalSales - $totalCost;

        if ($isDeletion) {
            // Only update if record exists
            $summary = DailySalesSummary::where('date', $date)->first();
            if ($summary) {
                $summary->update([
                    'total_sales' => $totalSales,
                    'total_cost' => $totalCost,
                    'total_profit' => $totalProfit,
                ]);
            }
        } else {
            // Normal behavior - update or create
            DailySalesSummary::updateOrCreate(
                ['date' => $date],
                [
                    'total_sales' => $totalSales,
                    'total_cost' => $totalCost,
                    'total_profit' => $totalProfit,
                ]
            );
        }
    }

    // Restock items after deleting an invoice
    public static function restockItems($items)
    {
        if (is_array($items)) {
            foreach ($items as $item) {
                $stockItem = Stock::where('product_code', $item['product_code'])->first();
                if ($stockItem) {
                    $stockItem->increment('quantity', $item['quantity']);
                }
            }
        }
    }
}
