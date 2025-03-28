@extends('layouts.admin')

@section('content')

<style>
    body {
        background-color: #f8f9fa;
    }
    .container1 {
        max-width: 800px;
    }
    .modal-header {
        border-bottom: none;
    }
    .modal-footer {
        border-top: none;
    }
    .table th {
        background-color: #343a40;
    }
</style>

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
    </div>  
</div>

<div class="container1 mt-5 p-4 bg-white shadow rounded">
    <h2 class="text-center text-danger fw-bold">Expense Management</h2><br>
    
    <div class="text-center mb-4">
        <button class="btn btn-outline-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            <i class="fas fa-plus-circle"></i> Add Expense
        </button>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('expenses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (LKR)</label>
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

    <h3 class="mt-4 text-center text-dark">All Expenses</h3>

    <div class="table-responsive">
        <table class="table table-hover text-center">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Total Amount (LKR)</th>
                    
                    <th>Created At</th>
                    <th>Action</th> <!-- View Button -->
                </tr>
            </thead>
            <tbody class="table-light">
                @foreach($expenses as $expense)
                    @php
                        $amounts = json_decode($expense->amount, true);
                        $reasons = json_decode($expense->reason, true);

                        // Ensure amounts and reasons are arrays
                        $amounts = is_array($amounts) ? $amounts : [$amounts];
                        $reasons = is_array($reasons) ? $reasons : [$reasons];

                        $totalAmount = array_sum($amounts);
                    @endphp
                    <tr>
                        <td class="text-danger fw-bold">{{ number_format($totalAmount, 2) }}</td>
                        <!-- Show combined reasons -->
                        <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm view-expense"
                                data-amounts='@json($amounts)'
                                data-reasons='@json($reasons)'
                                data-created_at="{{ $expense->created_at->format('Y-m-d H:i:s') }}"
                                data-bs-toggle="modal"
                                data-bs-target="#expenseDetailsModal">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Expense Details Modal -->
    <div class="modal fade" id="expenseDetailsModal" tabindex="-1" aria-labelledby="expenseDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="expenseDetailsModalLabel">Expense Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Date:</strong> <span id="modalCreatedAt"></span></p>
                    <ul id="expenseList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- JavaScript to Populate Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".view-expense").forEach(button => {
            button.addEventListener("click", function () {
                let amounts = JSON.parse(this.dataset.amounts);
                let reasons = JSON.parse(this.dataset.reasons);
                let createdAt = this.dataset.created_at;

                document.getElementById("modalCreatedAt").textContent = createdAt;

                let expenseList = document.getElementById("expenseList");
                expenseList.innerHTML = ""; // Clear previous entries
                
                amounts.forEach((amount, index) => {
                    let listItem = document.createElement("li");
                    listItem.className = "list-group-item";
                    listItem.innerHTML = `<strong>LKR ${parseFloat(amount).toFixed(2)}</strong> - ${reasons[index]}`;
                    expenseList.appendChild(listItem);
                });
            });
        });
    });
</script>

@endsection
