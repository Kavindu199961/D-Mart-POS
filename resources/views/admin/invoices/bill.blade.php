@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header bg-dark text-white text-center py-4">
            <h3 class="mb-0">Invoice #{{ $sale->invoice_number }}</h3>
        </div>
        <div class="card-body p-5">
            <!-- Customer & Invoice Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary">Customer Details</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $sale->customer_name }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $sale->phone_number }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="text-primary">Invoice Details</h5>
                    <p class="mb-1"><strong>Cashier:</strong> {{ $sale->cashier_name }}</p>
                    <p class="mb-1"><strong>Date:</strong> {{ $sale->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Item</th>
                            <th>Warranty</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item) <!-- No json_decode() here -->
                        <tr>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ !empty($item['warranty']) ? $item['warranty'] : '-----' }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rs. {{ number_format($item['sale_price'], 2) }}</td>
                            <td>Rs. {{ number_format($item['sale_price'] * $item['quantity'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total & Download Button -->
            <div class="d-flex justify-content-between align-items-center mt-4 p-4 bg-light rounded shadow-sm">
                <h4 class="text-success"><strong>Total: Rs. {{ number_format($sale->total, 2) }}</strong></h4>
                <a href="{{ route('sales.downloadInvoice', $sale->id) }}" class="btn btn-danger btn-lg">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
