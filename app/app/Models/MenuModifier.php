<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuModifier extends Model
{
    protected $fillable = ['menu_item_id', 'name', 'price', 'is_available', 'sort_order'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'is_available' => 'boolean'];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function getFormattedPrice(): string
    {
        return $this->price > 0 ? '+£'.number_format($this->price, 2) : 'Bepul';
    }
}
