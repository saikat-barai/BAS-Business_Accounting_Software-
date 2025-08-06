<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fff;">

    <div style="max-width: 800px; margin: 0 auto; padding: 40px; border: 1px solid #ccc;">

        <!-- Header -->
        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td>
                    <h2 style="margin: 0; color: #333;">INVOICE</h2>
                    <p style="margin: 5px 0 0; font-size: 14px;">Invoice #:
                        <strong>{{ $invoice->invoice_number }}</strong></p>
                    <p style="margin: 2px 0; font-size: 14px;">Date:
                        <strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}</strong></p>
                    <p style="margin: 2px 0; font-size: 14px;">Status:
                        <strong
                            style="color: {{ $invoice->status == 'Unpaid' ? '#d35400' : 'green' }};">{{ ucfirst($invoice->status) }}</strong>
                    </p>
                </td>
                <td align="right">
                    <h3 style="margin: 0; color: #2c3e50;">{{ $companyName ?? 'Your Company' }}</h3>
                    <p style="margin: 4px 0; font-size: 14px;">{{ $companyEmail ?? 'info@company.com' }}</p>
                    <p style="margin: 2px 0; font-size: 14px;">{{ $companyAddress ?? '123 Business Street' }}</p>
                </td>
            </tr>
        </table>

        <hr style="border: 1px solid #eee; margin: 20px 0;">

        <!-- Billed To -->
        <div style="margin-bottom: 20px;">
            <p style="margin: 0 0 5px; font-weight: bold;">Billed To:</p>
            <p style="margin: 2px 0; font-size: 14px;">{{ $invoice->client->name }}</p>
            <p style="margin: 2px 0; font-size: 14px;">{{ $invoice->client->email }}</p>
            <p style="margin: 2px 0; font-size: 14px;">{{ $invoice->client->address }}</p>
        </div>

        <!-- Items -->
        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background-color: #f0f0f0; border-bottom: 1px solid #ccc;">
                    <th align="left">Description</th>
                    <th align="center">Qty</th>
                    <th align="right">Unit Price (Tk)</th>
                    <th align="right">Total (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td>{{ $item->description }}</td>
                        <td align="center">{{ $item->quantity }}</td>
                        <td align="right">Tk {{ number_format($item->unit_price, 2) }}</td>
                        <td align="right">Tk {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <table align="right" cellpadding="8" cellspacing="0"
            style="margin-top: 20px; width: 300px; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="border-top: 1px solid #ccc;">Subtotal:</td>
                <td align="right" style="border-top: 1px solid #ccc;">
                    Tk {{ number_format($invoice->subtotal, 2) }}
                </td>
            </tr>
            <tr>
                <td>Tax ({{ $invoice->tax }}%):</td>
                <td align="right">
                    Tk {{ number_format(($invoice->subtotal * $invoice->tax) / 100, 2) }}
                </td>
            </tr>
            <tr>
                <td>Discount:</td>
                <td align="right">
                    - Tk {{ number_format($invoice->discount, 2) }}
                </td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td>Total:</td>
                <td align="right">
                    Tk {{ number_format($invoice->total, 2) }}
                </td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <!-- Footer -->
        <div style="margin-top: 40px; text-align: center; color: #888; font-size: 12px;">
            <p>Thank you for doing business with us.</p>
            <p>Contact: {{ $companyEmail ?? 'info@yourcompany.com' }} | {{ $companyPhone ?? '+123 456 7890' }}</p>
        </div>
    </div>
</body>

</html>
