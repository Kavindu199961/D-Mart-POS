@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center mb-4">Invoice #{{ $sale->invoice_number }}</h1>

            <!-- Bill Details -->
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th>Cashier Name</th>
                        <td>{{ $sale->cashier_name }}</td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td>{{ $sale->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{ $sale->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>Items</th>
                        <td>
                            <ul class="list-unstyled">
                                @foreach(json_decode($sale->items, true) as $item)
                                    <li>{{ $item['item_name'] }} - {{ $item['quantity'] }} x {{ number_format($item['sale_price'], 2) }} = {{ number_format($item['quantity'] * $item['sale_price'], 2) }} (LKR)</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>Total (LKR)</th>
                        <td class="font-weight-bold">{{ number_format($sale->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ url('/admin/invoices') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .table th, .table td {
            vertical-align: middle;
            padding: 12px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .card {
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .card-body {
            padding: 1.5rem;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .btn-secondary {
            padding: 10px 20px;
            font-size: 1.1rem;
        }

        .btn-secondary i {
            margin-right: 8px;
        }
    </style>
@endsection
