<?php

namespace App\Models;

use App\Contracts\MenuItemInterface;
use App\Enums\MenuItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MenuItem extends Model implements MenuItemInterface
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'image_path', 'type', 'price',
        'allergens', 'prep_time_minutes', 'calories',
        'is_available', 'is_vegetarian', 'is_vegan', 'is_gluten_free', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type'           => MenuItemType::class,
            'price'          => 'float',
            'allergens'      => 'array',
            'is_available'   => 'boolean',
            'is_vegetarian'  => 'boolean',
            'is_vegan'       => 'boolean',
            'is_gluten_free' => 'boolean',
        ];
    }

    // ── MenuItemInterface ─────────────────────────────
    public function getName(): string        { return $this->name; }
    public function getPrice(): float        { return $this->price; }
    public function getDescription(): string { return $this->description ?? ''; }
    public function getType(): string        { return $this->type->value; }
    public function getPrepTime(): int       { return $this->prep_time_minutes; }
    public function isAvailable(): bool      { return $this->is_available; }

    // ── Image ─────────────────────────────────────────
    public function getImageUrl(): ?string
    {
        return $this->image_path
            ? \Illuminate\Support\Facades\Storage::url($this->image_path)
            : null;
    }

    public function hasImage(): bool
    {
        return !empty($this->image_path) && $this->image_path !== null;
    }

    // ── Helpers ───────────────────────────────────────
    public function getFormattedPrice(): string
    {
        return '£' . number_format($this->price, 2);
    }

    public function getDietaryLabels(): array
    {
        $labels = [];
        if ($this->is_vegan)       $labels[] = ['label' => 'Vegan',       'icon' => '🌱'];
        if ($this->is_vegetarian)  $labels[] = ['label' => 'Vegetarian',  'icon' => '🥬'];
        if ($this->is_gluten_free) $labels[] = ['label' => 'Gluten Free', 'icon' => '🌾'];
        return $labels;
    }

    public function hasAllergen(string $allergen): bool
    {
        return in_array($allergen, $this->allergens ?? []);
    }

    // ── Scopes ────────────────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeOfType($query, MenuItemType $type)
    {
        return $query->where('type', $type->value);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'ilike', "%{$term}%")
              ->orWhere('description', 'ilike', "%{$term}%");
        });
    }
    public function getAvailable(): \Illuminate\Support\Collection
    {
        return $this->repository->all()
            ->where('is_available', true)
            ->values();
    }
    public function modifiers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuModifier::class)->orderBy('sort_order');
    }

    public function activeModifiers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuModifier::class)
            ->where('is_available', true)
            ->orderBy('sort_order');
    }
}
