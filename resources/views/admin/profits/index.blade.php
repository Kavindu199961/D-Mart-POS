@extends('layouts.admin')

@section('title', 'Profits')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top-4 py-3 px-4">
            <h4 class="mb-0 text-black"><i class="fas fa-chart-line"></i> Daily Sales Summary</h4>
            <a href="{{ route('daily-sales-summary.export') }}" class="btn btn-light btn-sm shadow-sm">
                <i class="fas fa-download"></i> Export Summary
            </a>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center shadow-sm">
                    <thead class="bg-dark text-white rounded-top-3">
                        <tr>
                            <th><i class="fas fa-calendar-alt"></i> Date</th>
                            <th><i class="fas fa-money-bill-wave"></i> Total Sales (LKR)</th>
                            <th><i class="fas fa-coins"></i> Total Profit (LKR)</th>
                            <th><i class="fas fa-dollar-sign"></i> Total Cost (LKR)</th> <!-- New Column Header -->
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @foreach($salesSummaries as $summary)
                            <tr>
                                <td><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($summary->date)->format('Y-m-d') }}</td>
                                <td class="text-success fw-bold"><i class="fas fa-money-bill"></i> {{ number_format($summary->total_sales, 2) }}</td>
                                <td class="text-primary fw-bold"><i class="fas fa-coins"></i> {{ number_format($summary->total_profit, 2) }}</td>
                                <td class="text-danger fw-bold"><i class="fas fa-dollar-sign"></i> {{ number_format($summary->total_cost, 2) }}</td> <!-- New Column Data -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light text-center py-2 rounded-bottom-4">
            <small class="text-muted">Last updated: {{ now()->format('Y-m-d H:i:s') }}</small>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table thead th {
        font-weight: 600;
        padding: 12px;
        background: linear-gradient(to right, #343a40, #495057);
    }
    .table tbody td {
        font-weight: 500;
        padding: 12px;
    }
    .card-header {
        background: linear-gradient(to right, #007bff, #0056b3);
        border-bottom: none;
    }
    .card-footer {
        border-top: none;
        font-size: 0.9rem;
    }
    .table-hover tbody tr:hover {
        background-color:rgb(129, 163, 197);
        transition: all 0.3s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
