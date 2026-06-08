<?php

namespace App\States;

class PendingState extends OrderState
{
    public function getStatus(): string { return 'pending'; }
    public function getLabel(): string  { return 'Pending Confirmation'; }
    public function canConfirm(): bool       { return true; }
    public function canStartPreparing(): bool { return false; }
    public function canMarkReady(): bool      { return false; }
    public function canServe(): bool          { return false; }
    public function canBill(): bool           { return false; }
    public function canCancel(): bool         { return true; }
}
