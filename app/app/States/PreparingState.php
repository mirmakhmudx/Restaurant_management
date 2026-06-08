<?php

namespace App\States;

class PreparingState extends OrderState
{
    public function getStatus(): string { return 'preparing'; }
    public function getLabel(): string  { return 'Being Prepared'; }
    public function canConfirm(): bool       { return false; }
    public function canStartPreparing(): bool { return false; }
    public function canMarkReady(): bool      { return true; }
    public function canServe(): bool          { return false; }
    public function canBill(): bool           { return false; }
    public function canCancel(): bool         { return true; }
}
