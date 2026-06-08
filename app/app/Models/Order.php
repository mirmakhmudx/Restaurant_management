<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\States\BilledState;
use App\States\CancelledState;
use App\States\ConfirmedState;
use App\States\OrderState;
use App\States\PendingState;
use App\States\PreparingState;
use App\States\ReadyState;
use App\States\ServedState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'BP-' . str_pad(
                        (static::max('id') ?? 0) + 1,
                        4, '0', STR_PAD_LEFT
                    );
            }
        });
    }

    protected $fillable = [
        'order_number', 'table_id', 'waiter_id', 'status',
        'subtotal', 'discount', 'total', 'notes',
        'confirmed_at', 'prepared_at', 'served_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => OrderStatus::class,
            'subtotal'     => 'float',
            'discount'     => 'float',
            'total'        => 'float',
            'confirmed_at' => 'datetime',
            'prepared_at'  => 'datetime',
            'served_at'    => 'datetime',
        ];
    }

    // ── Relations ─────────────────────────────────────
    public function table(): BelongsTo  { return $this->belongsTo(Table::class); }
    public function waiter(): BelongsTo { return $this->belongsTo(User::class, 'waiter_id'); }
    public function items(): HasMany    { return $this->hasMany(OrderItem::class); }

    // ── State Pattern ─────────────────────────────────
    public function getState(): OrderState
    {
        return match($this->status) {
            OrderStatus::Pending   => new PendingState($this),
            OrderStatus::Confirmed => new ConfirmedState($this),
            OrderStatus::Preparing => new PreparingState($this),
            OrderStatus::Ready     => new ReadyState($this),
            OrderStatus::Served    => new ServedState($this),
            OrderStatus::Billed    => new BilledState($this),
            OrderStatus::Cancelled => new CancelledState($this),
        };
    }

    // ── Helpers ───────────────────────────────────────
    public static function generateOrderNumber(): string
    {
        $last = self::latest()->value('order_number');
        $next = $last ? (int) substr($last, 3) + 1 : 1;
        return 'BP-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function recalculate(): void
    {
        $subtotal = $this->items->sum('subtotal');
        $this->update([
            'subtotal' => $subtotal,
            'total'    => $subtotal - $this->discount,
        ]);
    }

    public function getFormattedTotal(): string
    {
        return '£' . number_format($this->total, 2);
    }

    public function itemCount(): int
    {
        return $this->items->sum('quantity');
    }
}
