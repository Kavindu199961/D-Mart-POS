<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Invoice</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 9px;
                margin-top: -25px;
                margin-left: -20px;
                margin-right: 60px;
                margin-bottom: 0px;
                padding: 0;
                width: 210mm; /* A4 width */
                min-height: 148.5mm; /* Half A4 height */
                display: flex;
                justify-content: center;
                align-items: flex-start;
                box-sizing: border-box;
            }
            .invoice-container {
                width: 180mm; /* Slight margin inside the full A4 width (210mm) */
                padding-top: 5px;
                padding-right: 50px;
                padding-bottom: 10px;
                padding-left: 11px;
                border: 1px solid #000; /* Optional border */
            }
            .header {
                margin-bottom: 10px;
            }
            .table-layout {
                width: 105%;
                border-collapse: collapse;
            }
            .table-layout td {
                vertical-align: top;
                padding: 5px;
            }
            .table {
                width: 105%;
                border-collapse: collapse;
                font-size: 10px; /* Standard font size */
            }
            .table th, .table td {
                border: 1px solid #ddd; /* Cell border */
                padding: 2px; /* Minimal padding */
                text-align: left; /* Default alignment */
            }
            .table th {
                background-color: #f4f4f4; /* Header background */
                font-weight: bold;
            }
            .table tbody tr:nth-child(even) {
                background-color: #f9f9f9; /* Alternate row color */
            }
            .table tbody tr:hover {
                background-color: #e9e9e9; /* Row hover effect */
            }
            .table tfoot td {
                font-size: 11px; /* Slightly larger footer font */
                font-weight: bold;
                text-align: left;
            
            }
            .section {
                line-height: 0.4; /* Adjust this value to decrease spacing */
                text-align: right;
                font-size: 11px;
            }
            .details {
                line-height: 0.5; /* Adjust this value to decrease spacing */
                font-size: 11px;
            }
        </style>
    </head>
    <body>
        <div class='invoice-container'>
            <div class='header'>
                <table style='width: 100%; border: none; table-layout: fixed;'>
                    <tr>
                    <td style='width: 15%; text-align: left;'>
                        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" alt="Mobile Shop Logo" class="logo" style='width: 135px; height: auto;'></td>
                        </td>
                        
                        <td style='width: 160%; text-align: center;'>
                            <h1 style='margin: 0; font-size: 18px;'>D-MART MOBILE SHOP</h1>
                            <p style='margin: 5px 0; font-size: 10px;'>MOBILE PHONE REPAIR & ACCESSORIES</p>
                            <p style='margin: 5px 0; font-size: 10px;'>COMPUTER REPAIR & ACCESSORIES PHOTO COPY & PRINTOUT | RE-LOAD & ETC.</p>
                            <p style='margin: 5px 0; font-size: 10px;'>RAMBUKKANA ROAD, HIRIWADUNNA, SRI LANKA</p>
                            <p style='margin: 5px 0; font-size: 10px;'><strong>| Hotline: 076 3471705 | Email : dmart11259@gmail.com |</strong></p>
                        </td>
                    </tr>
                </table>
            </div>
            <table class='table-layout'>
                <tr>
                    <td>
                        <div class='details'>
                            <p><strong>Invoice To:</strong> {{ $sale->customer_name }}<</p>
                            <p><strong>Phone Number:</strong> {{ $sale->phone_number }}</p>
                        </div>
                    </td>
                    <td>
                        <div class='section'>
                            <p><strong>Invoice No:</strong> {{ $sale->invoice_number }}</p>
                            <p><strong>Issue Date:</strong> {{ $sale->created_at }}</p>
                            <p><strong>Sales Rep:</strong> {{ $sale->cashier_name }}</p>
                            <p><strong>Payment Method:</strong> Cash</p>
                        </div>
                    </td>
                </tr>
            </table>
            <table class='table'>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Warranty</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Directly use $sale->items if it's already an array
                    $itemCount = count($sale->items);
                    for ($i = 0; $i < 10; $i++) {
                        if ($i < $itemCount) {
                            $item = $sale->items[$i];
                            echo '<tr>
                                <td>' . $item['item_name'] . '</td>
                                <td>' . (!empty($item['warranty']) ? $item['warranty'] : '-----') . '</td>
                                <td>' . $item['quantity'] . '</td>
                                <td>' . number_format($item['sale_price'], 2) . '</td>
                                <td class="text-right">' . number_format($item['sale_price'] * $item['quantity'], 2) . '</td>
                            </tr>';
                        } else {
                            echo '<tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>';
                        }
                    }
                @endphp
            </tbody>

            <tfoot>
                <tr>
                    <td colspan='3'></td>
                    <td colspan='0'>Total</td>
                    <td>{{ number_format($sale->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
                    <div style='text-align: center; margin-top: 1px;'>
                <p style='margin: 0; font-size: 11px;'>Come back and enjoy your shopping experience</p>
                <!-- <p style='margin: 0; font-size: 9px; text-align: left;'>* Warranty for 1 Year Less 21 Working Days for computer hardware.</p>
                <p style='margin: 0; font-size: 9px; text-align: left;'>* No Warranty for Bum Marks, Scratches, Physical damages and any other damage happened by user activities.</p>
                <p style='margin: 0; font-size: 9px; text-align: left;'>* Goods sold once can't return.</p> -->
            </div>
            <table style='width: 105%; margin-top: 6px; table-layout: fixed;'>
                <tr>
                    <td style='width: 70%; text-align: left;'>
                        <p>__________________________</p>
                        <p style='margin-left: 50px; font-size: 8px;'>Authorized By</p>
                    </td>
                    <td style='width: 70%; text-align: right;'>
                        <p>__________________________</p>
                        <p style='margin-right: 50px; font-size: 8px;'>Received By</p>
                    </td>
                </tr>
            </table>
            <p style='text-align: center; margin-top: -20px; font-size: 10px;'>Powered by CeylonGIT | 070 7645303
            </p>
        </div>
    </body>
    </html>