<?php

namespace App\States;

class ServedState extends OrderState
{
    public function getStatus(): string { return 'served'; }
    public function getLabel(): string  { return 'Served'; }
    public function canConfirm(): bool       { return false; }
    public function canStartPreparing(): bool { return false; }
    public function canMarkReady(): bool      { return false; }
    public function canServe(): bool          { return false; }
    public function canBill(): bool           { return true; }
    public function canCancel(): bool         { return false; }
}
