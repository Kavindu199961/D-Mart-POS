@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4 fs-2">Sales Report</h1>

        <div class="cardBox">
            <a href="{{ route('admin.profits.index') }}" class="text-decoration-none">
                <div class="card">
                    <div>
                        <div class="numbers">LKR {{ number_format($totalSales, 2) }}</div>
                        <div class="cardName">Today's Selling</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cash-outline" style="font-size: 2rem; color: #28a745;"></ion-icon>
                    </div>
                </div>
            </a>


                <!-- <div class="card">
                    <div>
                        <div class="numbers">LKR {{ $profit }}</div>
                        <div class="cardName">Today's Profit</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="trending-up-outline"></ion-icon>
                    </div>
                </div> -->
           </div>  
</div>

<div class="container mt-5">
    <h2 class="text-center fs-4">Expense Management</h2><br>
    <div class="text-center mb-4">
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#expenseModal">
            <i class="fas fa-plus-circle"></i> Add Expense
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="expenseModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/expenses" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3 text-center">{{ session('success') }}</div>
    @endif

    <h3 class="mt-4 text-center">All Expenses</h3>
    <div class="table-responsive">
        <table class="table w-100">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Amount (LKR)</th>
                    <th>Reason</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                    <tr>
                        <td class="text-danger fw-bold">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->reason }}</td>
                        <td>{{ $expense->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection