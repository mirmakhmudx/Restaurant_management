<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Bill extends Model
{
    protected $fillable = [
        'order_id', 'cashier_id', 'subtotal', 'discount',
        'total', 'payment_method', 'paid_at', 'notes',
        'tax_rate', 'tax_amount', 'service_fee', 'grand_total',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'float',
            'discount' => 'float',
            'total'    => 'float',
            'paid_at'  => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getFormattedTotal(): string    { return '£' . number_format($this->total, 2); }
    public function getFormattedDiscount(): string { return '£' . number_format($this->discount, 2); }
    public function getFormattedSubtotal(): string { return '£' . number_format($this->subtotal, 2); }

    public function hasDiscount(): bool { return $this->discount > 0; }

    public function paymentIcon(): string
    {
        return match($this->payment_method) {
            'card'        => '💳',
            'contactless' => '📱',
            default       => '💵',
        };
    }
    public function lineItems(): HasMany
    {
        return $this->hasMany(BillLineItem::class);
    }
}
