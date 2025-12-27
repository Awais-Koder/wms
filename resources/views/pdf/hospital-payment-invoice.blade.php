<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Invoice - {{ $payment->hospital->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #FFC107;
            padding-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .header h1 {
            margin: 0;
            color: #FFC107;
            font-size: 28px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .invoice-info-left,
        .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #FFC107;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-row {
            margin: 8px 0;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 150px;
        }

        .info-value {
            color: #333;
        }

        .payment-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 30px 0;
        }

        .payment-details h2 {
            margin: 0 0 20px 0;
            color: #FFC107;
            font-size: 20px;
        }

        .amount-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .amount-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .amount-table td:first-child {
            font-weight: bold;
            color: #555;
            width: 60%;
        }

        .amount-table td:last-child {
            text-align: right;
            color: #333;
            font-size: 16px;
        }

        .amount-table tr.total td {
            border-top: 2px solid #FFC107;
            font-weight: bold;
            font-size: 18px;
            color: #FFC107;
            padding-top: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .status-collected {
            background-color: #4CAF50;
            color: white;
        }

        .status-pending {
            background-color: #FF9800;
            color: white;
        }

        .status-full {
            background-color: #2196F3;
            color: white;
        }

        .status-partial {
            background-color: #FFC107;
            color: white;
        }

        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid #FFC107;
        }

        .notes-section h3 {
            margin: 0 0 10px 0;
            color: #F57C00;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div>
            <img src="{{ public_path('images/wms-logo.jpg') }}" alt="Company Logo" height="80">
        </div>
        <div class="header">
            <div>
                <h1>PAYMENT INVOICE</h1>
                <p>CH Trading Company</p>
                <p>Invoice Date: {{ now()->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-info-left">
                <div class="info-section">
                    <h3>Hospital Information</h3>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $payment->hospital->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Hospital No:</span>
                        <span class="info-value">{{ $payment->hospital->uuid }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $payment->hospital->address }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Doctor:</span>
                        <span class="info-value">{{ $payment->hospital->doctor_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact:</span>
                        <span class="info-value">{{ $payment->hospital->mobile_number_1 }}</span>
                    </div>
                </div>
            </div>

            <div class="invoice-info-right">
                <div class="info-section">
                    <h3>Payment Information</h3>
                    <div class="info-row">
                        <span class="info-label">Invoice #:</span>
                        <span class="info-value">INV-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Month:</span>
                        <span class="info-value">{{ $payment->month->format('F Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Type:</span>
                        <span class="status-badge status-{{ $payment->payment_type }}">
                            {{ ucfirst($payment->payment_type) }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="status-badge status-{{ $payment->is_collected ? 'collected' : 'pending' }}">
                            {{ $payment->is_collected ? 'Collected' : 'Pending' }}
                        </span>
                    </div>
                    @if ($payment->is_collected && $payment->collection_date)
                        <div class="info-row">
                            <span class="info-label">Collected On:</span>
                            <span class="info-value">{{ $payment->collection_date->format('F d, Y') }}</span>
                        </div>
                    @endif
                    @if ($payment->is_collected && $payment->collectedBy)
                        <div class="info-row">
                            <span class="info-label">Collected By:</span>
                            <span class="info-value">{{ $payment->collectedBy->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
            <h2>Payment Breakdown</h2>
            <table class="amount-table">
                <tr>
                    <td>Total Agreement Amount</td>
                    <td>PKR {{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Amount Paid</td>
                    <td>PKR {{ number_format($payment->paid_amount ?? 0, 2) }}</td>
                </tr>
                @if ($payment->payment_type === 'partial')
                    <tr>
                        <td>Remaining Balance</td>
                        <td style="color: #F44336;">PKR {{ number_format($payment->remaining_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total">
                    <td>
                        @if ($payment->payment_type === 'full')
                            Total Paid
                        @else
                            Total Paid This Month
                        @endif
                    </td>
                    <td>PKR {{ number_format($payment->paid_amount ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if ($payment->notes)
            <div class="notes-section">
                <h3>Notes</h3>
                <p>{{ $payment->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any queries, please contact the administration.</p>
            <p>&copy; {{ date('Y') }} CH Trading Company. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
