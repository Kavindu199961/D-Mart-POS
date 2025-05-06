@extends('layouts.admin')

@section('title', '30-Day Financial Reports')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-pie me-3 fs-4 text-black"></i>
                            <h3 class="mb-0 text-black">Financial Performance Dashboard</h3>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Summary Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-lg border-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">LKR {{ number_format($grandTotals['sales'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-lg border-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Cost</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">LKR {{ number_format($grandTotals['cost'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-lg border-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Profit</div>
                            <div class="h5 mb-0 font-weight-bold {{ $grandTotals['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                LKR {{ number_format($grandTotals['profit'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-lg border-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            @php
                                $overallMargin = $grandTotals['sales'] ? ($grandTotals['profit']/$grandTotals['sales'])*100 : 0;
                                $overallMarginClass = $overallMargin >= 20 ? 'text-success' : ($overallMargin >= 10 ? 'text-primary' : 'text-danger');
                            @endphp
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Profit Margin</div>
                            <div class="h5 mb-0 font-weight-bold {{ $overallMarginClass }}">
                                {{ number_format($overallMargin, 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="row mb-4">-->
        <!-- Charts Section -->
    <!--    <div class="col-lg-8 mb-4">-->
    <!--        <div class="card shadow-sm h-100">-->
    <!--            <div class="card-header bg-white border-bottom">-->
    <!--                <h6 class="m-0 font-weight-bold text-primary">30-Day Performance Trend</h6>-->
    <!--            </div>-->
    <!--            <div class="card-body">-->
    <!--                <canvas id="performanceChart" height="300"></canvas>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div class="col-lg-4 mb-4">-->
    <!--        <div class="card shadow-sm h-100">-->
    <!--            <div class="card-header bg-white border-bottom">-->
    <!--                <h6 class="m-0 font-weight-bold text-primary">Profit Distribution</h6>-->
    <!--            </div>-->
    <!--            <div class="card-body">-->
    <!--                <canvas id="profitPieChart" height="300"></canvas>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->

    @if($reports->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Detailed Financial Reports</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item active" href="#">All Periods</a></li>
                            @foreach($reports as $month => $group)
                            <li><a class="dropdown-item" href="#">{{ Carbon\Carbon::parse($month)->format('F Y') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="financialReportsTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Period</th>
                                    <th class="text-end">Sales (LKR)</th>
                                    <th class="text-end">Cost (LKR)</th>
                                    <th class="text-end">Profit (LKR)</th>
                                    <th class="text-end">Margin</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $month => $group)
                                <!-- Month Header -->
                                <tr class="bg-light">
                                    <td colspan="6" class="fw-bold">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        {{ Carbon\Carbon::parse($month)->format('F Y') }}
                                    </td>
                                </tr>
                                
                                <!-- Daily Reports -->
                                @foreach($group['data'] as $report)
                                    @php
                                        $margin = $report->total_sales ? ($report->total_profit/$report->total_sales)*100 : 0;
                                        $marginClass = $margin >= 20 ? 'text-success' : ($margin >= 10 ? 'text-primary' : 'text-danger');
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ Carbon\Carbon::parse($report->start_date)->format('M d') }} - 
                                            {{ Carbon\Carbon::parse($report->end_date)->format('M d') }}
                                        </td>
                                        <td class="text-end">{{ number_format($report->total_sales, 2) }}</td>
                                        <td class="text-end">{{ number_format($report->total_cost, 2) }}</td>
                                        <td class="text-end {{ $report->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($report->total_profit, 2) }}
                                        </td>
                                        <td class="text-end {{ $marginClass }}">
                                            {{ number_format($margin, 1) }}%
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary view-details" data-id="{{ $report->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- Month Summary -->
                                
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th>Grand Total</th>
                                    <th class="text-end">{{ number_format($grandTotals['sales'], 2) }}</th>
                                    <th class="text-end">{{ number_format($grandTotals['cost'], 2) }}</th>
                                    <th class="text-end {{ $grandTotals['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($grandTotals['profit'], 2) }}
                                    </th>
                                    <th class="text-end">
                                        @php
                                            $overallMargin = $grandTotals['sales'] ? ($grandTotals['profit']/$grandTotals['sales'])*100 : 0;
                                            $overallMarginClass = $overallMargin >= 20 ? 'text-success' : ($overallMargin >= 10 ? 'text-primary' : 'text-danger');
                                        @endphp
                                        <span class="{{ $overallMarginClass }}">
                                            {{ number_format($overallMargin, 1) }}%
                                        </span>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="empty-state">
                        <img src="{{ asset('assets/img/illustrations/no-data.svg') }}" alt="No data" class="img-fluid mb-4" style="max-height: 200px;">
                        <h4>No reports available yet</h4>
                        <p class="text-muted">Financial reports will appear after your first 30-day period.</p>
                        <button class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Report Details Modal -->
<div class="modal fade" id="reportDetailsModal" tabindex="-1" aria-labelledby="reportDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reportDetailsModalLabel">Report Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Period</h6>
                                <h4 id="detail-period" class="text-primary">Jan 01 - Jan 30</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-3">Profit Margin</h6>
                                <h4 id="detail-margin" class="text-success">25.5%</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success bg-opacity-10 border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-success mb-3">Total Sales</h6>
                                <h3 id="detail-sales" class="text-success">LKR 250,000.00</h3>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-info bg-opacity-10 border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-info mb-3">Total Cost</h6>
                                <h3 id="detail-cost" class="text-info">LKR 150,000.00</h3>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary bg-opacity-10 border-0 h-100">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">Total Profit</h6>
                                <h3 id="detail-profit" class="text-primary">LKR 100,000.00</h3>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5 class="mb-3">Daily Breakdown</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dailyBreakdownTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-end">Sales</th>
                                    <th class="text-end">Cost</th>
                                    <th class="text-end">Profit</th>
                                    <th class="text-end">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i> Export Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Make sure you have this in your layout or before the chart scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .border-start-lg {
        border-left-width: 0.25rem !important;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .progress {
        border-radius: 10px;
    }
    .empty-state {
        padding: 2rem;
    }
    .view-details {
        transition: all 0.2s ease;
    }
    .view-details:hover {
        transform: scale(1.1);
    }
    .bg-gradient-primary {
        background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Performance Trend Chart
    document.addEventListener('DOMContentLoaded', function() {
        // Performance Trend Chart
        const performanceCtx = document.getElementById('performanceChart');
        if (performanceCtx) {
            const performanceChart = new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach($reports as $month => $group)
                            @foreach($group['data'] as $report)
                                "{{ Carbon\Carbon::parse($report->start_date)->format('M d') }}",
                            @endforeach
                        @endforeach
                    ],
                    datasets: [
                        {
                            label: 'Sales (LKR)',
                            data: [
                                @foreach($reports as $month => $group)
                                    @foreach($group['data'] as $report)
                                        {{ $report->total_sales }},
                                    @endforeach
                                @endforeach
                            ],
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Profit (LKR)',
                            data: [
                                @foreach($reports as $month => $group)
                                    @foreach($group['data'] as $report)
                                        {{ $report->total_profit }},
                                    @endforeach
                                @endforeach
                            ],
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.05)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': LKR ' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return 'LKR ' + value.toLocaleString('en-US');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Profit Pie Chart
        const profitPieCtx = document.getElementById('profitPieChart');
        if (profitPieCtx) {
            const profitPieChart = new Chart(profitPieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Sales', 'Cost', 'Profit'],
                    datasets: [{
                        data: [
                            {{ $grandTotals['sales'] }},
                            {{ $grandTotals['cost'] }},
                            {{ $grandTotals['profit'] }}
                        ],
                        backgroundColor: ['#4e73df', '#f6c23e', '#1cc88a'],
                        hoverBackgroundColor: ['#2e59d9', '#dda20a', '#17a673'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'LKR ' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    return label;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                        }
                    },
                    cutout: '70%',
                },
            });
        }
    });

    // Report Details Modal
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.getAttribute('data-id');
            // In a real application, you would fetch the details via AJAX
            // For this example, we'll use dummy data
            fetchReportDetails(reportId);
        });
    });

    function fetchReportDetails(reportId) {
        // Simulate AJAX call with dummy data
        setTimeout(() => {
            document.getElementById('detail-period').textContent = 'Jan 15 - Jan 30';
            document.getElementById('detail-sales').textContent = 'LKR 125,000.00';
            document.getElementById('detail-cost').textContent = 'LKR 85,000.00';
            document.getElementById('detail-profit').textContent = 'LKR 40,000.00';
            document.getElementById('detail-margin').textContent = '32.0%';
            
            // Populate daily breakdown table
            const dailyBreakdown = [
                { date: 'Jan 15', sales: 5000, cost: 3500, profit: 1500 },
                { date: 'Jan 16', sales: 7500, cost: 5000, profit: 2500 },
                { date: 'Jan 17', sales: 6000, cost: 4200, profit: 1800 },
                { date: 'Jan 18', sales: 8500, cost: 6000, profit: 2500 },
                { date: 'Jan 19', sales: 7000, cost: 4900, profit: 2100 },
            ];
            
            const tbody = document.querySelector('#dailyBreakdownTable tbody');
            tbody.innerHTML = '';
            
            dailyBreakdown.forEach(day => {
                const margin = (day.profit / day.sales * 100).toFixed(1);
                const marginClass = margin >= 20 ? 'text-success' : (margin >= 10 ? 'text-primary' : 'text-danger');
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${day.date}</td>
                    <td class="text-end">${day.sales.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="text-end">${day.cost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="text-end ${day.profit >= 0 ? 'text-success' : 'text-danger'}">${day.profit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="text-end ${marginClass}">${margin}%</td>
                `;
                tbody.appendChild(row);
            });
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
            modal.show();
        }, 300);
    }

    // Initialize DataTable
    $(document).ready(function() {
        $('#financialReportsTable').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            info: false,
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search reports...",
            }
        });
    });
</script>
@endpush