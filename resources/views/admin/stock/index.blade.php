@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-dark" style="font-size: 3rem; text-align: center;">
        <i class="bi bi-boxes"></i> Stock List
    </h1>

    <a href="{{ route('stock.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Add Stock
    </a>

    <form method="GET" action="{{ route('stock.search') }}" class="mb-4">
        <div class="input-group shadow-sm rounded">
            <input type="text" name="search" class="form-control border-end-0 rounded-start" placeholder="Search by Product Code or Item Name" value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary rounded-end">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Product Code</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Cost Price (LKR)</th>
                        <th>Sale Price (LKR)</th>
                        <th>Vendor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                        <tr class="{{ $stock->quantity == 0 ? 'table-danger' : '' }}">
                            <td>{{ $stock->product_code }}</td>
                            <td>{{ $stock->item_name }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ number_format($stock->cost_price, 2) }}</td>
                            <td>{{ number_format($stock->sale_price, 2) }}</td>
                            <td>{{ $stock->vendor_name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('stock.edit', $stock->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <!-- View Description Button -->
                                <button type="button" class="btn btn-info btn-sm view-desc-btn" 
                                    data-bs-toggle="popover" 
                                    data-bs-trigger="focus"
                                    data-bs-placement="left"
                                    title="Description"
                                    data-bs-content="{{ $stock->description ?? 'No description available' }}">
                                    <i class="bi bi-eye"></i> View Desc
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('stock.delete', $stock->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this stock?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enable Bootstrap Popover -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        let popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>

@endsection
