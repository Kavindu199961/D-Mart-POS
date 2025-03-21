<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>
        function downloadPDF() {
            const link = document.createElement('a');
            link.href = "{{ route('download.invoice', ['invoiceNumber' => $sale->invoice_number]) }}";
            link.download = "{{ $sale->invoice_number }}.pdf";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
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
            padding: 5px 11px 10px 11px;
            border: 1px solid #000; /* Optional border */
        }
        .header {
            margin-bottom: 10px;
        }
        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }
        .table-layout td {
            vertical-align: top;
            padding: 5px;
        }
        .table {
            width: 100%;
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
        }
        .details {
            line-height: 0.5; /* Adjust this value to decrease spacing */
        }
        .logo {
            width: 135px;
            height: auto;
        }
        .text-right {
            text-align: right;
        }

        /* Hide the download button when printing or generating PDF */
       
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="header">
            <table style="width: 100%; border: none; table-layout: fixed;">
                <tr>
                    <td style="width: 15%; text-align: left;">
                        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/logo.jpg'))) }}" alt="Mobile Shop Logo" class="logo">
                    </td>
                    <td style="width: 85%; text-align: center;">
                        <h1 style="margin: 0; font-size: 18px;">D-MART MOBILE SHOP</h1>
                        <p style="margin: 5px 0; font-size: 10px;">Dealers in Mobile Phones, Mobile Accessories, Repairs Service & Import Wholesale and Retail</p>
                        <p style="margin: 5px 0; font-size: 10px;">Rambukkana Road, Hiriwadunna, Sri Lanka</p>
                        <p style="margin: 5px 0; font-size: 10px;"><strong>| Hotline: 0763471705 | Email: chammikaelectronic@gmail.com |</strong></p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Invoice Details Section -->
        <table class="table-layout">
            <tr>
                <td>
                    <div class="details">
                        <p><strong>Invoice To:</strong> {{ $sale->customer_name }}</p>
                        <p><strong>Phone Number:</strong> {{ $sale->phone_number }}</p>
                    </div>
                </td>
                <td>
                    <div class="section">
                        <p><strong>Invoice No:</strong> {{ $sale->invoice_number }}</p>
                        <p><strong>Issue Date:</strong> {{ $sale->created_at }}</p>
                        <p><strong>Sales Rep:</strong> {{ $sale->cashier_name }}</p>
                        <p><strong>Payment Method:</strong> Cash</p>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="table">
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
                    $items = json_decode($sale->items, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $itemCount = count($items);
                        for ($i = 0; $i < 10; $i++) {
                            if ($i < $itemCount) {
                                $item = $items[$i];
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
                    } else {
                        echo '<tr><td colspan="5">Invalid items data</td></tr>';
                    }
                @endphp
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><strong>Total</strong></td>
                    <td class="text-right">{{ number_format($sale->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer Section -->
        <div style="text-align: center; margin-top: 1px;">
            <p style="margin: 0; font-size: 9px;">Visit us and enjoy your shopping experience</p>
            <p style="margin: 0; font-size: 9px; text-align: left;">* Warranty for 1 Year Less 21 Working Days for computer hardware.</p>
            <p style="margin: 0; font-size: 9px; text-align: left;">* No Warranty for Burn Marks, Scratches, Physical Damages, and any other damage caused by user activities.</p>
            <p style="margin: 0; font-size: 9px; text-align: left;">* Goods sold once cannot be returned.</p>
        </div>

        <!-- Signature Section -->
        <table style="width: 100%; margin-top: 10px; table-layout: fixed;">
            <tr>
                <td style="width: 50%; text-align: left;">
                    <p>__________________________</p>
                    <p style="margin-left: 50px; font-size: 7px;">Authorized By</p>
                </td>
                <td style="width: 50%; text-align: right;">
                    <p>__________________________</p>
                    <p style="margin-right: 50px; font-size: 7px;">Received By</p>
                </td>
            </tr>
        </table>

        <!-- Website URL -->
        <p style="text-align: center; margin-top: -20px;">www.chammikaelectronic.lk</p>
    </div>

    @if(isset($show_download_button) && $show_download_button)
        <button onclick="downloadPDF()">Download PDF</button>
    @endif
</body>
</html>

