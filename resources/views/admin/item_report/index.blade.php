@extends('layouts.admin')

@section('title', 'Item Sales Report')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Item Sales Report</h4>
            <form method="GET" action="{{ route('admin.item_report.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by code or name" value="{{ request('search') }}">
                <button type="submit" class="btn btn-light text-dark"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Product Code</th>
                            <th>Item Name</th>
                            <th>Total Quantity Sold</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($itemSales as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['product_code'] }}</td>
                                <td>{{ $item['item_name'] }}</td>
                                <td><span class="badge bg-success fs-6">{{ $item['total_quantity_sold'] }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
