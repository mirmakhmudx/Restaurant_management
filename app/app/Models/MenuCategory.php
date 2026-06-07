<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MenuCategory extends Model
{
    protected $fillable = ['name', 'icon', 'slug', 'is_active', 'sort_order'];

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($cat) {
            if (!$cat->slug) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }
}
