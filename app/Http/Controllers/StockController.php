<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::latest()->get();
        return view('admin.stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('admin.stock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'quantity' => 'required|integer',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);

        $lastStock = Stock::latest('id')->first();

    if ($lastStock && preg_match('/DMT-(\d+)/', $lastStock->product_code, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }

    // Generate new unique product code
    $product_code = 'DMT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Stock::create([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'product_code' => $product_code,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'sale_price' => $request->sale_price,
            'vendor_name' => $request->vendor_name,
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock added successfully.');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        return view('admin.stock.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required',
            'quantity' => 'required|integer',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock updated successfully.');
    }
    
    public function destroy($id)
    {
        Stock::destroy($id);
        return redirect()->route('stock.index')->with('success', 'Stock deleted successfully.');
    }

    public function search(Request $request)
{
    $query = Stock::query();

    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->where('product_code', 'like', "%{$searchTerm}%")
              ->orWhere('item_name', 'like', "%{$searchTerm}%");
    }

    $stocks = $query->get();
    return view('admin.stock.index', compact('stocks'));
}

}
