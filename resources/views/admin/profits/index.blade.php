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
                            <th>#</th>
                            <th><i class="fas fa-calendar-alt"></i> Date</th>
                            <th><i class="fas fa-money-bill-wave"></i> Total Sales (LKR)</th>
                            <th><i class="fas fa-money-bill-wave"></i> Total Cost (LKR)</th>
                            <th><i class="fas fa-coins"></i> Total Profit (LKR)</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @if($summaryGroups->isNotEmpty())
                            <!-- Current Group (Most Recent) -->
                            @foreach($summaryGroups->first()['data'] as $summaryIndex => $summary)
                                <tr>
                                    <td>{{ $summaryIndex + 1 }}</td>
                                    <td><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($summary->date)->format('Y-m-d') }}</td>
                                    <td class="text-success fw-bold">{{ number_format($summary->total_sales, 2) }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($summary->total_cost, 2) }}</td>
                                    <td class="text-primary fw-bold">{{ number_format($summary->total_profit, 2) }}</td>
                                </tr>
                            @endforeach
                            
                            <!-- Current Group Total Row -->
                            <tr class="table-info fw-bold text-dark">
                                <td colspan="2">Current Period ({{ count($summaryGroups->first()['data']) }} Days)</td>
                                <td>{{ number_format($summaryGroups->first()['totals']['sales'], 2) }}</td>
                                <td>{{ number_format($summaryGroups->first()['totals']['cost'], 2) }}</td>
                                <td>{{ number_format($summaryGroups->first()['totals']['profit'], 2) }}</td>
                            </tr>
                            
                            <!-- Spacer between current and older groups -->
                            <tr style="height: 20px; background-color: transparent;">
                                <td colspan="5" class="p-0"></td>
                            </tr>
                            
                            <!-- Older Groups -->
                            @foreach($summaryGroups->slice(1) as $groupIndex => $group)
                                <!-- Group Header -->
                                <tr class="bg-secondary text-white">
                                    <td colspan="5" class="text-start fw-bold ps-4">
                                        <i class="fas fa-history me-2"></i> New Period {{ $groupIndex + 1 }}
                                    </td>
                                </tr>
                                
                                @foreach($group['data'] as $summaryIndex => $summary)
                                    <tr>
                                        <td>{{ $summaryIndex + 1 }}</td>
                                        <td><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($summary->date)->format('Y-m-d') }}</td>
                                        <td class="text-success fw-bold">{{ number_format($summary->total_sales, 2) }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($summary->total_cost, 2) }}</td>
                                        <td class="text-primary fw-bold">{{ number_format($summary->total_profit, 2) }}</td>
                                    </tr>
                                @endforeach
                                
                                <!-- Group Total Row -->
                                <tr class="table-secondary fw-bold text-dark">
                                    <td colspan="2">Total for Period {{ $groupIndex + 1 }} ({{ count($group['data']) }} Days)</td>
                                    <td>{{ number_format($group['totals']['sales'], 2) }}</td>
                                    <td>{{ number_format($group['totals']['cost'], 2) }}</td>
                                    <td>{{ number_format($group['totals']['profit'], 2) }}</td>
                                </tr>
                                
                                <!-- Add spacing between older groups except after last one -->
                                @if(!$loop->last)
                                    <tr style="height: 15px; background-color: transparent;">
                                        <td colspan="5" class="p-0"></td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-4">No data available</td>
                            </tr>
                        @endif
                    </tbody>
                    @if(!empty($grandTotals))
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="2">Grand Total (All Periods)</th>
                            <th>{{ number_format($grandTotals['sales'], 2) }}</th>
                            <th>{{ number_format($grandTotals['cost'], 2) }}</th>
                            <th>{{ number_format($grandTotals['profit'], 2) }}</th>
                        </tr>
                    </tfoot>
                    @endif
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
        vertical-align: middle;
    }
    .table tbody td {
        font-weight: 500;
        padding: 12px;
        vertical-align: middle;
    }
    .card-header {
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        border-bottom: none;
    }
    .card-footer {
        border-top: none;
        font-size: 0.9rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        transition: all 0.3s ease;
    }
    .table-info {
        background-color: #e7f5ff !important;
    }
    .table-secondary {
        background-color: #f8f9fa !important;
    }
    .rounded-top-3 {
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
    }
    .rounded-bottom-4 {
        border-bottom-left-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
    }
    .rounded-4 {
        border-radius: 1rem !important;
    }
    .bg-secondary {
        background-color: #6c757d !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Add any JavaScript functionality you need here
    });
</script>
@endpush