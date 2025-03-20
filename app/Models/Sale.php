<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'cashier_name', 'customer_name', 'phone_number', 'items', 'total'
    ];

    protected $casts = [
        'items' => 'array', // Automatically convert JSON to an array
    ];

    // Get total sales for today
    public static function getTodaysSalesTotal()
    {
        return self::whereDate('created_at', Carbon::today())
                    ->sum('total');
    }

    // Get today's profit based on sales and stock cost price
    public static function getTodaysProfit()
    {
        $todaysSales = self::whereDate('created_at', Carbon::today())->get();
        $profit = 0;

        foreach ($todaysSales as $sale) {
            // Check if 'items' is an array
            $items = $sale->items;
            if (is_array($items)) {
                foreach ($items as $item) {
                    // Assuming each item has 'id', 'price', and 'quantity' fields
                    $stockItem = Stock::find($item['id']); 
                    if ($stockItem) {
                        $profit += ($item['price'] - $stockItem->cost_price) * $item['quantity'];
                    }
                }
            } else {
                // Log or handle error if items is not an array
                \Log::error("Invalid 'items' data for Sale ID: {$sale->id}");
            }
        }

        return $profit;
    }

    // Update or create daily sales summary
    public static function updateDailySalesSummary()
    {
        $today = Carbon::today()->toDateString();
        $totalSales = self::getTodaysSalesTotal();
        $totalProfit = self::getTodaysProfit();

        DailySalesSummary::updateOrCreate(
            ['date' => $today],
            [
                'total_sales' => $totalSales,
                'total_profit' => $totalProfit,
            ]
        );
    }
}