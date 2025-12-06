<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #fff;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #2563eb;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info h1 {
            color: #2563eb;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .company-info p {
            color: #64748b;
            font-size: 12px;
        }

        .quotation-badge {
            background: #2563eb;
            color: #fff;
            padding: 10px 25px;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-box {
            width: 48%;
        }

        .info-box h3 {
            color: #2563eb;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .info-box p {
            font-size: 13px;
            margin-bottom: 5px;
            color: #475569;
        }

        .info-box p strong {
            color: #1e293b;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background: #2563eb;
            color: #fff;
        }

        .items-table th {
            padding: 12px 15px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }

        .items-table th:nth-child(3),
        .items-table th:nth-child(4),
        .items-table th:nth-child(5),
        .items-table th:nth-child(6),
        .items-table td:nth-child(3),
        .items-table td:nth-child(4),
        .items-table td:nth-child(5),
        .items-table td:nth-child(6) {
            text-align: center;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table tbody tr:hover {
            background: #f8fafc;
        }

        .items-table td {
            padding: 12px 15px;
            font-size: 13px;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .totals-box {
            width: 300px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-row.grand-total {
            background: #2563eb;
            color: #fff;
            padding: 15px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: 600;
            border: none;
        }

        .terms-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .terms-section h3 {
            color: #2563eb;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .terms-section p {
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
            font-size: 12px;
            color: #64748b;
        }

        .notes-section {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #f59e0b;
        }

        .notes-section h4 {
            color: #92400e;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .notes-section p {
            font-size: 12px;
            color: #78350f;
        }

        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 12px 25px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 500;
        }

        .print-btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        🖨️ Print Quotation
    </button>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>QuotePro</h1>
                <p>{{ $quotation->user->name }}</p>
                <p>{{ $quotation->user->email }}</p>
            </div>
            <div class="quotation-badge">
                Quotation
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Bill To</h3>
                <p><strong>{{ $quotation->customer->name }}</strong></p>
                @if($quotation->customer->company)
                    <p>{{ $quotation->customer->company }}</p>
                @endif
                @if($quotation->customer->address)
                    <p>{{ $quotation->customer->address }}</p>
                @endif
                @if($quotation->customer->phone)
                    <p>Phone: {{ $quotation->customer->phone }}</p>
                @endif
                @if($quotation->customer->email)
                    <p>Email: {{ $quotation->customer->email }}</p>
                @endif
                @if($quotation->customer->gst_number)
                    <p>GST: {{ $quotation->customer->gst_number }}</p>
                @endif
            </div>
            <div class="info-box" style="text-align: right;">
                <h3>Quotation Details</h3>
                <p><strong>Quotation #:</strong> {{ $quotation->quotation_number }}</p>
                <p><strong>Date:</strong> {{ $quotation->quotation_date->format('d M Y') }}</p>
                <p><strong>Time:</strong> {{ date('h:i A', strtotime($quotation->quotation_time)) }}</p>
                <p><strong>Valid Until:</strong> {{ $quotation->validity_date->format('d M Y') }}</p>
            </div>
        </div>

        @if($quotation->notes)
            <div class="notes-section">
                <h4>Notes</h4>
                <p>{{ $quotation->notes }}</p>
            </div>
        @endif

        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>GST %</th>
                    <th>GST Amt</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->gst_percentage }}%</td>
                        <td>₹{{ number_format($item->gst_amount, 2) }}</td>
                        <td>₹{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <div class="totals-box">
                <div class="totals-row">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>Total GST:</span>
                    <span>₹{{ number_format($quotation->total_gst, 2) }}</span>
                </div>
                <div class="totals-row grand-total">
                    <span>Grand Total:</span>
                    <span>₹{{ number_format($quotation->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if($quotation->terms_conditions)
            <div class="terms-section">
                <h3>Terms & Conditions</h3>
                <p>{{ $quotation->terms_conditions }}</p>
            </div>
        @endif

        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">Customer Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print on load (optional - uncomment if needed)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

