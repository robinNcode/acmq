<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background: #f5f5f5; }
        .invoice-container { max-width: 800px; margin: 20px auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info h1 { font-size: 24px; color: #2563eb; margin-bottom: 4px; }
        .company-info p { font-size: 12px; color: #666; }

        .invoice-details { text-align: right; }
        .invoice-details h2 { font-size: 28px; color: #2563eb; letter-spacing: 2px; margin-bottom: 8px; }
        .invoice-details .meta { font-size: 13px; color: #666; }
        .invoice-details .meta strong { color: #333; }

        .parties { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .party { flex: 1; }
        .party h3 { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 6px; }
        .party p { font-size: 14px; line-height: 1.6; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background: #2563eb; color: #fff; padding: 10px 12px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        tbody tr:hover { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .totals { display: flex; justify-content: flex-end; }
        .totals-table { width: 300px; }
        .totals-table tr td { padding: 6px 12px; font-size: 13px; }
        .totals-table tr.grand-total td { border-top: 2px solid #2563eb; font-size: 16px; font-weight: bold; color: #2563eb; padding-top: 10px; }

        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; }
        .footer p { font-size: 12px; color: #999; }

        .print-btn { position: fixed; top: 20px; right: 20px; background: #2563eb; color: #fff; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; box-shadow: 0 2px 6px rgba(37,99,235,0.3); }
        .print-btn:hover { background: #1d4ed8; }

        @media print {
            body { background: #fff; }
            .invoice-container { box-shadow: none; margin: 0; padding: 20px; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>

    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>Accounting MQ</h1>
                <p>Professional Accounting Solutions</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <div class="meta">
                    <p><strong>Invoice #:</strong> {{ $sale->code }}</p>
                    <p><strong>Date:</strong> {{ $sale->selling_date?->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="parties">
            <div class="party">
                <h3>Bill To</h3>
                <p><strong>{{ $sale->customer->name ?? 'N/A' }}</strong></p>
                @if($sale->customer)
                    <p>{{ $sale->customer->address ?? '' }}</p>
                    <p>{{ $sale->customer->phone ?? '' }}</p>
                @endif
            </div>
            <div class="party" style="text-align: right;">
                <h3>Branch</h3>
                <p><strong>{{ $sale->branch->name ?? 'N/A' }}</strong></p>
                @if($sale->branch)
                    <p>{{ $sale->branch->address ?? '' }}</p>
                    <p>{{ $sale->branch->district ?? '' }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @php $items = $sale->product_info ?? []; @endphp
            @forelse($items as $i => $item)
                @php
                    $product = $products[$item['product_id']] ?? null;
                    $qty = $item['quantity'] ?? 0;
                    $price = $item['unit_price'] ?? 0;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $product->name ?? 'Unknown Product' }}</td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-right">৳ {{ number_format($price, 2) }}</td>
                    <td class="text-right">৳ {{ number_format($qty * $price, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding: 20px; color: #999;">No items</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">৳ {{ number_format($sale->total_price, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">- ৳ {{ number_format($sale->discount, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td class="text-right">৳ {{ number_format($sale->total_price - $sale->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td class="text-right">৳ {{ number_format($sale->paid, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Due</strong></td>
                    <td class="text-right"><strong>৳ {{ number_format($sale->due, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</body>
</html>
