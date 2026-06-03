<?php

namespace App\Models;

use App\Contracts\MenuComponentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Composite Pattern — Leaf
 */
class ComboItem extends Model implements MenuComponentInterface
{
    protected $fillable = ['combo_id', 'menu_item_id', 'quantity'];

    public function combo(): BelongsTo    { return $this->belongsTo(Combo::class); }
    public function menuItem(): BelongsTo { return $this->belongsTo(MenuItem::class); }

    public function getName(): string        { return $this->menuItem->name; }
    public function getPrice(): float        { return (float) ($this->menuItem->price * $this->quantity); }
    public function getDescription(): string { return $this->menuItem->description ?? ''; }
    public function isComposite(): bool      { return false; }
}
