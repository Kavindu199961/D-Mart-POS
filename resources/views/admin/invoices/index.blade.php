@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-center text-black fs-3">Invoices</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <form method="GET" action="{{ url('/admin/invoices') }}" class="input-group">
                    <input type="text" name="search" placeholder="Search by Invoice Number or Customer Name" class="form-control" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Invoice Number</th>
                            <th>Customer Name</th>
                            <th>Total (LKR)</th>
                            <th>Cashier Name</th>
                            <th>Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>{{ number_format($sale->total, 2) }}</td>
                                <td>{{ $sale->cashier_name }}</td>
                                <td>{{ $sale->created_at }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/admin/invoices/' . $sale->id) }}" class="btn btn-info btn-sm mb-2" data-toggle="tooltip" title="View Bill">
                                        <i class="fas fa-eye"></i> View Bill
                                    </a>

                                    <a href="{{ route('sales.downloadInvoice', $sale->id) }}" class="btn btn-primary btn-sm mb-2" data-toggle="tooltip" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i> Download
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.invoices.destroy', $sale->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this invoice?')" data-toggle="tooltip" title="Delete Invoice">
                                            <i class="fas fa-trash-alt"></i> Delete
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
@endsection

@section('styles')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-sm {
            font-size: 0.875rem;
        }

        .card {
            border-radius: 10px;
        }

        .card-body {
            padding: 1.5rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .input-group .form-control {
            border-radius: 5px;
        }

        .input-group-append .btn {
            border-radius: 5px;
        }

        .btn-primary i {
            margin-right: 5px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
