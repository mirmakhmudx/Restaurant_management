<?php

namespace App\States;

class CancelledState extends OrderState
{
    public function getStatus(): string { return 'cancelled'; }
    public function getLabel(): string  { return 'Cancelled'; }
    public function canConfirm(): bool       { return false; }
    public function canStartPreparing(): bool { return false; }
    public function canMarkReady(): bool      { return false; }
    public function canServe(): bool          { return false; }
    public function canBill(): bool           { return false; }
    public function canCancel(): bool         { return false; }
}
