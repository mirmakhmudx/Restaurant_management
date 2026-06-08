<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'guest_name','guest_phone','guest_email',
        'table_id','reserved_at','guest_count','status','notes',
    ];

    protected function casts(): array
    {
        return ['reserved_at' => 'datetime'];
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function statusBadge(): string
    {
        return match($this->status) {
            'confirmed' => 'bg-green-50 text-green-700 border-green-200',
            'seated'    => 'bg-blue-50 text-blue-700 border-blue-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            'no_show'   => 'bg-gray-100 text-gray-500 border-gray-200',
            default     => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'confirmed' => 'Tasdiqlangan',
            'seated'    => 'Keldi',
            'cancelled' => 'Bekor',
            'no_show'   => 'Kelmadi',
            default     => 'Kutilmoqda',
        };
    }
}
