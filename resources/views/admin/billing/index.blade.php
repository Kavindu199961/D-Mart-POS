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
                                data-cost="${item.cost_price}" 
                                data-product-code="${item.product_code}">
                                ${item.item_name} - ${item.sale_price}
                            </li>`;
                        });

                        $('#search_results').html(resultList);
                    },
                    error: function(xhr) {
                        console.error('Search error:', xhr.responseText);
                    }
                });
            } else {
                $('#search_results').html('');
            }
        });

        // Add item to table when clicked
        $(document).on('click', '.item-option', function() {
            let itemId = $(this).data('id');
            let itemName = $(this).data('name');
            let itemPrice = $(this).data('price');
            let costPrice = $(this).data('cost');
            let productCode = $(this).data('product-code');

            let newRow = `<tr data-product-code="${productCode}">
                <td>${itemName}</td>
                <td><input type="number" value="${itemPrice}" class="form-control sale-price"></td>
                <td><input type="number" value="1" min="1" class="form-control quantity"></td>
                <td><input type="text" placeholder="Warranty (optional)" class="form-control warranty"></td>
                <td class="total-price">${itemPrice}</td>
                <td><input type="hidden" class="cost-price" value="${costPrice}"></td>
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

        // Update total calculation
        function updateTotal() {
            let total = 0;
            $('.total-price').each(function() {
                total += parseFloat($(this).text()) || 0;
            });
            $('#total_amount').text(total.toFixed(2));
        }

        // Generate invoice
        $('#generate_invoice').on('click', function() {
    // Disable button to prevent multiple clicks
    const $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

    // Collect form data
    let items = [];
    let customerName = $('#customer_name').val();
    let phoneNumber = $('#phone_number').val();
    let cashierName = $('#cashier_name').val();
    let total = parseFloat($('#total_amount').text()) || 0;

    // Validate required fields
    if (!customerName || !cashierName) {
        alert('Customer name and cashier name are required');
        $btn.prop('disabled', false).html('Generate Invoice');
        return;
    }

    // Check if there are items
    if ($('#item_list tr').length === 0) {
        alert('Please add at least one item');
        $btn.prop('disabled', false).html('Generate Invoice');
        return;
    }

    // Collect items data
    $('#item_list tr').each(function() {
        let itemName = $(this).find('td:first').text();
        let itemPrice = parseFloat($(this).find('.sale-price').val()) || 0;
        let quantity = parseInt($(this).find('.quantity').val()) || 1;
        let warranty = $(this).find('.warranty').val();
        let productCode = $(this).data('product-code');
        let costPrice = parseFloat($(this).find('.cost-price').val()) || 0;

        items.push({
            item_name: itemName,
            product_code: productCode,
            sale_price: itemPrice,
            cost_price: costPrice,
            quantity: quantity,
            warranty: warranty || null
        });
    });

    // Make AJAX request
    $.ajax({
        url: "{{ route('billing.generateInvoice') }}",
        method: "POST",
        data: JSON.stringify({
            customer_name: customerName,
            phone_number: phoneNumber,
            cashier_name: cashierName,
            items: items,
            total: total,
            _token: "{{ csrf_token() }}"
        }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json'
        },
        xhrFields: {
            responseType: 'blob' // Important for handling PDF response
        },
        success: function(data, status, xhr) {
            // Check if response is PDF
            if (xhr.getResponseHeader('content-type') === 'application/pdf') {
                // Create blob URL for the PDF
                const blob = new Blob([data], {type: 'application/pdf'});
                const url = URL.createObjectURL(blob);
                
                // Open PDF in new window and trigger print
                const printWindow = window.open(url, '_blank');
                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.print();
                        // Clean up
                        URL.revokeObjectURL(url);
                    }, 500);
                };
                
                // Clear the form
                $('#item_list').empty();
                $('#customer_name, #phone_number').val('');
                $('#total_amount').text('0.00');
            } else {
                // Handle JSON response (fallback)
                const response = JSON.parse(data);
                if (response.success) {
                    alert('Invoice generated successfully! Invoice #: ' + response.invoice_number);
                    // Clear the form
                    $('#item_list').empty();
                    $('#customer_name, #phone_number').val('');
                    $('#total_amount').text('0.00');
                    
                    // Open PDF in new tab if available
                    if (response.pdf_url) {
                        const printWindow = window.open(response.pdf_url, '_blank');
                        printWindow.onload = function() {
                            setTimeout(function() {
                                printWindow.print();
                            }, 500);
                        };
                    }
                } else {
                    alert('Error: ' + (response.message || 'Invoice generation failed'));
                }
            }
        },
        error: function(xhr) {
            let errorMessage = xhr.responseJSON?.message || 
                             xhr.responseJSON?.error || 
                             'Failed to generate invoice. Please try again.';
            alert('Error: ' + errorMessage);
        },
        complete: function() {
            // Re-enable button
            $btn.prop('disabled', false).html('Generate Invoice');
        }
    });
});
});


</script>

@endsection