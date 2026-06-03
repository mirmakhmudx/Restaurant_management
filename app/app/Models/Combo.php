<?php

namespace App\Models;

use App\Contracts\MenuComponentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Composite Pattern — Composite (container)
 */
class Combo extends Model implements MenuComponentInterface
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'price', 'is_active', 'image_path'];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ComboItem::class);
    }

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'combo_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // MenuComponentInterface
    public function getName(): string        { return $this->name; }
    public function getPrice(): float        { return (float) $this->price; }
    public function getDescription(): string { return $this->description ?? ''; }
    public function isComposite(): bool      { return true; }

    public function getOriginalPrice(): float
    {
        return $this->items->sum(fn($i) => ($i->menuItem?->price ?? 0) * $i->quantity);
    }

    public function getSavings(): float
    {
        return round($this->getOriginalPrice() - $this->getPrice(), 2);
    }

    public function getFormattedPrice(): string
    {
        return '£' . number_format($this->price, 2);
    }
}
