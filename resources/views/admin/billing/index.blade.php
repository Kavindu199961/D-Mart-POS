@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-cash-register"></i> Billing System</h4>
        </div>
        <div class="card-body">
            <!-- Customer Details and Cashier Selection -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_name"><i class="fas fa-user"></i> Customer Name</label>
                        <input type="text" id="customer_name" class="form-control" placeholder="Enter Customer Name">
                    </div><br>
                    <div class="form-group">
                        <label for="phone_number"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="text" id="phone_number" class="form-control" placeholder="Enter Phone Number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cashier_name"><i class="fas fa-user-tie"></i> Select Cashier</label>
                        <select id="cashier_name" class="form-control">
                            @foreach($cashiers as $cashier)
                                <option value="{{ $cashier }}">{{ $cashier }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Item Search -->
            <div class="mt-4">
                <label for="search_item"><i class="fas fa-search"></i> Search & Add Items</label>
                <input type="text" id="search_item" class="form-control" placeholder="Search by Item Name or Code">
                <ul id="search_results" class="list-group mt-2"></ul>
            </div>

            <!-- Items Table -->
            <div class="mt-4">
                <h5><i class="fas fa-shopping-cart"></i> Items List</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Sale Price (LKR)</th>
                            <th>Quantity</th>
                            <th>Warranty</th>
                            <th>Total (LKR)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="item_list"></tbody>
                </table>
            </div>

            <!-- Total Calculation -->
            <div class="mt-4 text-right fs-3">
                <h5><i class="fas fa-file-invoice-dollar"></i> Total: <span id="total_amount">0.00 (LKR)</span></h5>
            </div>

            <!-- Generate Invoice Button -->
            <div class="text-center mt-4">
                <button class="btn btn-success btn-lg" id="generate_invoice">
                    <i class="fas fa-file-download"></i> Generate Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and FontAwesome -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
    $(document).ready(function() {
        let itemList = [];

        // Search items
        $('#search_item').on('keyup', function() {
            let search = $(this).val();

            if (search.length > 0) {
                $.ajax({
                    url: "{{ route('billing.searchItem') }}",
                    type: "GET",
                    data: { search: search },
                    success: function(response) {
                        let resultList = '';
                        response.forEach(item => {
                            resultList += `<li class="list-group-item item-option" 
                                data-id="${item.id}" 
                                data-name="${item.item_name}" 
                                data-price="${item.sale_price}" 
                                data-product-code="${item.product_code}"> <!-- Include product_code -->
                                ${item.item_name} - ${item.sale_price}
                            </li>`;
                        });

                        $('#search_results').html(resultList);
                    }
                });
            } else {
                $('#search_results').html('');
            }
        });

        // Add item to table when selected
        $(document).on('click', '.item-option', function() {
            let itemId = $(this).data('id');
            let itemName = $(this).data('name');
            let itemPrice = $(this).data('price');
            let productCode = $(this).data('product-code'); // Get product_code

            let newRow = `<tr data-product-code="${productCode}">
                <td>${itemName}</td>
                <td><input type="number" value="${itemPrice}" class="form-control sale-price"></td>
                <td><input type="number" value="1" min="1" class="form-control quantity"></td>
                <td><input type="text" placeholder="Warranty (optional)" class="form-control warranty"></td>
                <td class="total-price">${itemPrice}</td>
                <td><button class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button></td>
            </tr>`;

            $('#item_list').append(newRow);
            $('#search_results').html('');
            $('#search_item').val('');

            updateTotal();
        });

        // Update total price when quantity or sale price changes
        $(document).on('input', '.quantity, .sale-price', function() {
            let row = $(this).closest('tr');
            let price = parseFloat(row.find('.sale-price').val()) || 0;
            let quantity = parseInt(row.find('.quantity').val()) || 1;
            let total = price * quantity;

            row.find('.total-price').text(total.toFixed(2));
            updateTotal();
        });

        // Remove item from the list
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            updateTotal();
        });

        // Calculate total amount
        function updateTotal() {
            let total = 0;
            $('.total-price').each(function() {
                total += parseFloat($(this).text());
            });
            $('#total_amount').text(total.toFixed(2));
        }

        // Generate invoice
            $('#generate_invoice').on('click', function() {
            let customerName = $('#customer_name').val();
            let phoneNumber = $('#phone_number').val();
            let cashierName = $('#cashier_name').val();
            let items = [];

            // Loop through each row in the item list
            $('#item_list tr').each(function() {
                let itemName = $(this).find('td:nth-child(1)').text(); // Get item name
                let itemPrice = parseFloat($(this).find('.sale-price').val()); // Get sale price
                let quantity = parseInt($(this).find('.quantity').val()); // Get quantity
                let warranty = $(this).find('.warranty').val(); // Get warranty (if exists)
                let productCode = $(this).data('product-code'); // Get product code

                // Push item details into the items array
                items.push({
                    item_name: itemName,
                    product_code: productCode,
                    sale_price: itemPrice,
                    quantity: quantity,
                    warranty: warranty || null // Set warranty to null if it's empty
                });
            });

            // Send data to the server to generate the invoice
            fetch("{{ route('billing.generateInvoice') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    customer_name: customerName,
                    phone_number: phoneNumber,
                    cashier_name: cashierName,
                    items: items // Include the items array
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'An error occurred'); });
                }
                return response.blob(); // Get the PDF file as a blob
            })
            .then(blob => {
                // Create a temporary URL for the blob
                let url = window.URL.createObjectURL(blob);
                let a = document.createElement("a");
                a.style.display = "none";
                a.href = url;
                a.download = "invoice.pdf"; // Set the file name for download
                document.body.appendChild(a);
                a.click(); // Trigger the download
                window.URL.revokeObjectURL(url); // Clean up the URL object
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message); // Show error message to the user
            });
        });
    });
</script>

@endsection