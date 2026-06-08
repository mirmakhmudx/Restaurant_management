<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'menu_item_id', 'name', 'price',
        'quantity', 'subtotal', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'price'    => 'float',
            'subtotal' => 'float',
        ];
    }

    public function order(): BelongsTo    { return $this->belongsTo(Order::class); }
    public function menuItem(): BelongsTo { return $this->belongsTo(MenuItem::class); }

    public function getFormattedPrice(): string    { return '£' . number_format($this->price, 2); }
    public function getFormattedSubtotal(): string { return '£' . number_format($this->subtotal, 2); }
    public function modifiers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    public function getModifierTotal(): float
    {
        return (float) $this->modifiers->sum('price');
    }

    public function getSubtotalWithModifiers(): float
    {
        return ($this->price + $this->getModifierTotal()) * $this->quantity;
    }
}
