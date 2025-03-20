@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-dark" style="font-size: 2rem; text-align: center;"><i class="bi bi-plus-circle"></i> Add Stock</h2>
    
    <form action="{{ route('stock.store') }}" method="POST">
        @csrf
        <div class="card shadow-sm p-4">
            <!-- Item Name -->
            <div class="form-group mb-3">
                <label for="item_name" class="form-label"><i class="bi bi-box"></i> Item Name</label>
                <div class="input-group">
                    <input type="text" id="item_name" name="item_name" class="form-control" required>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group mb-3">
                <label for="description" class="form-label"><i class="bi bi-file-earmark-text"></i> Description</label>
                <div class="input-group">
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <!-- Quantity -->
            <div class="form-group mb-3">
                <label for="quantity" class="form-label"><i class="bi bi-plus-square"></i> Quantity</label>
                <div class="input-group">
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>
            </div>

            <!-- Cost Price -->
            <div class="form-group mb-3">
                <label for="cost_price" class="form-label">Cost Price (LKR)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control" required>
                </div>
            </div>

            <!-- Sale Price -->
            <div class="form-group mb-3">
                <label for="sale_price" class="form-label">Sale Price (LKR)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" required>
                </div>
            </div>

            <!-- Vendor Name -->
            <div class="form-group mb-3">
                <label for="vendor_name" class="form-label"><i class="bi bi-person-badge"></i> Vendor Name (Optional)</label>
                <div class="input-group">
                    <input type="text" name="vendor_name" id="vendor_name" class="form-control">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-save"></i> Save Stock
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
