@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Daily Sales Summary</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Date</th>
                        <th>Total Sales (LKR)</th>
                        <th>Total Profit (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesSummaries as $summary)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($summary->date)->format('Y-m-d') }}</td>
                            <td>{{ number_format($summary->total_sales, 2) }}</td>
                            <td>{{ number_format($summary->total_profit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
