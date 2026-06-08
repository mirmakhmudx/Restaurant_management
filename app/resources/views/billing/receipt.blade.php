<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
    .receipt { width: 100%; max-width: 400px; margin: 0 auto; padding: 20px; }
    .header { text-align: center; border-bottom: 2px solid #111; padding-bottom: 15px; margin-bottom: 15px; }
    .header h1 { font-size: 24px; font-weight: bold; letter-spacing: 2px; }
    .header p { font-size: 11px; color: #555; margin-top: 3px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 11px; }
    .info-label { color: #555; }
    .info-value { font-weight: bold; }
    .divider { border-top: 1px dashed #999; margin: 12px 0; }
    .items-header { display: flex; font-size: 10px; color: #777; font-weight: bold; padding-bottom: 5px; border-bottom: 1px solid #ddd; margin-bottom: 8px; }
    .item-row { display: flex; margin-bottom: 6px; font-size: 11px; }
    .item-name { flex: 1; }
    .item-qty { width: 30px; text-align: center; }
    .item-price { width: 70px; text-align: right; }
    .item-total { width: 70px; text-align: right; font-weight: bold; }
    .totals { margin-top: 10px; }
    .total-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 11px; }
    .grand-total { display: flex; justify-content: space-between; padding-top: 8px; border-top: 2px solid #111; font-size: 16px; font-weight: bold; margin-top: 5px; }
    .footer { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #999; }
    .footer p { font-size: 10px; color: #777; margin-bottom: 3px; }
    .badge { display: inline-block; background: #111; color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
    .strategy { background: #f5f5f5; padding: 8px; border-radius: 4px; margin: 8px 0; font-size: 11px; }
</style>
</head>
<body>
<div class="receipt">
    {{-- Split Bill badge --}}
    @if($bill->is_split)
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:8px;margin-bottom:12px;text-align:center">
            <p style="font-weight:bold;color:#1d4ed8;font-size:13px">
                Split Bill {{ $bill->split_index }} / {{ $bill->split_count }}
            </p>
            <p style="color:#3b82f6;font-size:10px;margin-top:2px">
                {{ $bill->split_count }} ta bo'lakdan {{ $bill->split_index }}-qismi
            </p>
        </div>
    @endif
    <div class="header">
        <h1>🍽 BitePlate</h1>
        <p>Smart Restaurant Management System</p>
        <p style="margin-top:8px; font-size:10px;">Receipt #{{ str_pad($bill->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="info-row">
        <span class="info-label">Order:</span>
        <span class="info-value">{{ $bill->order->order_number }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Table:</span>
        <span class="info-value">{{ $bill->order->table ? 'Table '.$bill->order->table->number : 'Takeaway' }}</span>
    </div>
    @if($bill->order->waiter)
    <div class="info-row">
        <span class="info-label">Server:</span>
        <span class="info-value">{{ $bill->order->waiter->name }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Date:</span>
        <span class="info-value">{{ $bill->paid_at->format('d M Y, H:i') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Payment:</span>
        <span class="info-value">{{ ucfirst($bill->payment_method) }}</span>
    </div>

    <div class="flex justify-between text-sm">
        <span class="text-gray-600">Subtotal:</span>
        <span>£{{ number_format($bill->subtotal, 2) }}</span>
    </div>
    @if($bill->discount > 0)
        <div class="flex justify-between text-sm text-green-600">
            <span>Discount:</span>
            <span>-£{{ number_format($bill->discount, 2) }}</span>
        </div>
    @endif
    <div class="flex justify-between text-sm">
        <span class="text-gray-600">Tax ({{ $bill->tax_rate }}%):</span>
        <span>£{{ number_format($bill->tax_amount, 2) }}</span>
    </div>
    @if($bill->service_fee > 0)
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Service Fee:</span>
            <span>£{{ number_format($bill->service_fee, 2) }}</span>
        </div>
    @endif
    <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
        <span>TOTAL:</span>
        <span>£{{ number_format($bill->grand_total ?: $bill->total, 2) }}</span>
    </div>


    <div class="divider"></div>

    <div class="items-header">
        <span style="flex:1">Item</span>
        <span style="width:30px;text-align:center">Qty</span>
        <span style="width:70px;text-align:right">Price</span>
        <span style="width:70px;text-align:right">Total</span>
    </div>

    @foreach($bill->order->items as $item)
    <div class="item-row">
        <span class="item-name">{{ $item->name }}</span>
        <span class="item-qty">{{ $item->quantity }}</span>
        <span class="item-price">£{{ number_format($item->price, 2) }}</span>
        <span class="item-total">£{{ number_format($item->subtotal, 2) }}</span>
    </div>
    @endforeach

    <div class="divider"></div>

    @if($bill->pricing_strategy !== 'Standard')
    <div class="strategy">
        <div class="total-row">
            <span>Subtotal</span>
            <span>{{ $bill->getFormattedSubtotal() }}</span>
        </div>
        <div class="total-row" style="color: #16a34a;">
            <span>{{ $bill->pricing_strategy }} Discount</span>
            <span>-{{ $bill->getFormattedDiscount() }}</span>
        </div>
    </div>
    @endif

    <div class="grand-total">
        <span>TOTAL</span>
        <span>{{ $bill->getFormattedTotal() }}</span>
    </div>

    <div class="footer">
        <p>Thank you for dining with us!</p>
        <p>BitePlate Restaurant · localhost:8000</p>
        <p style="margin-top:8px;"><span class="badge">PAID</span></p>
    </div>
</div>
</body>
</html>
