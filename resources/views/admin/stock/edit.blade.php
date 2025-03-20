@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-primary" style="font-size: 2rem; text-align: center;"><i class="bi bi-pencil-square"></i> Edit Stock</h2>

    <form action="{{ route('stock.update', $stock->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="card shadow-sm p-4">
            <!-- Product Code (hidden) -->
            <input type="hidden" name="product_code" value="{{ $stock->product_code }}">

            <!-- Item Name -->
            <div class="form-group mb-3">
                <label for="item_name" class="form-label"><i class="bi bi-box"></i> Item Name</label>
                <div class="input-group">
                    <input type="text" id="item_name" name="item_name" value="{{ $stock->item_name }}" class="form-control" required>
                </div>
            </div>

            <!-- Quantity -->
            <div class="form-group mb-3">
                <label for="quantity" class="form-label"><i class="bi bi-plus-square"></i> Quantity</label>
                <div class="input-group">
                    <input type="number" id="quantity" name="quantity" value="{{ $stock->quantity }}" class="form-control" required>
                </div>
            </div>

            <!-- Cost Price -->
            <div class="form-group mb-3">
                <label for="cost_price" class="form-label">Cost Price (LKR)</label>
                <div class="input-group">
                    <input type="number" step="0.01" id="cost_price" name="cost_price" value="{{ $stock->cost_price }}" class="form-control" required>
                </div>
            </div>

            <!-- Sale Price -->
            <div class="form-group mb-3">
                <label for="sale_price" class="form-label">Sale Price (LKR)</label>
                <div class="input-group">
                    <input type="number" step="0.01" id="sale_price" name="sale_price" value="{{ $stock->sale_price }}" class="form-control" required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-warning btn-lg">
                    <i class="bi bi-pencil-square"></i> Update Stock
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
