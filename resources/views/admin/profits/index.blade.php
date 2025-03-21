@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-chart-line"></i> Daily Sales Summary</h4>
            <a href="#" class="btn btn-light btn-sm"><i class="fas fa-download"></i> Export Report</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th><i class="fas fa-calendar-alt"></i> Date</th>
                        <th><i class="fas fa-money-bill-wave"></i> Total Sales (LKR)</th>
                        <th><i class="fas fa-coins"></i> Total Profit (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesSummaries as $summary)
                        <tr>
                            <td><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($summary->date)->format('Y-m-d') }}</td>
                            <td><i class="fas fa-money-bill"></i> {{ number_format($summary->total_sales, 2) }}</td>
                            <td><i class="fas fa-coins"></i> {{ number_format($summary->total_profit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            <small class="text-muted">Last updated: {{ now()->format('Y-m-d H:i:s') }}</small>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table thead th {
        font-weight: 600;
    }
    .table tbody td {
        font-weight: 500;
    }
    .card-header {
        border-bottom: none;
    }
    .card-footer {
        border-top: none;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush