<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { width: 100%; padding: 20px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Invoice: {{ $sale->invoice_number }}</h2>
        <p><strong>Customer:</strong> {{ $customer_name ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $phone_number ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ $date_time ?? 'N/A' }}</p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Warranty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $items = json_decode($sale->items, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        foreach ($items as $item) {
                            echo '<tr>
                                <td>' . $item['item_name'] . '</td>
                                <td>' . $item['quantity'] . '</td>
                                <td>' . (!empty($item['warranty']) ? $item['warranty'] : 'N/A') . '</td>
                                <td>' . number_format($item['sale_price'], 2) . '</td>
                                <td class="text-right">' . number_format($item['sale_price'] * $item['quantity'], 2) . '</td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">Invalid items data</td></tr>';
                    }
                @endphp
            </tbody>
        </table>

        <h3 class="text-right">Total: {{ number_format($sale->total, 2) }}</h3>
    </div>
</body>
</html>