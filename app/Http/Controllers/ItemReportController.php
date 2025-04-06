<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ItemReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $allSales = Sale::all();
        $itemSales = [];
    
        foreach ($allSales as $sale) {
            $items = $sale->items; // No need to json_decode here
    
            if (is_array($items)) {
                foreach ($items as $item) {
                    $productCode = $item['product_code'];
                    $itemName = $item['item_name'];
                    $quantity = $item['quantity'];
    
                    if (!isset($itemSales[$productCode])) {
                        $itemSales[$productCode] = [
                            'item_name' => $itemName,
                            'product_code' => $productCode,
                            'total_quantity_sold' => 0,
                        ];
                    }
    
                    $itemSales[$productCode]['total_quantity_sold'] += $quantity;
                }
            }
        }
    
        // Apply search filter
        if ($search) {
            $itemSales = array_filter($itemSales, function ($item) use ($search) {
                return stripos($item['product_code'], $search) !== false ||
                       stripos($item['item_name'], $search) !== false;
            });
        }
    
        return view('admin.item_report.index', [
            'itemSales' => $itemSales,
            'search' => $search,
        ]);
    }
    
}
