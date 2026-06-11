<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffShift extends Model
{
    protected $fillable = [
        'user_id', 'created_by', 'shift_start', 'shift_end', 'type', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'shift_start' => 'datetime',
            'shift_end'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return is_null($this->shift_end);
    }

    public function getDurationMinutes(): int
    {
        $end = $this->shift_end ?? now();
        return (int) $end->diffInMinutes($this->shift_start);
    }

    public function getFormattedDuration(): string
    {
        $mins  = $this->getDurationMinutes();
        $hours = intdiv($mins, 60);
        $rem   = $mins % 60;

        if ($hours > 0 && $rem > 0) {
            return $hours . 's ' . $rem . 'm';
        }
        if ($hours > 0) {
            return $hours . ' soat';
        }
        return $rem . ' daqiqa';
    }

    public function getTypeLabel(): string
    {
        $labels = [
            'morning'  => '🌅 Ertalabki',
            'evening'  => '🌆 Kechki',
            'night'    => '🌙 Tungi',
            'overtime' => '⚡ Qo\'shimcha',
            'regular'  => '📋 Oddiy',
        ];
        return $labels[$this->type] ?? '📋 Oddiy';
    }

    public function getTypeBadge(): string
    {
        $badges = [
            'morning'  => 'bg-amber-50 text-amber-700 border-amber-200',
            'evening'  => 'bg-blue-50 text-blue-700 border-blue-200',
            'night'    => 'bg-purple-50 text-purple-700 border-purple-200',
            'overtime' => 'bg-red-50 text-red-700 border-red-200',
            'regular'  => 'bg-gray-100 text-gray-600 border-gray-200',
        ];
        return $badges[$this->type] ?? 'bg-gray-100 text-gray-600 border-gray-200';
    }
}
