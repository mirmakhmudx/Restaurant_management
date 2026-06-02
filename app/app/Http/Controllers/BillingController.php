<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Order;
use App\Services\BillingFacade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        private readonly BillingFacade $billing
    ) {}

    public function index(): View
    {
        $pending    = Order::with(['table', 'items', 'waiter'])->where('status', 'served')->oldest()->get();
        $recent     = Bill::with(['order.table'])->latest()->take(20)->get();
        $todayTotal = Bill::whereDate('paid_at', today())->sum('total');
        $todayCount = Bill::whereDate('paid_at', today())->count();
        return view('billing.index', compact('pending', 'recent', 'todayTotal', 'todayCount'));
    }

    public function create(Order $order): View
    {
        abort_unless($order->status->value === 'served', 404);
        $strategies = BillingFacade::strategies();
        return view('billing.create', compact('order', 'strategies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'order_id'       => ['required', 'exists:orders,id'],
            'payment_method' => ['required', 'in:cash,card,online'],
            'discount'       => ['nullable', 'numeric', 'min:0'],
            'service_fee'    => ['nullable', 'numeric', 'min:0'],
            'tax_rate'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes'          => ['nullable', 'string'],
        ]);

        $order    = Order::findOrFail($request->order_id);
        $subtotal = $order->total;
        $discount = $request->discount ?? 0;
        $taxRate  = $request->tax_rate ?? 20;
        $svcFee   = $request->service_fee ?? 0;

        $afterDiscount = $subtotal - $discount;
        $taxAmount     = round($afterDiscount * $taxRate / 100, 2);
        $grandTotal    = $afterDiscount + $taxAmount + $svcFee;

        $bill = Bill::create([
            'order_id'       => $order->id,
            'cashier_id'     => auth()->id(),
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total'          => $afterDiscount,
            'tax_rate'       => $taxRate,
            'tax_amount'     => $taxAmount,
            'service_fee'    => $svcFee,
            'grand_total'    => $grandTotal,
            'payment_method' => $request->payment_method,
            'paid_at'        => now(),
            'notes'          => $request->notes,
        ]);

        // Line items yaratish
        foreach ($order->items as $item) {
            $bill->lineItems()->create([
                'description' => $item->name,
                'quantity'    => $item->quantity,
                'unit_price'  => $item->price,
                'subtotal'    => $item->subtotal,
                'type'        => 'item',
            ]);
        }

        if ($discount > 0) {
            $bill->lineItems()->create([
                'description' => 'Discount',
                'quantity'    => 1,
                'unit_price'  => -$discount,
                'subtotal'    => -$discount,
                'type'        => 'discount',
            ]);
        }

        if ($taxAmount > 0) {
            $bill->lineItems()->create([
                'description' => "Tax ({$taxRate}%)",
                'quantity'    => 1,
                'unit_price'  => $taxAmount,
                'subtotal'    => $taxAmount,
                'type'        => 'tax',
            ]);
        }

        if ($svcFee > 0) {
            $bill->lineItems()->create([
                'description' => 'Service Fee',
                'quantity'    => 1,
                'unit_price'  => $svcFee,
                'subtotal'    => $svcFee,
                'type'        => 'service_fee',
            ]);
        }

        $order->update(['status' => 'billed']);

        return redirect()->route('billing.show', $bill)
            ->with('success', 'Bill yaratildi!');
    }

    public function show(Bill $bill): View
    {
        $bill->load(['order.items', 'order.table', 'order.waiter']);
        return view('billing.show', compact('bill'));
    }

    public function receipt(Bill $bill): Response
    {
        $bill->load(['order.items', 'order.table', 'order.waiter']);
        $pdf = Pdf::loadView('billing.receipt', compact('bill'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');
        return $pdf->download("receipt-{$bill->order->order_number}.pdf");
    }
    public function split(Request $request, \App\Models\Order $order): RedirectResponse
    {
        $request->validate([
            'split_count'    => ['required', 'integer', 'min:2', 'max:10'],
            'payment_method' => ['required', 'in:cash,card,online'],
        ]);

        $splitCount  = $request->split_count;
        $subtotal    = $order->total;
        $taxRate     = 20;
        $splitAmount = round($subtotal / $splitCount, 2);
        $taxAmount   = round($splitAmount * $taxRate / 100, 2);
        $grandSplit  = $splitAmount + $taxAmount;

        $bills = [];
        for ($i = 1; $i <= $splitCount; $i++) {
            $bills[] = Bill::create([
                'order_id'       => $order->id,
                'cashier_id'     => auth()->id(),
                'subtotal'       => $splitAmount,
                'discount'       => 0,
                'total'          => $splitAmount,
                'tax_rate'       => $taxRate,
                'tax_amount'     => $taxAmount,
                'service_fee'    => 0,
                'grand_total'    => $grandSplit,
                'payment_method' => $request->payment_method,
                'paid_at'        => now(),
                'is_split'       => true,
                'split_count'    => $splitCount,
                'split_index'    => $i,
            ]);
        }

        return redirect()->route('billing.show', $bills[0])
            ->with('success', "Bill {$splitCount} ga bo'lindi! Har biri: £" . number_format($grandSplit, 2));
    }
}
