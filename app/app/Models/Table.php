<?php

namespace App\Models;

use App\Enums\TableLocation;
use App\Enums\TableStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = ['number', 'capacity', 'status', 'location', 'notes'];

    protected function casts(): array
    {
        return [
            'status'   => TableStatus::class,
            'location' => TableLocation::class,
        ];
    }

    // ── Relations ─────────────────────────────────────
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrder()
    {
        return $this->orders()
            ->whereNotIn('status', ['billed', 'cancelled'])
            ->latest()
            ->first();
    }

    // ── Helpers ───────────────────────────────────────
    public function isAvailable(): bool { return $this->status === TableStatus::Available; }

    public function displayName(): string { return "Table {$this->number}"; }

    public function capacityLabel(): string
    {
        return $this->capacity === 1 ? '1 person' : "{$this->capacity} people";
    }
}
